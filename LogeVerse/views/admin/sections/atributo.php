<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("Location " . url_init . "/LogeVerse/inicio");
    exit;
}
?>
<div>
    <?php
    try {
        $pdo = conectar();
        $stmt = $pdo->prepare("SELECT id, nombre, descripcion FROM prop_atributo;");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $html = "<table>
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Nombre</td>
                                <td>Descripción</td>
                                <td>Acción</td>
                            </tr>
                        </thead>
                    <tbody>";
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $atributo) {
                $html .= "<tr>
                            <form action=" . url_init . "'LogeVerse/aceptar/Atributo' method='POST'>
                                <td><input name='atributo_id' type='number' value='" . $atributo["id"] . "' readonly required></td>
                                <td><input name='atributo_nombre' type='text' value='" . $atributo["nombre"] . "' required></td>
                                <td><input name='atributo_descripcion' type='text' value='" . $atributo["descripcion"] . "' required></td>
                                <td><button class='btn_aceptar' type='submit'>ACEPTAR</button></td>
                            </form>
                          </tr>";
            }
            $html .= "</tbody>
                    </table>";
            echo $html;
        } else {
            echo "<p class='infoMsg'>😭Actualmente no hay ningún Atributo propuesto.😭</p>";
        }
        $pdo = null;
    } catch (PDOException $e) {
        echo "<p>Error al conectar con la base de datos.</p>";
    }
    ?>
</div>