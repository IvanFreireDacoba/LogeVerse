<?php
//Control de acceso desde perfil
if (!isset($_SESSION["usuario"])) {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: " . url_init . "LogeVerse/inicio");
    exit;
}
if ($_POST) {
    include root_dir . "LogeVerse/modules/execute/newCharacter.module.php";
} else {
    $_SESSION["alert"] = "Error al obtener los datos del formulario.";
    $conexion = null; //Cerramos la conexión a la base de datos
    header("Location: " . url_init . "LogeVerse/nuevoPersonaje");
    exit;
}