<?php
include_once 'LogeVerse/classes/include_classes.php';
include_once 'LogeVerse/modules/toDatabase.module.php';

/*  Este es el módulo para eliminar perfiles, por ello la condición de entrada al if es rigurosa
 *   
 *   El usuario debe confirmar que quiere borrar su cuenta introduciendo su nombre de usuario
 *   en mayúsculas, si se accede aquí de otra manera, el acceso se habrá hecho modificando el script
 *
 */
session_start();

if (isset($_SESSION["usuario"])) {
    $upperName = strtoupper($_SESSION["usuario"]->getNombre());
    if (isset($_POST) && isset($_POST["confirmation"]) && $_POST["confirmation"] === $upperName) {
        //eliminarJugador puede lanzar un error, por ello controlamos desde un try-catch
        try {
            $conexion = conectar();
            eliminarJugador($conexion, $_SESSION["usuario"]->getId());
            $conexion = null;
            $_SESSION = [];
            session_destroy();
            header("Location: /LogeVerse/inicio");
            exit;
        } catch (Error $e) {
            $_SESSION["alert"] = "Error intentando borrar el usuario de la base de datos";
            header("Location: /LogeVerse/perfil/ajustes");
            exit;
        }
    } else {
        $_SESSION["alert"] = "Acceso ilegal.";
        header("Location: /LogeVerse/perfil/ajustes");
        exit;
    }
    //Si ves esto has perdido el juego: https://es.wikipedia.org/wiki/El_Juego_(juego_mental)
} else {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: /LogeVerse/incio");
    exit;
}