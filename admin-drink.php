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
            $dbh->deleteDrink($_POST["iddrink"]);
            header("Location: admin-drink.php?azione=eliminato");
        } catch (mysqli_sql_exception $e) {
            header("Location: admin-drink.php?azione=non_eliminabile");
        }
        exit;
    }

    $nomedrink = trim($_POST["nomedrink"]);
    $categoria = $_POST["categoria"];
    $gradazione = $_POST["gradazione"];
    $volumeStandard = $_POST["volume_standard"];
    $descrizione = trim($_POST["descrizione"]);

    if (empty($nomedrink)) {
        $errori[] = "Il nome del drink è obbligatorio.";
    }

    // Se non viene caricato un file nuovo, resta quella attuale (arriva da un campo nascosto nel form)
    $immagine = $_POST["immagine_attuale"] ?? null;
    if (isset($_FILES["immagine"]) && $_FILES["immagine"]["error"] == 0 && $_FILES["immagine"]["size"] > 0) {
        $immagine = $_FILES["immagine"]["name"];
        move_uploaded_file($_FILES["immagine"]["tmp_name"], UPLOAD_DIR . $immagine);
    }

    if (count($errori) == 0) {
        if (isset($_POST["modifica"])) {
            $dbh->updateDrink($_POST["iddrink"], $nomedrink, $categoria, $gradazione, $volumeStandard, $immagine, $descrizione);
            header("Location: admin-drink.php?azione=modificato");
        } else {
            $dbh->insertDrink($nomedrink, $categoria, $gradazione, $volumeStandard, $immagine, $descrizione);
            header("Location: admin-drink.php?azione=aggiunto");
        }
        exit;
    }
}

$drinkInModifica = null;
if (isset($_GET["modifica"])) {
    $drinkInModifica = $dbh->getDrinkById($_GET["modifica"]);
}

$templateParams["titolo"] = "SoberUp - Admin Drink";
$templateParams["intestazione"] = "Gestione Drink";
$templateParams["nome"] = "template/admin-drink.php";
$templateParams["errori"] = $errori;
$templateParams["drinks"] = $dbh->getDrinks();
$templateParams["categorie"] = $dbh->getCategorie();
$templateParams["drinkInModifica"] = $drinkInModifica;

$messaggi = [
    "aggiunto" => "Drink aggiunto.",
    "modificato" => "Drink modificato.",
    "eliminato" => "Drink eliminato.",
];
if (isset($_GET["azione"]) && isset($messaggi[$_GET["azione"]])) {
    $templateParams["messaggio"] = $messaggi[$_GET["azione"]];
}
if (isset($_GET["azione"]) && $_GET["azione"] == "non_eliminabile") {
    $templateParams["errori"][] = "Impossibile eliminare: questo drink è già stato registrato in almeno una serata.";
}

require 'template/base.php';
?>
