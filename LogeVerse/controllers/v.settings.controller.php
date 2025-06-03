<?php
$title = "Ajustes";
if (isset($_SESSION["usuario"])) {
    require 'LogeVerse/views/profile/settings.view.php';
} else {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: LogeVerse/inicio");
    exit;
}