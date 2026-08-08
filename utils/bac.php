<?php
// Calculates the grams of pure alcohol in a drink.
function gramsOfAlcohol($volumeMl, $abvPercent, $alcoholDensity = 0.789)
{
    return $volumeMl * ($abvPercent / 100) * $alcoholDensity;
}

// Widmark formula: BAC (g/L) = (A / (weight_g * r)) - (beta * hours)
function bacWidmark($gramsAlcohol, $weightKg, $rFactor, $hoursElapsed, $eliminationRate = 0.15)
{
    $weightGrams = $weightKg * 1000;

    $initialBac = $gramsAlcohol / ($weightGrams * $rFactor) * 1000;
    $bac = $initialBac - ($eliminationRate * $hoursElapsed);

    return max(0.0, $bac);
}

// Watson formula for Total Body Water (TBW), an alternative to Widmark's fixed r factor
function watsonTBW($weightKg, $heightCm, $age, $sex)
{
    $sex = strtoupper($sex);

    if ($sex === 'M') {
        return 2.447 - (0.09156 * $age) + (0.1074 * $heightCm) + (0.3362 * $weightKg);
    }
    if ($sex === 'F') {
        return -2.097 + (0.1069 * $heightCm) + (0.2466 * $weightKg);
    }
    return -1; // -1 ON ERROR
}

// Calculates BAC using Watson's TBW instead of Widmark's fixed r factor
function bacWatson($gramsAlcohol, $weightKg, $heightCm, $age, $sex, $hoursElapsed, $eliminationRate = 0.15)
{
    $tbwLiters = watsonTBW($weightKg, $heightCm, $age, $sex);

    $initialBac = $gramsAlcohol / $tbwLiters;
    $bac = $initialBac - ($eliminationRate * $hoursElapsed);

    return max(0.0, $bac);
}


// Generates {time, bac} points for the chart, sampling every $intervalMin minutes.
// $drinks must come from $db->getDrinkDiSerata(), already ordered by time.
function chartPointsSerata($drinks, $weightKg, $heightCm, $age, $sex, $intervalMin = 10)
{
    if (empty($drinks)) {
        return [];
    }

    $start = strtotime($drinks[0]['orario']);
    $end = strtotime(end($drinks)['orario']) + 6 * 3600; // margin so bac can drop to 0

    $points = [];
    for ($t = $start; $t <= $end; $t += $intervalMin * 60) {
        $totalGrams = 0;
        foreach ($drinks as $d) {
            $drinkTime = strtotime($d['orario']);
            if ($drinkTime <= $t) {
                $ml = $d['volume_standard'] * $d['volume'];
                $totalGrams += gramsOfAlcohol($ml, $d['gradazione']);
            }
        }

        $hoursElapsed = ($t - $start) / 3600;
        $bac = bacWatson($totalGrams, $weightKg, $heightCm, $age, $sex, $hoursElapsed);

        $points[] = ['time' => date('H:i', $t), 'bac' => round($bac, 3)];

        if ($bac <= 0 && $totalGrams > 0 && $t > $start) {
            break; // session is "over", no need to keep sampling
        }
    }

    return $points;
}

// Calculates the current BAC of an open session; closes it in the DB if it has dropped to 0.
// Returns the current BAC (0 if the session was just closed).
function currentSerataStatus($db, $serata, $user)
{
    $age = (new DateTime($user['data_nascita']))->diff(new DateTime())->y;
    $drinks = $db->getDrinkDiSerata($serata['idserata']);

    $totalGrams = 0;
    foreach ($drinks as $d) {
        $ml = $d['volume_standard'] * $d['volume'];
        $totalGrams += gramsOfAlcohol($ml, $d['gradazione']);
    }

    $hoursElapsed = (time() - strtotime($serata['datainizio'])) / 3600;
    $bac = bacWatson($totalGrams, $user['peso'], $user['altezza'], $age, $user['sesso'], $hoursElapsed);

    if ($bac <= 0) {
        $db->chiudiSerata($serata['idserata'], date('Y-m-d H:i:s'));
    }

    return $bac;
}
?>