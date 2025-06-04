<?php
//Control de acceso desde perfil de administrador
if (!isset($_SESSION["usuario"]) && checkAdmin($_SESSION["usuario"]->getAdmin())) {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: " . url_init . "/LogeVerse/inicio");
    exit;
}
if (isset($_POST)) {
    include root_dir . "LogeVerse/modules/aceptar/pasiva.module.php";
}
header("Location " . url_init . "/LogeVerse/portalAdmin");
exit;