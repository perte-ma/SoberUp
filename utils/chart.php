<?php
require_once 'DatabaseHelper.php';
require_once 'functions.php';

$db = new DatabaseHelper($servername, $username, $password, $dbname, $port);

$serataId = $_GET['idserata'];

$serata = $db->getSerataById($serataId);
$user = $db->getUtenteById($serata['utente']);

$age = (new DateTime($user['data_nascita']))->diff(new DateTime())->y;

$drinks = $db->getDrinkDiSerata($serataId);
$points = chartPointsSerata($drinks, $user['peso'], $user['altezza'], $age, $user['sesso']);

// If the calculated bac has reached 0 and the session is still marked open, close it here
if (!empty($points) && end($points)['bac'] <= 0 && $serata['datafine'] === null) {
    $db->chiudiSerata($serataId, end($drinks)['orario']);
}

header('Content-Type: application/json');
echo json_encode($points);