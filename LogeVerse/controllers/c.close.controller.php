<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("Location: " . url_init . "/LogeVerse/inicio");
    exit;
}
$_SESSION = [];
session_destroy();
header("Location: " . url_init . "/LogeVerse/inicio");
exit;