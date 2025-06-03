<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("/LogeVerse/inicio");
    exit;
}
?>
<div id="tabla_propuestas">
    <?php
    try {
        $pdo = conectar();
        //Obtener los tipos de efectos
        $stmt = $pdo->prepare("SELECT valor FROM constantes WHERE nombre LIKE 'efecto_tipo_%';");
        $stmt->execute();
        $tipos = $stmt->fetchAll(PDO::FETCH_COLUMN);
        //Obtener qué jugadores han propuesto los efectos
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT id,
                                             id_prop AS id_efecto, 
                                             nombre,
                                             fecha
                                        FROM jugador JOIN propuestas
                                          ON jugador.id = propuestas.id_jugador
                                       WHERE tipo = 'efecto';");
        $stmt->execute();
        $propuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Obtener los efectos propuestos
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT id, nombre, descripcion, cantidad, duracion, tipo FROM prop_efecto;");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $html = "<table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Duración</th>
                            <th>Tipo</th>
                            <th>Jugador</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>";
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $efecto) {
                $html .= "<tr>
                        <form action='LogeVerse/aceptar/Efecto' method='POST'>
                        <td><input name='id' type='text' value='" . $efecto["id"] . "' readonly required></td>
                        <td><input name='nombre' type='text' value='" . $efecto["nombre"] . "' required></td>
                        <td><textarea name='descripcion' value='" . $efecto["descripcion"] . "' required>" . $efecto["descripcion"] . "</textarea></td>
                        <td><input name='cantidad' type='number' value='" . $efecto["cantidad"] . "' required></td>
                        <td><input name='duracion' type='number' value='" . $efecto["duracion"] . "' required></td>
                        <td>
                            <select name='tipo' required>
                                <option  value='" . $efecto["tipo"] . "'>" . $efecto["tipo"] . "</option>";
                foreach ($tipos as $tipo) {
                    if ($tipo != $efecto["tipo"]) {
                        $html .= "<option  value='" . $tipo . "'>" . $tipo . "</option>";
                    }
                }
                $html .= "</select>
                        </td>
                        <td>";
                foreach ($propuestas as $propuesta) {
                    if ($propuesta["id_efecto"] == $efecto["id"]) {
                        $html .= "<p id_player='" . $propuesta["id"] . "'>" . $propuesta["nombre"] . "<br>" . $propuesta["fecha"] . "</p>";
                        break;
                    }
                }
                $html .= "  </td>
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
            echo "<p class='infoMsg'>😭Actualmente no hay ningún Efecto propuesto.😭</p>";
        }
        $pdo = null;
    } catch (PDOException $e) {
        $pdo = null;
        echo "<p>Error al conectar con la base de datos.</p>";
    }
    ?>
</div>