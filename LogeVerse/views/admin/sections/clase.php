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
        //Obtener todas las habilidades relacionadas con clases
        $stmt = $pdo->prepare("SELECT prop_clase_habilidad.id_clase AS clase_id,
                                             habilidad.id AS hab_id,
                                             habilidad.nombre AS nombre,
                                             habilidad.descripcion AS descripcion,
                                             habilidad.tipo AS tipo,
                                             habilidad.nivel AS nivel
                                        FROM habilidad RIGHT JOIN prop_clase_habilidad
                                          ON habilidad.id = prop_clase_habilidad.id_habilidad;");
        $stmt->execute();
        $habilidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Obtener todos los paquetes que se usan en las clases
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT id, nombre FROM objeto WHERE id = ANY(SELECT equipo_inicial FROM prop_clase);");
        $stmt->execute();
        $objetos = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $objeto) {
            $objetos[$objeto["id"]] = $objeto["nombre"];
        }
        //Obtener la lista de atributos
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT id, nombre, descripcion FROM atributo;");
        $stmt->execute();
        $atributos = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $atributo) {
            $atributos[$atributo["id"]] = [$atributo["nombre"], $atributo["descripcion"]];
        }
        //Obtener qué jugadores han propuesto las clases
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT id,
                                             id_prop AS id_clase, 
                                             nombre,
                                             fecha
                                        FROM jugador JOIN propuestas
                                          ON jugador.id = propuestas.id_jugador
                                       WHERE tipo = 'clase';");
        $stmt->execute();
        $propuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Obtener los datos de las clases
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT id,
                                             nombre,
                                             descripcion,
                                             dado_golpe,
                                             equipo_inicial,
                                             hp_atr,
                                             hp_mod,
                                             def_atr,
                                             def_mod,
                                             golpe_atr,
                                             golpe_mod
                                        FROM prop_clase LEFT JOIN prop_imagen_clase
                                          ON prop_clase.id = prop_imagen_clase.id_clase;");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $html = "<table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Equipación</th>
                                <th>Características</th>
                                <th>Habilidades</th>
                                <th>Jugador</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                    <tbody>";
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $clase) {
                $html .= "<tr>
                            <form action='LogeVerse/aceptar/Clase' method='POST'>
                                <td><input name='clase_id' type='number' value='" . $clase["id"] . "' readonly required></td>
                                <td><textarea name='clase_nombre' type='text' value='" . $clase["nombre"] . "' required>" . $clase["nombre"] . "</textarea></td>
                                <td><textarea name='clase_descripcion' type='text' value='" . $clase["descripcion"] . "' required>" . $clase["descripcion"] . "</textarea></td>
                                <td>" . $objetos[$clase["equipo_inicial"]] . "</td>
                                <td>
                                    <p>Tirada: d" . $clase["dado_golpe"] . "</p>
                                    <p>PS: " . $atributos[$clase["hp_atr"]][0] . " x " . $clase["hp_mod"] . "</p>
                                    <p>ATQ: " . $atributos[$clase["golpe_atr"]][0] . " x " . $clase["golpe_mod"] . "</p>
                                    <p>DEF: " . $atributos[$clase["def_atr"]][0] . " x " . $clase["def_mod"] . "</p>
                                </td>
                                <td>
                                    <details>
                                        <summary>Habilidades</summary>";
                if (!empty($habilidades)) {
                    foreach ($habilidades as $key => $hailidad) {
                        if ($habilidad["clase_id"] === $clase["id"]) {
                            $html .= $habilidad["nombre"] . " | Lvl: " . $habilidad["nivel"];
                            unset($habilidades[$key]);
                        }
                    }
                } else {
                    $html .= "Sin habilidades";
                }
                $html .= "          </details>
                                </td>
                                <td>";
                foreach ($propuestas as $key => $propuesta) {
                    if ($propuesta["id_clase"] == $clase["id"]) {
                        $html .= "<p id_player='" . $propuesta["id"] . "'>" . $propuesta["nombre"] . "<br>" . $propuesta["fecha"] . "</p>";
                        unset($propuestas[$key]);
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
            echo "<p>😭Actualmente no hay ninguna clase propuesta.😭</p>";
        }
        $pdo = null;
    } catch (PDOException $e) {
        echo "<p>Error al conectar con la base de datos.</p>";
    }
    ?>
</div>