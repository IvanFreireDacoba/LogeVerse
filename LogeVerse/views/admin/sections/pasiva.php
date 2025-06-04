<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("Location: " . url_init . "/LogeVerse/inicio");
    exit;
}
?>
<div>
    <?php
    try {
        $pdo = conectar();
        //Obtener todos los efectos de las pasivas propuestas
        $stmt = $pdo->prepare("SELECT efecto.id AS id_efecto,
                                             efecto.descripcion AS efecto_descripcion,
                                             efecto.cantidad AS efecto_cantidad,
                                             efecto.duracion AS efecto_duracion,
                                             efecto.tipo AS efecto_tipo,
                                             prop_efecto_pasiva.id_pasiva AS id_pasiva,
                                             prop_efecto_pasiva.modificador AS modificador
                                        FROM prop_efecto_pasiva JOIN efecto
                                          ON prop_efecto_pasiva.id_efecto = efecto.id
                                       WHERE prop_efecto_pasiva.id_efecto = ANY (SELECT id FROM prop_pasiva)");
        $stmt->execute();
        $efectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Obtener todas las pasivas propuestas
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT id,
                                             nombre,
                                             descripcion
                                        FROM prop_pasiva;");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $html = "<table>
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Nombre</td>
                                <td>Descripción</td>
                                <td>Efectos</td>
                                <td>Acción</td>
                            </tr>
                        </thead>
                    <tbody>";
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $pasiva) {
                $html .= "<tr>
                            <form action=" . url_init . "'LogeVerse/aceptar/Pasiva' method='POST'>
                            <td><input name='id' type='number' value='" . $pasiva["id"] . "' readonly required></td>
                            <td><input name='nombre' type='text' value='" . $pasiva["nombre"] . "' required></td>
                            <td><textarea name='descripcion' value='" . $pasiva["descripcion"] . "' required>" . $pasiva["descripcion"] . "</textarea></td>
                            <td>";
                foreach ($efectos as $key => $efecto) {
                    if ($efecto["id_pasiva"] == $pasiva["id"]) {
                        $html .= "<details title=" . $efecto["descripcion"] . ">
                                    <summary>" . $efecto["nombre"] . "</summary>
                                    <p>" . $efecto["tipo"] . "</p>
                                    <p>Turnos: " . $efecto["duracion"] === 0 ? $efecto["duracion"] : "Instantáneo" . "</p>
                                    <p>" . $efecto["cantidad"] . " x " . $efecto["modificador"] . "</p>
                                </details>";
                        unset($efectos[$key]);
                    }
                }
                $html .= "</td>
                            <td>
                                <button class='btn_aceptar' type='submit'>ACEPTAR</button>
                            </td>
                            </form>
                        </tr>";
            }
            $html .= "</tbody>
                </table>";
            echo $html;
        } else {
            echo "<p class='infoMsg'>😭Actualmente no hay ninguna Pasiva propuesta.😭</p>";
        }
        $pdo = null;
    } catch (PDOException $e) {
        echo "<p>Error al conectar con la base de datos.</p>";
    }
    ?>
</div>