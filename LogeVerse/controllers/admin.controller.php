<?php

$title = "Portal Admin";

$nombre_propuestas = [
    0 => "Atributo",
    1 => "Clase",
    2 => "Efecto",
    3 => "Habilidad",
    4 => "Objeto",
    5 => "Pasiva",
    6 => "Raza"
];

if (isset($_SESSION["usuario"])) {
    if ($_SESSION["usuario"]->getAdmin()) {
        try {
            $admin = checkAdmin($_SESSION["usuario"]->getId());
        } catch (PDOException $e) {
            $pdo = null;
            $admin = false;
        }
        if ($admin) {
            $section = 0;
            if (isset($_POST["admin_section"])) {
                $section = $_POST["admin_section"];
                $section_name = isset($nombre_propuestas[$section]) ? $nombre_propuestas[$section] : "Error";
            } else {
                $section_name = 'Atributo';
            }
            require 'LogeVerse/views/admin/admin.php';
        } else {
            $_SESSION["alert"] = "No tienes permiso para acceder a esta página.\nContacta con un moderador.";
            header("Location: LogeVerse/inicio");
            exit;
        }
    }
} else {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: LogeVerse/inicio");
    exit;
}