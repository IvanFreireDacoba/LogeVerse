<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("Location: " . url_init . "/LogeVerse/inicio");
    exit;
}

$alert = "";

//<input name="id_pj" value="{$personaje->getId()}" readonly>
try {
    $pdo = conectar();
} catch (PDOException $error) {
    $pdo = null;
    $update_historia = false;
    $update_img = false;
    $update_atr = false;
    $alert = "Imposible conectar con la base de datos.";
}

//<input name='update_historia' value='false' readonly>
//<textarea id="historia_input" name="historia"></textarea>
if ($update_historia) {
    try {
        $stmt = $pdo->prepare("UPDATE personaje SET historia=:historia WHERE id=:id;");
        $stmt->bindParam(":historia", $historia_input, PDO::PARAM_STR);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $alert .= "Historia actualizada correctamente.\n";
    } catch (PDOException $error) {
        $alert = "Imposible actualizar la historia.\n";
    }
}

//<input name='update_img' value='false' readonly>
//<input name="image_pj" type="file" value="{$personaje->getImgData()}" readonly>
if ($update_img) {
    if (isset($_FILES['image_pj']) && $_FILES['image_pj']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['image_pj']['tmp_name']);
        try {
            $stmt->closeCursor();
            $stmt = $pdo->prepare("UPDATE imagen_personaje SET img_data=:img_data WHERE id_personaje=:id");
            $stmt->bindParam(':img_data', $imageData, PDO::PARAM_LOB);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $alert .= "Imagen actualizada correctamente.\n";
        } catch (PDOException $error) {
            $alert .= "Imposible actualizar la imagen.\n";
        }
    } else {
        $alert .= "Error al subir la imagen.\n";
    }
}

//<input name='update_atr' value='false' readonly>
//<input class='{$key}' name='atr_{$atributos[$key]}' value='{$value}'>";
if ($update_atr) {
    try {
        $pdo->beginTransaction();
        foreach ($atributos as $key => $value) {
            $stmt = $pdo->prepare("UPDATE atributo_personaje SET cantidad=:cant WHERE id_atributo=:id_atr AND id_personaje=:id");
            $stmt->bindParam(":cant", $value, PDO::PARAM_INT);
            $stmt->bindParam(":id_atr", $key, PDO::PARAM_INT);
            $stmt->bindParam(":id_personaje", $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        $pdo->commit();
    } catch (PDOException $error) {
        if($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $alert .= "Error al actualizar los atributos.";
    }
}

if (empty($alert)) {
    $alert = "No se ha actualizado nada.\nError desconocido.";
} else {
    $_SESSION["usuario"] = refrescarUsuario($pdo, $_SESSION["usuario"]->getId());
}

$pdo = null;
$_SESSION["alert"] = $alert;
header("Location: /LogeVerse/perfil/personaje");
exit;