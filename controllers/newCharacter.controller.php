<?php

include_once "../modules/functions.module.php";

session_start();

//Control de acceso desde perfil
if (!isset($_SESSION["usuario"])) {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: ../controllers/index.controller.php");
    exit;
}

if ($_POST) {
    $_SESSION["newChar"] = [];
    foreach($_POST as $key => $value){
        $_SESSION["newChar"][$key] = $value;
    }
}

$title = 'Creador PJs';
require "../views/newCharacter/newCharacter.php";