<?php
require_once 'bootstrap.php';

if (isUserLoggedIn()) {
    header("Location: index.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome = trim($_POST["nome"]);
    $cognome = trim($_POST["cognome"]);
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confermaPassword = $_POST["conferma_password"];
    $sesso = trim($_POST["sesso"]);
    $data_nascita = trim($_POST["data_nascita"]);
    $peso = $_POST["peso"];
    $altezza = $_POST["altezza"];

    $errori = [];
    $obbligatori = ["nome" => "Nome", "cognome" => "Cognome", "username" => "Username", "data_nascita" => "Data di nascita"];
    foreach ($obbligatori as $campo => $etichetta) {
        if (empty($_POST[$campo])) {
            $errori[] = "$etichetta è un campo obbligatorio.";
        }
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errori[] = "Email non valida.";
    }
    if (strlen($password) < 8) {
        $errori[] = "La password deve essere lunga almeno 8 caratteri.";
    }
    if ($password != $confermaPassword) {
        $errori[] = "Le password non coincidono.";
    }

    if ($dbh->getUtenteByUsernameOrEmail($username)) {
        $errori[] = "Username già in uso.";
    }
    if ($dbh->getUtenteByUsernameOrEmail($email)) {
        $errori[] = "Email già in uso.";
    }

    if (count($errori) == 0) {
        do {
            $codice = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6));
        } while ($dbh->esisteCodiceAmico($codice));

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $dbh->insertUtente($nome, $cognome, $email, $username, $passwordHash, $codice, $sesso, $data_nascita, $peso, $altezza);

        header("Location: login.php?registrazione=ok");
        exit;

    }
}

$templateParams["errori"] = $errori ?? [];

$templateParams["titolo"] = "SoberUp - Registrazione";
$templateParams["intestazione"] = "Registrati";
$templateParams["nome"] = "template/registrazione.php";

require 'template/base.php';
?>