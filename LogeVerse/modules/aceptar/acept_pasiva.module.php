<?php
if (isset($_POST)) {
    try {
        $pdo = conectar();
        $stmt = $pdo->prepare("CALL aceptarPasiva(:id, @resultado);");
        $stmt->bindParam(":id", $_POST["id"], PDO::PARAM_INT);
        $stmt->execute();
        $stmt = $pdo->query("SELECT @resultado;");
        $resultado = $stmt->fetchColumn();
        $_SESSION["alert"] = "Pasiva migrada correctamente.\nNuevo ID: " . $resultado;
    } catch (PDOException $error) {
        $_SESSION["alert"] = "No se ha podido aceptar la pasiva.\n" . $error->getMessage();
    }
}
header("Location /LogeVerse/portalAdmin");
exit;