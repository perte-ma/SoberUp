<?php
require_once 'bootstrap.php';

if (!isAdmin()) {
    header("Location: index.php");
    exit;
}

$errori = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["elimina"])) {
        try {
            $dbh->deleteCategoria($_POST["idcategoria"]);
            header("Location: admin-categorie.php?azione=eliminata");
        } catch (mysqli_sql_exception $e) {
            header("Location: admin-categorie.php?azione=non_eliminabile");
        }
        exit;
    }

    $nomecategoria = trim($_POST["nomecategoria"]);

    if (empty($nomecategoria)) {
        $errori[] = "Il nome della categoria è obbligatorio.";
    }

    if (count($errori) == 0) {
        if (isset($_POST["modifica"])) {
            $dbh->updateCategoria($_POST["idcategoria"], $nomecategoria);
            header("Location: admin-categorie.php?azione=modificata");
        } else {
            $dbh->insertCategoria($nomecategoria);
            header("Location: admin-categorie.php?azione=aggiunta");
        }
        exit;
    }
}

$templateParams["titolo"] = "SoberUp - Admin Categorie";
$templateParams["intestazione"] = "Gestione Categorie";
$templateParams["nome"] = "template/admin-categorie.php";
$templateParams["errori"] = $errori;
$templateParams["categorie"] = $dbh->getCategorie();

$messaggi = [
    "aggiunta" => "Categoria aggiunta.",
    "modificata" => "Categoria modificata.",
    "eliminata" => "Categoria eliminata.",
];
if (isset($_GET["azione"]) && isset($messaggi[$_GET["azione"]])) {
    $templateParams["messaggio"] = $messaggi[$_GET["azione"]];
}
if (isset($_GET["azione"]) && $_GET["azione"] == "non_eliminabile") {
    $templateParams["errori"][] = "Impossibile eliminare: questa categoria ha ancora dei drink assegnati.";
}

require 'template/base.php';
?>
