<?php
    
    $title = "Propuestas";

    include_once '../classes/include_classes.php';
    include_once "../modules/functions.module.php";

    session_start();

    if(isset($_SESSION["usuario"])){
        if(isset($_SESSION["POST"])){
            foreach ($_SESSION["POST"] as $key => $value) {
                $$key = $value;
            }
            unset($_SESSION["POST"]);
        }
        require '../views/propuestas/propuestas.php';
    }else{
        $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
        header("Location: ../controllers/index.controller.php");
        exit;
    }