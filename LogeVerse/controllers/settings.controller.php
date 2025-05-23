<?php
$title = "Ajustes";

include_once 'LogeVerse/classes/include_classes.php';

session_start();

if (isset($_SESSION["usuario"])) {
    require 'LogeVerse/views/profile/settings.view.php';
} else {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: LogeVerse/inicio");
    exit;
}