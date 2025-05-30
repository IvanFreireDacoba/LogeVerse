<?php
//Control de acceso desde perfil de administrador
if (!isset($_SESSION["usuario"]) && checkAdmin($_SESSION["usuario"]->getAdmin())) {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: LogeVerse/inicio");
    exit;
}
if (isset($_POST)) {
    include "LogeVerse/modules/aceptar/atributo.module.php";
}
header("Location /LogeVerse/portalAdmin");
exit;