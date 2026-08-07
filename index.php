<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit;
}

$templateParams["titolo"] = "SoberUp - Home";
$templateParams["intestazione"] = "Dashboard";
$templateParams["nome"] = "template/home.php";

require 'template/base.php';
?>
