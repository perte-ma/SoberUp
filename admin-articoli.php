<?php
require_once 'bootstrap.php';

if (!isAdmin()) {
    header("Location: index.php");
    exit;
}

$errori = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["elimina"])) {
        $dbh->deleteArticolo($_POST["idarticolo"]);
        header("Location: admin-articoli.php?azione=eliminato");
        exit;
    }

    $titoloarticolo = trim($_POST["titoloarticolo"]);
    $testoarticolo = trim($_POST["testoarticolo"]);

    if (empty($titoloarticolo)) {
        $errori[] = "Il titolo è obbligatorio.";
    }
    if (empty($testoarticolo)) {
        $errori[] = "Il testo è obbligatorio.";
    }

    if (count($errori) == 0) {
        if (isset($_POST["modifica"])) {
            $dbh->updateArticolo($_POST["idarticolo"], $titoloarticolo, $testoarticolo);
            header("Location: admin-articoli.php?azione=modificato");
        } else {
            $dbh->insertArticolo($titoloarticolo, $testoarticolo, date("Y-m-d"), $_SESSION["idutente"]);
            header("Location: admin-articoli.php?azione=aggiunto");
        }
        exit;
    }
}

$articoloInModifica = null;
if (isset($_GET["modifica"])) {
    $articoloInModifica = $dbh->getArticoloById($_GET["modifica"]);
}

$templateParams["titolo"] = "SoberUp - Admin Contenuti";
$templateParams["intestazione"] = "Gestione Contenuti";
$templateParams["nome"] = "template/admin-articoli.php";
$templateParams["errori"] = $errori;
$templateParams["articoli"] = $dbh->getArticoli();
$templateParams["articoloInModifica"] = $articoloInModifica;

$messaggi = [
    "aggiunto" => "Articolo aggiunto.",
    "modificato" => "Articolo modificato.",
    "eliminato" => "Articolo eliminato.",
];
if (isset($_GET["azione"]) && isset($messaggi[$_GET["azione"]])) {
    $templateParams["messaggio"] = $messaggi[$_GET["azione"]];
}

require 'template/base.php';
?>
