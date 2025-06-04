<?php
if (isset($_SESSION["usuario"])) {
    include root_dir . "LogeVerse/modules/execute/drop_profile.module.php";
} else {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: " . url_init . "/LogeVerse/incio");
    exit;
}