<?php
$title = "Menú PJ";
if (isset($_SESSION["usuario"])) {
    if (isset($_POST["id"])) {
        try {
            $pdo = conectar();
            $stmt = $pdo->prepare("SELECT id, nombre FROM atributo;");
            $stmt->execute();
            $atributos = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $atributo) {
                $atributos[$atributo["nombre"]] = $atributo["id"];
            }
            $stmt->closeCursor();
            $stmt = $pdo->prepare("SELECT propietario FROM personaje WHERE id=:id;");
            $stmt->bindParam(":id", $_POST["id"], PDO::PARAM_INT);
            $stmt->execute();
            if ($_SESSION["usuario"]->getId() == $stmt->fetchColumn()) {
                $personaje = null;
                foreach ($_SESSION["usuario"]->getPersonajes() as $personaje_loop) {
                    if ($personaje_loop->getId() == $_POST["id"]) {
                        $personaje = $personaje_loop;
                        break;
                    }
                }
                if ($personaje !== null) {
                    include 'LogeVerse/views/profile/character.php';
                } else {
                    $_SESSION["alert"] = "Error al obtener el personaje.";
                    $_SESSION["usuario"] = refrescarUsuario($pdo, $_SESSION["usuario"]->getId());
                    header("Location: LogeVerse/perfil");
                }
            } else {
                $_SESSION["alert"] = "No eres propietario de ese personaje.\nHa sido un buen intento.\nNo toques más el HTML.";
                header("Location: LogeVerse/perfil");
            }
        } catch (PDOException) {
            $_SESSION["alert"] = "Error al conectar con la base de datos.";
        }
        $pdo = null;
    } else {
        $_SESSION["alert"] = "Error, acceso ilegal.";
        header("Location: LogeVerse/perfil");
        exit;
    }
} else {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: LogeVerse/inicio");
    exit;
}