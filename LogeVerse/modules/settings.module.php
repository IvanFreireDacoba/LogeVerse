<?php
include_once 'LogeVerse/classes/include_classes.php';
include_once 'LogeVerse/modules/toDatabase.module.php';

session_start();

if (isset($_SESSION["usuario"])) {
    $alert = null;
    if (isset($_POST)) {
        $pdo = conectar();
        $id_usuario = $_SESSION["usuario"]->getId();
        if (isset($_POST["name"]) && $_POST["name"] !== $_SESSION["usuario"]->getNombre()) {
            $stmt = $pdo->prepare("UPDATE jugador SET nombre = :nombre  WHERE id = :id_usuario;");
            $stmt->bindParam(':nombre', $_POST["name"]);
            $stmt->bindParam(':id_usuario', $id_usuario);
            try {
                $stmt->execute();
                $alert = "Nombre actualizado con éxito.";
                $_SESSION["usuario"]->setNombre($_POST["name"]);
            } catch (PDOException $e) {
                $alert = "Error || Nombre no disponible.";
            }
        }
        if (isset($_POST["email"]) && $_POST["email"] !== $_SESSION["usuario"]->getCorreo()) {
            $stmt = $pdo->prepare("UPDATE jugador SET correo = :email  WHERE id = :id_usuario;");
            $stmt->bindParam(':email', $_POST["email"]);
            $stmt->bindParam(':id_usuario', $id_usuario);
            try {
                $stmt->execute();
                if ($alert) {
                    $alert .= "\\n\\nCorreo actualizado con éxito.";
                } else {
                    $alert = "Correo actualizado con éxito.";
                }
                $_SESSION["usuario"]->setCorreo($_POST["email"]);
            } catch (PDOException $e) {
                if ($alert) {
                    $alert .= "\\n\\nError || Correo no disponible.";
                } else {
                    $alert = "Error || Correo no disponible.";
                }
            }
        }
        if (isset($_POST["password"]) && isset($_POST["password_confirm"]) && !empty($_POST["password"])) {
            if ($_POST["password"] === $_POST["password_confirm"]) {
                $stmt = $pdo->prepare("UPDATE jugador SET hash = :pwd  WHERE id = :id_usuario;");
                $hashed_pwd = password_hash($_POST["password"], PASSWORD_DEFAULT);
                $stmt->bindParam(':pwd', $hashed_pwd);
                $stmt->bindParam(':id_usuario', $id_usuario);
                try {
                    $stmt->execute();
                    if ($alert) {
                        $alert .= "\\n\\nContraseña actualizada con éxito.";
                    } else {
                        $alert = "Contraseña actualizada con éxito.";
                    }
                } catch (PDOException $e) {
                    if ($alert) {
                        $alert .= "\\n\\nError || La contraseña no se ha podido actualizar.";
                    } else {
                        $alert = "Error || La contraseña no se ha podido actualizar.";
                    }
                }
            } else {
                if ($alert) {
                    $alert .= "\\n\\nError || Las constraseñas no coinciden.";
                } else {
                    $alert = "\\n\\nError || Las constraseñas no coinciden.";
                }
            }
        }
        $pdo = null;
        $_SESSION["alert"] = $alert;
    } else {
        $_SESSION["alert"] = "Acceso inválido - Error al recuperar los datos del formulario.";
    }
    header('Location: /LogeVerse/perfil/ajustes');
    exit;
} else {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: /LogeVerse/perfil/ajustes");
    exit;
}