<?php
if (isset($_POST)) {
    $_SESSION["POST"] = $_POST;
    include "LogeVerse/modules/execute/propuesta.module.php";
} else {
    $_SESSION["alert"] = "Error al obtener los datos del formulario.";
}
header("Location: /LogeVerse/propuestas");
exit;