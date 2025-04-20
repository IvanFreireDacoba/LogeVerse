<?php

include_once "../classes/include_classes.php";
include_once "./functions.module.php";

session_start();

//Control de acceso solo a usuarios con la sesion iniciada
if (!isset($_SESSION["usuario"])) {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: ../controllers/index.controller.php");
    exit;
}

if ($_POST) {
    $datos = [];
    foreach ($_POST as $key => $value) {
        $datos = [$$key => $value];
    }

    $types = [
        "clase",
        "raza"
    ];

    if ($proposal_type && in_array($proposal_type, $types)) {
        switch ($proposal_type) {
            case "raza": {
                try {
                    $conexion = conectar();
                    propuestaRaza($conexion, $datos);
                } catch (error) {

                }
                break;
            }
            case "clase": {
                try {
                    $conexion = conectar();
                    propuestaClase($conexion, $datos);
                } catch (error) {

                }
                break;
            }
            default: {
                $_SESSION["alert"] = "No se ha podido realizar la propuesta.";
                header("Location: ../controllers/propuestas.controller.php");
                exit;
            }
        }
    } else {
        $_SESSION["alert"] = "No se ha podido realizar la propuesta.";
        header("Location: ../controllers/propuestas.controller.php");
        exit;
    }
} else {
    $_SESSION["alert"] = "Error al obtener los datos del formulario.";
    header("Location: ../controllers/propuestas.controller.php");
    exit;
}