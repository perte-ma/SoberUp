<?php
require_once 'bootstrap.php';

if (isUserLoggedIn()) {
    header("Location: index.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $usernameOrEmail = trim($_POST["username"]);
    $password = $_POST["password"];

    $utente = $dbh->getUtenteByUsernameOrEmail($usernameOrEmail);

    if ($utente != null && password_verify($password, $utente["password"])) {
        registerLoggedUser($utente);
        header("Location: index.php");
        exit;
    } else {
        $errori[] = "Credenziali non valide.";
    }
}

if (isset($_GET["registrazione"]) && $_GET["registrazione"] == "ok") {
    $templateParams["messaggio"] = "Registrazione completata, effettua il login.";
}

$templateParams["errori"] = $errori ?? [];

$templateParams["titolo"] = "SoberUp - Login";
$templateParams["intestazione"] = "Accedi";
$templateParams["nome"] = "template/login.php";

require 'template/base.php';
?>