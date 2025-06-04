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
        //Obtener todos los atributos de razas propuestas
        $stmt = $pdo->prepare("SELECT prop_atributo_raza.id_raza AS id_raza,
                                             atributo.nombre AS nombre,
                                             prop_atributo_raza.cantidad AS cantidad
                                        FROM prop_atributo_raza JOIN atributo
                                          ON prop_atributo_raza.id_atributo = atributo.id;");
        $stmt->execute();
        $atributos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Obtener todas las pasivas de las razas propuestas
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT pasiva.id AS id_pasiva,
                                             prop_pasiva_raza.id_raza AS id_raza,
                                             pasiva.nombre AS nombre,
                                             pasiva.descripcion AS descripcion
                                        FROM prop_pasiva_raza JOIN pasiva
                                          ON prop_pasiva_raza.id_pasiva = pasiva.id;");
        $stmt->execute();
        $pasivas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Obtener todas los idiomas de las razas propuestas
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT idioma.id AS id_idioma,
                                             prop_idioma_raza.id_raza AS id_raza,
                                             nombre
                                        FROM prop_idioma_raza JOIN idioma
                                          ON prop_idioma_raza.id_idioma = idioma.id;");
        $stmt->execute();
        $idiomas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Obtener todas las razas propuestas
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT id,
                                             nombre,
                                             caracteristicas,
                                             historia,
                                             velocidad
                                        FROM prop_raza;");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $html = "<table>
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Nombre</td>
                                <td>Descripción</td>
                                <td>Pasivas</td>
                                <td>Atributos</td>
                                <td>Idiomas</td>
                                <td>Acción</td>
                            </tr>
                        </thead>
                    <tbody>";
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $raza) {
                $html .= "<tr>
                            <form action=" . url_init . "'LogeVerse/aceptar/Raza' method='POST'>
                            <td><input name='id' type='number' value='" . $raza["id"] . "' readonly required></td>
                            <td><input name='nombre' type='text' value='" . $raza["nombre"] . "' required></td>
                            <td><textarea name='descripcion' value='" . $raza["descripcion"] . "' required>" . $raza["descripcion"] . "</textarea></td>
                            <td>";
                foreach ($pasivas as $key => $pasiva) {
                    if ($raza["id"] === $pasiva["id_raza"]) {
                        $html .= "<p title='" . $pasiva["descripcion"] . "'>" . $pasiva["nombre"] . "</p>";
                        unset($pasivas[$key]);
                    }
                }
                $html .= "</td>
                          <td>";
                foreach ($idiomas as $key => $idioma) {
                    if ($raza["id"] === $idioma["id_raza"]) {
                        $html .= "<p>" . $idioma["nombre"] . "</p>";
                        unset($idiomas[$key]);
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