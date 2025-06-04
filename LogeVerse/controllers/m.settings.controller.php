<?php
if (isset($_SESSION["usuario"])) {
    $alert = null;
    if (isset($_POST)) {
        include root_dir . "LogeVerse/modules/execute/settings.module.php";
    } else {
        $_SESSION["alert"] = "Acceso inválido - Error al recuperar los datos del formulario.";
    }
    header("Location: " . url_init . "/LogeVerse/perfil/ajustes");
    exit;
} else {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: " . url_init . "/LogeVerse/perfil/ajustes");
    exit;
}