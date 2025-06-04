<?php
if (isset($_POST)) {
    $_SESSION["POST"] = $_POST;
    include root_dir . "LogeVerse/modules/execute/propuesta.module.php";
} else {
    $_SESSION["alert"] = "Error al obtener los datos del formulario.";
}
header("Location: " . url_init . "/LogeVerse/propuestas");
exit;