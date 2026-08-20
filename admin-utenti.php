<?php
require_once 'bootstrap.php';

if (!isAdmin()) {
    header("Location: index.php");
    exit;
}

$errori = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["elimina"])) {
        if ($_POST["idutente"] == $_SESSION["idutente"]) {
            header("Location: admin-utenti.php?azione=errore_se_stesso");
            exit;
        }
        try {
            $dbh->deleteUtente($_POST["idutente"]);
            header("Location: admin-utenti.php?azione=eliminato");
        } catch (mysqli_sql_exception $e) {
            header("Location: admin-utenti.php?azione=non_eliminabile");
        }
        exit;
    }
    if (isset($_POST["disattiva"])) {
        $dbh->setUtenteAttivo($_POST["idutente"], 0);
        header("Location: admin-utenti.php?azione=disattivato");
        exit;
    }
    if (isset($_POST["attiva"])) {
        $dbh->setUtenteAttivo($_POST["idutente"], 1);
        header("Location: admin-utenti.php?azione=riattivato");
        exit;
    }
}

$templateParams["titolo"] = "SoberUp - Admin Utenti";
$templateParams["intestazione"] = "Gestione Utenti";
$templateParams["nome"] = "template/admin-utenti.php";
$templateParams["utenti"] = $dbh->getAllUtenti();
$templateParams["errori"] = $errori;

$messaggi = [
    "eliminato" => "Utente eliminato.",
    "disattivato" => "Utente disattivato.",
    "riattivato" => "Utente riattivato.",
];
if (isset($_GET["azione"]) && isset($messaggi[$_GET["azione"]])) {
    $templateParams["messaggio"] = $messaggi[$_GET["azione"]];
}
if (isset($_GET["azione"]) && $_GET["azione"] == "non_eliminabile") {
    $templateParams["errori"][] = "Impossibile eliminare: questo utente ha scritto degli articoli.";
}
if (isset($_GET["azione"]) && $_GET["azione"] == "errore_se_stesso") {
    $templateParams["errori"][] = "Non puoi eliminare il tuo stesso account da qui.";
}

require 'template/base.php';
?>
