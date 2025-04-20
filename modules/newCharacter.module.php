<?php

//Control de acceso solo a usuarios con la sesion iniciada
if (!isset($_SESSION["usuario"])) {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: ../controllers/index.controller.php");
    exit;
}

if($_POST){
    foreach($_POST as $key => $value){
        $$key = $value;
    }



} else {
    $_SESSION["alert"] = "Error al obtener los datos del formulario.";
    header("Location: ../controllers/newCharacter.controller.php");
    exit;
}