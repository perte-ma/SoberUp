<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/bac.php';

if (!isUserLoggedIn()) {
    http_response_code(401);
    exit;
}

$serataId = $_GET['idserata'];

$serata = $dbh->getSerataById($serataId);

if ($serata === null || $serata['utente'] != $_SESSION['idutente']) {
    http_response_code(403);
    exit;
}

$user = $dbh->getUtenteById($serata['utente']);

$age = (new DateTime($user['data_nascita']))->diff(new DateTime())->y;

$drinks = $dbh->getDrinkDiSerata($serataId);
$points = chartPointsSerata($drinks, $user['peso'], $user['altezza'], $age, $user['sesso']);

// Se l'orario stimato di fine e' gia' passato e la serata risulta ancora aperta,
// la chiudiamo qui registrando quell'orario stimato (non il momento del controllo)
$oraFineStimata = oraFineStimataSerata($drinks, $serata['datainizio'], $user['peso'], $user['altezza'], $age, $user['sesso']);
if ($serata['datafine'] === null && time() >= $oraFineStimata) {
    $dbh->chiudiSerata($serataId, date('Y-m-d H:i:s', $oraFineStimata));
}

header('Content-Type: application/json');
echo json_encode($points);
