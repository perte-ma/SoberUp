<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit;
}

$categorie = $dbh->getCategorie();

foreach ($categorie as &$c) {
    $c['drinks'] = $dbh->getDrinksByCategoria($c['idcategoria']);
}
unset($c);

$templateParams["titolo"] = "SoberUp - Catalogo Drink";
$templateParams["intestazione"] = "Catalogo Drink";
$templateParams["nome"] = "template/catalogo-drink.php";
$templateParams["categorie"] = $categorie;

require 'template/base.php';
?>