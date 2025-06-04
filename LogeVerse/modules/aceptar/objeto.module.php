<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("Location " . url_init . "/LogeVerse/inicio");
    exit;
}
if (isset($_POST)) {
    try {
        throw new Exception("NO IMPLEMENTADO");
        /*
        $pdo = conectar();
        $stmt = $pdo->prepare("CALL aceptarHabilidad(:id, @resultado);");
        $stmt->bindParam(":id", $_POST["id"], PDO::PARAM_INT);
        $stmt->execute();
        $stmt = $pdo->query("SELECT @resultado;");
        $resultado = $stmt->fetchColumn();
        $_SESSION["alert"] = "Habilidad migrada correctamente.\nNuevo ID: " . $resultado;
        */
    } catch (Exception $error) {
        $_SESSION["alert"] = "No se ha podido aceptar el objeto.\n" . $error->getMessage();
    }
}
header("Location " . url_init . "/LogeVerse/portalAdmin");
exit;