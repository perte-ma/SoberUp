<?php
require_once 'bootstrap.php';

$drinks = $dbh->getDrinks();

$templateParams["titolo"] = "SoberUp - Calcolo Rapido";
$templateParams["intestazione"] = "Calcolo Rapido";
$templateParams["nome"] = "template/calcolo-rapido.php";
$templateParams["drinks"] = $drinks;

require 'template/base.php';
?>
