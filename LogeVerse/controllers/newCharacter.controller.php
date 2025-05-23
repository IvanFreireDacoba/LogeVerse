<?php

//Control de acceso desde perfil
if (!isset($_SESSION["usuario"])) {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: LogeVerse/inicio");
    exit;
}

if ($_POST) {
    $_SESSION["newChar"] = [];
    foreach($_POST as $key => $value){
        $_SESSION["newChar"][$key] = $value;
    }
} else if ($_SESSION["POST"]) {
    $_SESSION["newChar"] = [];
    foreach($_SESSION["POST"] as $key => $value){
        $_SESSION["newChar"][$key] = $value;
    }
    unset($_SESSION["POST"]);
}

$title = 'Creador PJs';
require "LogeVerse/views/newCharacter/newCharacter.php";