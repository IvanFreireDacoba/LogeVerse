<?php
if (isset($_SESSION["usuario"])) {
    include "LogeVerse/modules/execute/drop_profile.module.php";
} else {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: /LogeVerse/incio");
    exit;
}