<?php
require_once 'bootstrap.php';
require_once 'utils/bac.php';

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit;
}

$serataAperta = $dbh->getSerataAperta($_SESSION['idutente']);
$bac = null;

if ($serataAperta) {
    $utente = $dbh->getUtenteById($_SESSION["idutente"]);
    $bac = currentSerataStatus($dbh, $serataAperta, $utente);
}

$templateParams["titolo"] = "SoberUp - Home";
$templateParams["intestazione"] = "Dashboard";
$templateParams["nome"] = "template/home.php";
$templateParams["idserata"] = $serataAperta ? $serataAperta['idserata'] : null;
$templateParams["bac"] = $bac;

require 'template/base.php';
?>