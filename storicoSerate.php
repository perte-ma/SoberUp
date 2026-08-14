<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit;
}

$idutente = $_SESSION['idutente'];
$serateChiuse = $dbh->getSerateChiuseByUtente($idutente);

// Per ogni serata chiusa recupero anche i drink consumati, serviranno per il dettaglio
foreach ($serateChiuse as &$serata) {
    $serata['drinks'] = $dbh->getDrinkDiSerata($serata['idserata']);
}
unset($serata);

$templateParams["titolo"] = "SoberUp - Storico Serate";
$templateParams["intestazione"] = "Storico Serate";
$templateParams["nome"] = "template/storicoSerate.php";
$templateParams["serate"] = $serateChiuse;

require 'template/base.php';
?>