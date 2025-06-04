<?php
//Preparación de los datos recibidos por post
if ($_POST) {
    foreach ($_POST as $key => $value) {
        $$key = htmlspecialchars($value);
    }
}
include root_dir . "LogeVerse/modules/execute/login.module.php";