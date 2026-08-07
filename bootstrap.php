<?php
session_start();
define("UPLOAD_DIR", "./upload/");
define("LIMITE_LEGALE_BAC", 0.5); // g/L, limite legale in Italia

require_once("utils/functions.php");
require_once("db/database.php");

$dbh = new DatabaseHelper("localhost", "root", "", "soberup_db", 3306);
?>
