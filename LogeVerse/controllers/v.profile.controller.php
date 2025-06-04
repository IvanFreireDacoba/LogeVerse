<?php
$title = "Perfil";
if (isset($_SESSION["usuario"])) {
    require root_dir . "LogeVerse/views/profile/profile.php";
} else {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: " . url_init . "LogeVerse/inicio");
    exit;
}