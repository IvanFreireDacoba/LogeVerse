<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("Location " . url_init . "/LogeVerse/inicio");
    exit;
}
if (isset($_POST)) {
    try {
        $pdo = conectar();
        $stmt = $pdo->prepare("CALL aceptarEfecto(:id, @resultado);");
        $stmt->bindParam(":id", $_POST["id"], PDO::PARAM_INT);
        $stmt->execute();
        $stmt = $pdo->query("SELECT @resultado;");
        $resultado = $stmt->fetchColumn();
        $_SESSION["alert"] = "Efecto migrado correctamente.\nNuevo ID: " . $resultado;
    } catch (PDOException $error) {
        $_SESSION["alert"] = "No se ha podido aceptar el efecto.\n" . $error->getMessage();
    }
}
header("Location " . url_init . "/LogeVerse/portalAdmin");
exit;