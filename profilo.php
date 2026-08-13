<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome = trim($_POST["nome"]);
    $cognome = trim($_POST["cognome"]);
    $sesso = trim($_POST["sesso"]);
    $data_nascita = trim($_POST["data_nascita"]);
    $peso = $_POST["peso"];
    $altezza = $_POST["altezza"];

    $errori = [];
    $obbligatori = ["nome" => "Nome", "cognome" => "Cognome", "data_nascita" => "Data di nascita"];
    foreach ($obbligatori as $campo => $etichetta) {
        if (empty($_POST[$campo])) {
            $errori[] = "$etichetta è un campo obbligatorio.";
        }
    }

    if (count($errori) == 0) {
        $dbh->updateUtente($_SESSION["idutente"],$nome, $cognome, $sesso, $data_nascita, $peso, $altezza);

        header("Location: profilo.php?salvato=ok");
        exit;

    }
}

$utente = $dbh->getUtenteById($_SESSION["idutente"]);

$templateParams["errori"] = $errori ?? [];

$templateParams["titolo"] = "SoberUp - Profilo";
$templateParams["intestazione"] = "Profilo";
$templateParams["nome"] = "template/profilo.php";
$templateParams["utente"] = $utente;

if (isset($_GET["salvato"]) && $_GET["salvato"] == "ok") {
    $templateParams["messaggio"] = "Dati salvati";
}

require 'template/base.php';
?>