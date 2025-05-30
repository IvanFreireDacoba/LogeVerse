<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("/LogeVerse/inicio");
    exit;
}
?>
<div>
    <?php
    try {
        $pdo = conectar();
        $stmt = $pdo->prepare("SELECT id, nombre, descripcion FROM prop_objeto;");
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
            //foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $objeto) {
            $html .= "</tbody>
                    </table>";
            echo $html;
        } else {
            echo "<p class='infoMsg'>😭Actualmente no hay ningún Objeto propuesto.😭</p>";
        }
        $pdo = null;
    } catch (PDOException $e) {
        echo "<p>Error al conectar con la base de datos.</p>";
    }
    ?>
</div>