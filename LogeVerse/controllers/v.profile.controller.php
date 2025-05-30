<?php
$title = "Perfil";
if (isset($_SESSION["usuario"])) {
    require 'LogeVerse/views/profile/profile.php';
} else {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: LogeVerse/inicio");
    exit;
}