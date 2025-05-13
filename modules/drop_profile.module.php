<?php
include_once '../classes/include_classes.php';
include_once '../modules/toDatabase.module.php';

session_start();

if (isset($_SESSION["usuario"])) {

} else {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: ../controllers/index.controller.php");
    exit;
}