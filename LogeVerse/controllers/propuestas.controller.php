<?php
    
    $title = "Propuestas";

    if(isset($_SESSION["usuario"])){
        if(isset($_SESSION["POST"])){
            foreach ($_SESSION["POST"] as $key => $value) {
                $$key = $value;
            }
            unset($_SESSION["POST"]);
        }
        require 'LogeVerse/views/propuestas/propuestas.php';
    }else{
        $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
        header("Location: LogeVerse/inicio");
        exit;
    }