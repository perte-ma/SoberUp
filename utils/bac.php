<?php

// Calcola i grammi di alcol puro contenuti in una bevanda.
function grammiAlcol($volumeMl, $gradazionePerc, $densitaAlcol = 0.789)
{
    return $volumeMl * ($gradazionePerc / 100) * $densitaAlcol;
}

// Formula di Widmark: BAC (g/L) = (A / (peso_g * r)) - (beta * ore)
function bacWidmark($grammiAlcol, $pesoKg, $fattoreR, $oreTrascorse, $tassoEliminazione = 0.15)
{
    $pesoGrammi = $pesoKg * 1000;

    $bacIniziale = $grammiAlcol / ($pesoGrammi * $fattoreR) * 1000;
    $bac = $bacIniziale - ($tassoEliminazione * $oreTrascorse);

    return max(0.0, $bac);
}

// Formula di Watson per l'Acqua Corporea Totale (TBW), alternativa al fattore r fisso di Widmark
function tbwWatson($pesoKg, $altezzaCm, $eta, $sesso)
{
    $sesso = strtoupper($sesso);

    if ($sesso === 'M') {
        return 2.447 - (0.09156 * $eta) + (0.1074 * $altezzaCm) + (0.3362 * $pesoKg);
    }
    if ($sesso === 'F') {
        return -2.097 + (0.1069 * $altezzaCm) + (0.2466 * $pesoKg);
    }
    return -1; // -1 IN CASO DI ERRORE
}

// Calcola il BAC usando il TBW di Watson al posto del fattore r di Widmark
function bacWatson($grammiAlcol, $pesoKg, $altezzaCm, $eta, $sesso, $oreTrascorse, $tassoEliminazione = 0.15)
{
    $tbwLitri = tbwWatson($pesoKg, $altezzaCm, $eta, $sesso);

    $bacIniziale = $grammiAlcol / $tbwLitri;
    $bac = $bacIniziale - ($tassoEliminazione * $oreTrascorse);

    return max(0.0, $bac);
}