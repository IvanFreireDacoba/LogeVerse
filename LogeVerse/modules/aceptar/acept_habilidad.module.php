<?php
if (isset($_POST)) {
    try {
        $pdo = conectar();
        $stmt = $pdo->prepare("CALL aceptarHabilidad(:id, @resultado);");
        $stmt->bindParam(":id", $_POST["id"], PDO::PARAM_INT);
        $stmt->execute();
        $stmt = $pdo->query("SELECT @resultado;");
        $resultado = $stmt->fetchColumn();
        $_SESSION["alert"] = "Habilidad migrada correctamente.\nNuevo ID: " . $resultado;
    } catch (PDOException $error) {
        $_SESSION["alert"] = "No se ha podido aceptar la habilidad.\n" . $error->getMessage();
    }
}
header("Location /LogeVerse/portalAdmin");
exit;