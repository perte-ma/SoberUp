<?php
require_once 'bootstrap.php';
require_once 'utils/bac.php';

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit;
}

$idutente = $_SESSION['idutente'];
$serateChiuse = $dbh->getSerateChiuseByUtente($idutente);
$user = $dbh->getUtenteById($idutente);
$age = (new DateTime($user["data_nascita"]))->diff(new DateTime())->y;

// Per ogni serata chiusa recupero anche i drink consumati, serviranno per il dettaglio
// e per calcolare il picco di BAC raggiunto (riusando i punti del grafico già pronti)
$bacMassimo = 0;
foreach ($serateChiuse as &$serata) {
    $serata['drinks'] = $dbh->getDrinkDiSerata($serata['idserata']);

    if (!empty($serata['drinks'])) {
        $punti = chartPointsSerata($serata['drinks'], $user['peso'], $user['altezza'], $age, $user['sesso']);
        $bacMassimo = max($bacMassimo, max(array_column($punti, 'bac')));
    }
}
unset($serata);

$templateParams["titolo"] = "SoberUp - Storico Serate";
$templateParams["intestazione"] = "Storico Serate";
$templateParams["nome"] = "template/storicoSerate.php";
$templateParams["serate"] = $serateChiuse;
$templateParams["drinkPreferito"] = $dbh->getDrinkPreferitoUtente($idutente);
$templateParams["millilitriTotali"] = $dbh->getMillilitriTotaliUtente($idutente);
$templateParams["bacMassimo"] = $bacMassimo;

require 'template/base.php';
?>