<?php
require_once 'bootstrap.php';
require_once 'utils/bac.php';

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit;
}

$errori = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Invio di una nuova richiesta di amicizia tramite codice amico
    if (isset($_POST["invia"])) {
        $codice = strtoupper(trim($_POST["codice_amico"]));
        $trovato = $codice ? $dbh->getUtenteByCodiceAmico($codice) : null;

        if (empty($codice)) {
            $errori[] = "Inserisci un codice amico.";
        } elseif (!$trovato) {
            $errori[] = "Nessun utente trovato con questo codice amico.";
        } elseif ($trovato["idutente"] == $_SESSION["idutente"]) {
            $errori[] = "Non puoi aggiungere te stesso come amico.";
        } elseif ($dbh->getAmiciziaTra($_SESSION["idutente"], $trovato["idutente"])) {
            $errori[] = "Esiste già una richiesta o un'amicizia con questo utente.";
        }

        if (count($errori) == 0) {
            $dbh->inviaRichiestaAmicizia($_SESSION["idutente"], $trovato["idutente"]);
            header("Location: amici.php?azione=inviata");
            exit;
        }
    }

    if (isset($_POST["accetta"])) {
        $dbh->accettaRichiestaAmicizia($_POST["idamicizia"]);
        header("Location: amici.php?azione=accettata");
        exit;
    }

    if (isset($_POST["rifiuta"])) {
        $dbh->rimuoviAmicizia($_POST["idamicizia"]);
        header("Location: amici.php?azione=rifiutata");
        exit;
    }

    if (isset($_POST["rimuovi"])) {
        $dbh->rimuoviAmicizia($_POST["idamicizia"]);
        header("Location: amici.php?azione=rimosso");
        exit;
    }
}

$utente = $dbh->getUtenteById($_SESSION["idutente"]);
$richieste = $dbh->getRichiesteInAttesa($_SESSION["idutente"]);
$amici = $dbh->getAmiciAccettati($_SESSION["idutente"]);

foreach ($amici as &$amico) {
    $amico["bac"] = null;

    if ($amico["idserata"]) {
        $serata = $dbh->getSerataById($amico["idserata"]);
        $amico["bac"] = currentSerataStatus($dbh, $serata, $amico);
    }
}
unset($amico);

$templateParams["titolo"] = "SoberUp - Amici";
$templateParams["intestazione"] = "Amici";
$templateParams["nome"] = "template/amici.php";
$templateParams["errori"] = $errori;
$templateParams["utente"] = $utente;
$templateParams["richieste"] = $richieste;
$templateParams["amici"] = $amici;

$messaggi = [
    "inviata" => "Richiesta di amicizia inviata.",
    "accettata" => "Richiesta di amicizia accettata.",
    "rifiutata" => "Richiesta rifiutata.",
    "rimosso" => "Amico rimosso.",
];
if (isset($_GET["azione"]) && isset($messaggi[$_GET["azione"]])) {
    $templateParams["messaggio"] = $messaggi[$_GET["azione"]];
}

require 'template/base.php';
?>