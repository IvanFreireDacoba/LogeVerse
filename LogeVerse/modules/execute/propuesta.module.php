<?php

//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("Location: " . url_init . "/LogeVerse/inicio");
    exit;
}
$datos = [];
foreach ($_POST as $key => $value) {
    if (!str_contains($key, "imagen")) {
        $datos[$key] = htmlspecialchars($value);
    } elseif (
        isset($_FILES[$key]) && is_uploaded_file($_FILES[$key]['tmp_name']) &&
        exif_imagetype($_FILES[$key]['tmp_name'])
    ) {
        $mime = mime_content_type($_FILES[$key]['tmp_name']);
        $formatosPermitidos = ['image/jpeg', 'image/png'];  //, 'image/gif'
        if (in_array($mime, $formatosPermitidos)) {
            $datos[$key] = file_get_contents($_FILES[$key]['tmp_name']);
        } else {
            $_SESSION["alert"] = "Formato de imagen no permitido.\nPor favor usa: JPG o PNG.";
            $datos["proposal_type"] = "invalid";
            $exito = false;
        }
    }
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
                $_SESSION["alert"] = $infoMsg;
                $_SESSION["datos"] = null;
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
    if ($exito === null) {
        $_SESSION["alert"] = "La entidad propuesta no existe.";
    }
}