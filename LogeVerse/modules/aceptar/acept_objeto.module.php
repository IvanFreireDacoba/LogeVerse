<?php
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
header("Location /LogeVerse/portalAdmin");
exit;