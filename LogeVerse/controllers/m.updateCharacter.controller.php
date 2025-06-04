<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("Location " . url_init . "/LogeVerse/inicio");
    exit;
}

if (isset($_SESSION["usuario"])) {
    //Preparación de los datos recibidos por post
    if ($_POST) {
        $atributos = [];
        foreach ($_POST as $key => $value) {
            if (str_starts_with($key, "atr_")) {
                $num = (int) substr($key, 4);
                $atributos[$num] = htmlspecialchars($value);
            } else {
                $$key = htmlspecialchars($value);
            }
        }
        if (isset($id_pj) && $id_pj > 0) {
            $owner = false;
            try {
                $pdo = conectar();
                $stmt = $pdo->prepare("SELECT propietario FROM personaje WHERE id = :id;");
                $stmt->bindParam(":id", $id_pj, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result !== false) {
                    $owner = true;
                }
                $pdo = null;
            } catch (PDOException $error) {
                $pdo = null;
                $_SESSION["alert"] = "Error al conectar con la base de datos.";
                header("Location: " . url_init . "/LogeVerse/perfil/personaje");
                exit;
            }
            if ($owner) {
                include root_dir . "/LogeVerse/modules/execute/updateCharacter.php";
            } else {
                $_SESSION["alert"] = "El personaje seleccionado no es de tu propiedad.";
                header("Location: " . url_init . "/LogeVerse/perfil");
                exit;
            }
        } else {
            $_SESSION["alert"] = "ID de personaje no válido.";
            header("Location: " . url_init . "/LogeVerse/perfil");
            exit;
        }
    } else {
        $_SESSION["alert"] = "Acceso ilegal.";
        header("Location: " . url_init . "/LogeVerse/inicio");
        exit;
    }
} else {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: " . url_init . "/LogeVerse/inicio");
    exit;
}