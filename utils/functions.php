<?php
function isActive($pagename){
    if(basename($_SERVER['PHP_SELF'])==$pagename){
        echo "active";
    }
}

function isUserLoggedIn(){
    return !empty($_SESSION['idutente']);
}

function isAdmin(){
    return isUserLoggedIn() && $_SESSION['is_admin'] == 1;
}

function registerLoggedUser($user){
    $_SESSION["idutente"] = $user["idutente"];
    $_SESSION["nome"] = $user["nome"];
    $_SESSION["cognome"] = $user["cognome"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["is_admin"] = $user["is_admin"];
}
?>
