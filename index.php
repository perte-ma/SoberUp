<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit;
}

$serataAperta = $dbh->getSerataAperta($_SESSION['idutente']);

$templateParams["titolo"] = "SoberUp - Home";
$templateParams["intestazione"] = "Dashboard";
$templateParams["nome"] = "template/home.php";
$templateParams["idserata"] = $serataAperta ? $serataAperta['idserata'] : null;

require 'template/base.php';
?>