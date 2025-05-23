<?php
$title = "Ajustes";

include_once '../classes/include_classes.php';

session_start();

if (isset($_SESSION["usuario"])) {
    require '../views/profile/settings.view.php';
} else {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: ../controllers/index.controller.php");
    exit;
}