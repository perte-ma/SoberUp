<?php
// Calcola i grammi di alcol puro contenuti in un drink.
function gramsOfAlcohol($volumeMl, $abvPercent, $alcoholDensity = 0.789)
{
    return $volumeMl * ($abvPercent / 100) * $alcoholDensity;
}

// Formula di Widmark: BAC (g/L) = (A / (peso_g * r)) - (beta * ore)
// Da utilizzare per calcolo rapido ma in versione script JS per avere un calcolo più veloce lato client
function bacWidmark($gramsAlcohol, $weightKg, $rFactor, $hoursElapsed, $eliminationRate = 0.15)
{
    $weightGrams = $weightKg * 1000;

    $initialBac = $gramsAlcohol / ($weightGrams * $rFactor) * 1000;
    $bac = $initialBac - ($eliminationRate * $hoursElapsed);

    return max(0.0, $bac);
}

// Formula di Watson per l'acqua corporea totale (TBW), alternativa al fattore r fisso di Widmark
function watsonTBW($weightKg, $heightCm, $age, $sex)
{
    $sex = strtoupper($sex);

    if ($sex === 'M') {
        return 2.447 - (0.09516 * $age) + (0.1074 * $heightCm) + (0.3362 * $weightKg);
    }
    if ($sex === 'F') {
        return -2.097 + (0.1069 * $heightCm) + (0.2466 * $weightKg);
    }
    return -1; // -1 IN CASO DI ERRORE
}

// Calcola il BAC usando il TBW di Watson al posto del fattore r fisso di Widmark
function bacWatson($gramsAlcohol, $weightKg, $heightCm, $age, $sex, $hoursElapsed, $eliminationRate = 0.15)
{
    $tbwLiters = watsonTBW($weightKg, $heightCm, $age, $sex);

    $initialBac = $gramsAlcohol / $tbwLiters;
    $bac = $initialBac - ($eliminationRate * $hoursElapsed);

    return max(0.0, $bac);
}


// Calcola l'orario stimato in cui il BAC di questa serata scendera' a 0, sommando TUTTI i drink attualmente in $drinks. 
// Si "aggiorna da sola" ogni volta che viene richiamata dopo aver aggiunto un drink, perche' ricalcola sempre da zero, non salva nulla.
function oraFineStimataSerata($drinks, $datainizio, $weightKg, $heightCm, $age, $sex, $eliminationRate = 0.15)
{
    $totalGrams = 0;
    foreach ($drinks as $d) {
        $totalGrams += gramsOfAlcohol($d['volume'], $d['gradazione']);
    }

    $tbwLiters = watsonTBW($weightKg, $heightCm, $age, $sex);
    $initialBac = $totalGrams / $tbwLiters;
    $oreFinoAZero = $initialBac / $eliminationRate;

    return strtotime($datainizio) + ($oreFinoAZero * 3600);
}

// Genera i punti {time, bac} per il grafico, campionando ogni $intervalMin minuti.
// $drinks deve arrivare da $db->getDrinkDiSerata(), gia' ordinato per orario.
function chartPointsSerata($drinks, $weightKg, $heightCm, $age, $sex, $intervalMin = 10)
{
    if (empty($drinks)) {
        return [];
    }

    $start = strtotime($drinks[0]['orario']);
    $end = oraFineStimataSerata($drinks, $drinks[0]['orario'], $weightKg, $heightCm, $age, $sex);

    $points = [];
    for ($t = $start; $t <= $end; $t += $intervalMin * 60) {
        $totalGrams = 0;
        foreach ($drinks as $d) {
            $drinkTime = strtotime($d['orario']);
            if ($drinkTime <= $t) {
                $ml = $d['volume']; // ml effettivamente bevuti in quella riga, non volume_standard
                $totalGrams += gramsOfAlcohol($ml, $d['gradazione']);
            }
        }

        $hoursElapsed = ($t - $start) / 3600;
        $bac = bacWatson($totalGrams, $weightKg, $heightCm, $age, $sex, $hoursElapsed);

        $points[] = ['time' => date('H:i', $t), 'bac' => round($bac, 3)];

        if ($bac <= 0 && $totalGrams > 0 && $t > $start) {
            break; // la serata e' "finita", non serve campionare oltre
        }
    }

    return $points;
}

// Calcola il BAC attuale di una serata aperta; la chiude nel DB se l'orario stimato
// di fine e' gia' passato (registrando quell'orario stimato, non il momento del controllo).
// Ritorna il BAC attuale (0 se la serata e' appena stata chiusa).
function currentSerataStatus($db, $serata, $user)
{
    $age = (new DateTime($user['data_nascita']))->diff(new DateTime())->y;
    $drinks = $db->getDrinkDiSerata($serata['idserata']);

    $oraFineStimata = oraFineStimataSerata($drinks, $serata['datainizio'], $user['peso'], $user['altezza'], $age, $user['sesso']);

    if (time() >= $oraFineStimata) {
        $db->chiudiSerata($serata['idserata'], date('Y-m-d H:i:s', $oraFineStimata));
        return 0.0;
    }

    $totalGrams = 0;
    foreach ($drinks as $d) {
        $ml = $d['volume']; // ml effettivamente bevuti in quella riga, non volume_standard
        $totalGrams += gramsOfAlcohol($ml, $d['gradazione']);
    }

    $hoursElapsed = (time() - strtotime($serata['datainizio'])) / 3600;
    return bacWatson($totalGrams, $user['peso'], $user['altezza'], $age, $user['sesso'], $hoursElapsed);
}
?>