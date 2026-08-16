<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET["iddrink"])) {
    header("Location: catalogo-drink.php");
    exit;
}

$iddrink = $_GET["iddrink"];
$drink = $dbh->getDrinkById($iddrink);

if (!$drink) {
    header("Location: catalogo-drink.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $volume = $_POST["volume"];
    $orario = $_POST["orario"];

    $adesso = new DateTime();
    $orarioDrink = clone $adesso;
    list($h, $m) = explode(':', $orario);
    $orarioDrink->setTime((int)$h, (int)$m, 0);

    if ($orarioDrink > $adesso) {
        $orarioDrink->modify('-1 day');
    }

    $serataAperta = $dbh->getSerataAperta($_SESSION["idutente"]);
    if ($serataAperta) {
        $idserata = $serataAperta["idserata"];
    } else {
        $idserata = $dbh->insertSerata($_SESSION["idutente"], $orarioDrink->format('Y-m-d H:i:s'));
    }

    $dbh->insertDrinkInSerata($idserata, $iddrink, $orarioDrink->format('Y-m-d H:i:s'), $volume);

    $templateParams["messaggio"] = "Drink aggiunto alla tua serata!";
}

$templateParams["titolo"] = "SoberUp - " . $drink["nomedrink"];
$templateParams["intestazione"] = $drink["nomedrink"];
$templateParams["nome"] = "template/dettaglio-drink.php";
$templateParams["drink"] = $drink;

require 'template/base.php';
?>
