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
        //Obtener los tipos de habilidades
        $stmt = $pdo->prepare("SELECT valor FROM constantes WHERE nombre LIKE 'habilidad_tipo_%';");
        $stmt->execute();
        $tipos = $stmt->fetchAll(PDO::FETCH_COLUMN);
        //Obtener qué jugadores han propuesto las habilidades
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT id,
                                             id_prop AS id_clase, 
                                             nombre,
                                             fecha
                                        FROM jugador JOIN propuestas
                                          ON jugador.id = propuestas.id_jugador
                                       WHERE tipo = 'habilidad';");
        $stmt->execute();
        $propuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Obtener todos los efectos de las habilidades
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT id_habilidad,
                                             id_efecto,
                                             nombre,
                                             descripcion,
                                             cantidad,
                                             duracion,
                                             tipo,
                                             modificador
                                        FROM prop_efecto_habilidad JOIN efecto
                                          ON prop_efecto_habilidad.id_efecto = efecto.id;");
        $stmt->execute();
        $efectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Obtener las habilidades propuestas
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT id, nombre, descripcion, tipo, nivel FROM prop_habilidad;");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $html = "<table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Tipo</th>
                                <th>Nivel</th>
                                <th>Efectos</th>
                                <th>Jugador</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                    <tbody>";
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $habilidad) {
                $html .= "<tr>
                            <form action='LogeVerse/aceptar/Habilidad' method='POST'>
                            <td><input name='id' type='number' value='" . $habilidad["id"] . "' readonly required></td>
                            <td><input name='nombre' type='text' value='" . $habilidad["nombre"] . "' required></td>
                            <td><textarea name='descripcion' value='" . $habilidad["descripcion"] . "' required>" . $habilidad["descripcion"] . "</textarea></td>
                            <td>
                                <select name='tipo' required>
                                    <option  value='" . $habilidad["tipo"] . "'>" . $habilidad["tipo"] . "</option>";
                foreach ($tipos as $tipo) {
                    if ($tipo != $habilidad["tipo"]) {
                        $html .= "<option  value='" . $tipo . "'>" . $tipo . "</option>";
                    }
                }
                $html .= "</select>
                            </td>
                            <td>" . $habilidad["nivel"] . "</td>
                            <td>";
                if (!empty($efectos)) {
                    $html .= "       <details>
                                    <summary>Efectos</summary>";
                    foreach ($efectos as $key => $efecto) {
                        if ($efecto["id_habilidad"] == $habilidad["id"]) {
                            echo "<p>" . $efecto["nombre"] . "<br>" . $efecto["cantidad"] . " x " . $efecto["modificador"] . "<br>Turnos: " . $efecto["duracion"] . "</p><hr>";
                            unset($efectos[$key]);
                        }
                    }
                    $html .= "      </details>";
                } else {
                    $html .= "<p>Sin efectos</p>";
                }
                $html .= "  </td>
                            <td>";
                foreach ($propuestas as $propuesta) {
                    if ($propuesta["id_clase"] == $habilidad["id"]) {
                        $html .= "<p id_player='" . $propuesta["id"] . "'>" . $propuesta["nombre"] . "<br>" . $propuesta["fecha"] . "</p>";
                        break;
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
            echo "<p class='infoMsg'>😭Actualmente no hay ninguna Habilidad propuesta.😭</p>";
        }
        $pdo = null;
    } catch (PDOException $e) {
        $pdo = null;
        echo "<p>Error al conectar con la base de datos.</p>";
    }
    ?>
</div>