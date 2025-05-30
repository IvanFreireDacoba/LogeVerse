<?php
if (isset($_SESSION["usuario"])) {
    $alert = null;
    if (isset($_POST)) {
        include "LogeVerse/modules/execute/settings.module.php";
    } else {
        $_SESSION["alert"] = "Acceso inválido - Error al recuperar los datos del formulario.";
    }
    header('Location: /LogeVerse/perfil/ajustes');
    exit;
} else {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: /LogeVerse/perfil/ajustes");
    exit;
}