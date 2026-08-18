<?php
require_once 'bootstrap.php';
require_once 'utils/bac.php';

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit;
}

$idutente = $_SESSION['idutente'];
$serataAperta = $dbh->getSerataAperta($idutente);
$errori = [];

if ($serataAperta && $_SERVER["REQUEST_METHOD"] == "POST") {
    $idseratadrink = $_POST["idseratadrink"] ?? null;
    $drinkValido = null;
    foreach ($dbh->getDrinkDiSerata($serataAperta["idserata"]) as $d) {
        if ($d["idseratadrink"] == $idseratadrink) {
            $drinkValido = $d;
            break;
        }
    }
    if ($drinkValido) {
        if (isset($_POST["elimina"])) {
            $dbh->deleteDrinkInSerata($idseratadrink);
            header("Location: serata.php?azione=eliminato");
            exit;
        }
        if (isset($_POST["modifica"])) {
            $volume = $_POST["volume"];
            $orario = trim($_POST["orario"]);

            if (empty($orario)) {
                $errori[] = "L'orario è obbligatorio.";
            }
            if ($volume == "" || $volume <= 0) {
                $errori[] = "Il volume deve essere maggiore di 0 ml.";
            }
            if (count($errori) == 0) {
                $adesso = new DateTime();
                $orarioDrink = clone $adesso;
                list($h, $m) = explode(':', $orario);
                $orarioDrink->setTime((int)$h, (int)$m, 0);
                if ($orarioDrink > $adesso) {
                    $orarioDrink->modify('-1 day');
                }
                $dbh->updateDrinkInSerata($idseratadrink, $orarioDrink->format('Y-m-d H:i:s'), $volume);
                header("Location: serata.php?azione=modificato");
                exit;
            }
        }
    }
}

$templateParams["titolo"] = "SoberUp - Serata";
$templateParams["intestazione"] = "Serata in corso";
$templateParams["nome"] = "template/serata.php";
$templateParams["errori"] = $errori;

if (isset($_GET["azione"]) && $_GET["azione"] == "eliminato") {
    $templateParams["messaggio"] = "Drink eliminato dalla serata.";
} elseif (isset($_GET["azione"]) && $_GET["azione"] == "modificato") {
    $templateParams["messaggio"] = "Drink modificato.";
}

if ($serataAperta) {
    $user = $dbh->getUtenteById($idutente);
    $drinks = $dbh->getDrinkDiSerata($serataAperta["idserata"]);
    $bacAttuale = currentSerataStatus($dbh, $serataAperta, $user);
    $serataAperta = $dbh->getSerataById($serataAperta["idserata"]);

    if ($serataAperta["datafine"] !== null) {
        $serataAperta = null;
    } else {
        $age = (new DateTime($user["data_nascita"]))->diff(new DateTime())->y;
        $oraFineStimata = oraFineStimataSerata($drinks, $serataAperta["datainizio"], $user["peso"], $user["altezza"], $age, $user["sesso"]);

        $templateParams["serata"] = $serataAperta;
        $templateParams["drinks"] = $drinks;
        $templateParams["bac"] = $bacAttuale;
        $templateParams["oraFineStimata"] = $oraFineStimata;
        $templateParams["puoGuidare"] = $bacAttuale <= LIMITE_LEGALE_BAC;
    }
}

$templateParams["haSerataAperta"] = $serataAperta !== null;

require 'template/base.php';
?>
