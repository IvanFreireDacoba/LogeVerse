<?php

include_once "LogeVerse/classes/include_classes.php";
include_once "LogeVerse/modules/functions.module.php";

session_start();

//Control de acceso solo a usuarios con la sesion iniciada
if (!isset($_SESSION["usuario"])) {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: /LogeVerse/inicio");
    exit;
}

if (isset($_POST)) {
    $_SESSION["POST"] = $_POST;
    $datos = [];
    foreach ($_POST as $key => $value) {
        $datos[$key] = htmlspecialchars($value);
    }

    $types = [
        "clase",
        "raza",
        "efecto",
        "habilidad",
        "pasiva",
        "objeto",
        "idioma"
    ];

    if ($datos["proposal_type"] && in_array($datos["proposal_type"], $types)) {
        $exito = false;
        $infoMsg = "";
        try {
            $conexion = conectar();
            switch ($datos["proposal_type"]) {
                case "raza": {
                    $idProp = propuestaRaza($conexion, $datos, $infoMsg, $exito);
                    break;
                }
                case "clase": {
                    $idProp = propuestaClase($conexion, $datos, $infoMsg, $exito);
                    break;
                }
                case "efecto": {
                    $idProp = propuestaEfecto($conexion, $datos, $infoMsg, $exito);
                    break;
                }
                case "habilidad": {
                    $idProp = propuestaHabilidad($conexion, $datos, $infoMsg, $exito);
                    break;
                }
                case "pasiva": {
                    $idProp = propuestaPasiva($conexion, $datos, $infoMsg, $exito);
                    break;
                }
                case "objeto": {
                    $idProp = propuestaObjeto($conexion, $datos, $infoMsg, $exito);
                    break;
                }
                case "idioma": {
                    $idProp = propuestaIdioma($conexion, $datos, $infoMsg, $exito);
                    break;
                }
                default: {
                    $conexion = null;
                    break;
                }
            }
            if ($exito) {
                try {
                    registrarPropuesta($conexion, $_SESSION["usuario"]->getId(), $idProp, $datos["proposal_type"]);
                    $_SESSION["alert"] = "Propuesta realizada con éxito.";
                } catch (Error $e) {
                    $_SESSION["alert"] = "Propuesta realizada con éxito, no se ha podido registrar al usuario.";
                }
            } else if ($infoMsg !== null) {
                $_SESSION["alert"] = $infoMsg;
            }
        } catch (error) {
            $_SESSION["alert"] = "No se ha podido realizar la propuesta: " . $infoMsg;
        }
        $conexion = null;
    } else {
        $_SESSION["alert"] = "La entidad propuesta no existe.";
    }
} else {
    $_SESSION["alert"] = "Error al obtener los datos del formulario.";
}
header("Location: /LogeVerse/propuestas");
exit;