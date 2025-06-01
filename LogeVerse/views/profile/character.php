<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("/LogeVerse/inicio");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    //Añadimos el head de la página común al resto de páginas
    include_once 'LogeVerse/views/shared/head.php';
    ?>
</head>

<body>
    <?php
    //Añadimos la cabecera de la página comín al resto de páginas
    // y el menú de navegación
    include_once 'LogeVerse/views/shared/header.php';
    ?>
    <main id="ficha_personaje">
        <div id="header_pj">
            <div class="col1">
                <p id="nombre_personaje">Nombre: <?php echo $personaje->getNombre() ?></p>
                <img id="img_personaje" src="<?php echo $personaje->getImgData() ?>" alt="Imagen del personaje">
            </div>
            <div>
                <p id="dinero_personaje">Dinero: <?php echo $personaje->getDinero() ?> LCs</p>
                <p id="xp_personaje">Nivel: <?php echo $personaje->getLvlNow() ?> || Sig. Nivel:
                    <?php echo $personaje->getExpNext() ?>
                </p>
                <p id="raza_personaje">Raza: <?php echo $personaje->getRaza()->getNombre() ?></p>
                <p id="clase_personaje">Clase: <?php echo $personaje->getClase()->getNombre() ?></p>
                <p id="estado_personaje">Estado:
                    <?php echo $personaje->getEstado() ? "<a class='green sphere'></a>" : "<a class='red sphere'></a>"; ?>
                </p>
                <div id="idiomas_pj">
                    <p><a>Idiomas</a>
                        <?php
                        if (!empty($personaje->getRaza()->getIdiomas())) {
                            foreach ($personaje->getRaza()->getIdiomas() as $idioma) {
                                echo "<a title='{$idioma->getDescripcion()}'>{$idioma->getNombre()}</a>";
                            }
                        } else {
                            echo "<p>El personaje no conoce ningún idioma.</p>";
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <div id="data_pj">
            <details id="historia_pj">
                <summary>Historia</summary>
                <textarea name="historia_pj"
                    placeholder="Escribe la historia de tu personaje.
                    Puedes incluir su personalidad, descripción física o vivencias para contextualizar tu rol."><?php echo $personaje->getHistoria() ?></textarea>
            </details>
            <details>
                <summary>Pasivas</summary>
                <?php
                if (!empty($personaje->getRaza()->getPasivas()[0])) {
                    foreach ($personaje->getRaza()->getPasivas() as $pasiva) {
                        $html = "<details><summary>{$pasiva->getNombre()}</summary>";
                        if ($pasiva->getEfectos()) {
                            foreach ($pasiva->getEfectos() as $efecto_compuesto) {
                                $html_hab .= "<p title='{$efecto_compuesto[0]->getDescripcion()}'>$efecto_compuesto[0]->getNombre(): ";
                                $html_hab .= ($efecto_compuesto[0]->getCantidad * $efecto_compuesto[1]) . " | Turnos: ";
                                $html_hab .= $efecto_compuesto[0]->getDuracion() == 0 ? "Instantáneo" : $efecto_compuesto[0]->getDuracion();
                                $html_hab .= "</p>";
                            }
                        } else {
                            $html .= "<p>La pasiva no tiene efectos.</p>";
                        }
                        $html .= "</details>";
                        echo $html;
                    }
                } else {
                    echo "<p>El personaje no tiene pasivas.<p>";
                }
                ?>
            </details>
            <details>
                <summary>Habilidades</summary>
                <?php
                if ($personaje->getHabilidades()) {
                    foreach ($personaje->getHabilidades() as $habilidad) {
                        $html_hab = "<details><summary>{$habilidad->getNombre()}</summary>";
                        if ($habilidad->getEfectos()) {
                            foreach ($habilidad->getEfectos() as $efecto_compuesto) {
                                $html_hab .= "<p title='{$efecto_compuesto[0]->getDescripcion()}'>$efecto_compuesto[0]->getNombre(): ";
                                $html_hab .= ($efecto_compuesto[0]->getCantidad * $efecto_compuesto[1]) . " | Turnos: ";
                                $html_hab .= $efecto_compuesto[0]->getDuracion() == 0 ? "Instantáneo" : $efecto_compuesto[0]->getDuracion();
                                $html_hab .= "</p>";
                            }
                        } else {
                            $html_hab .= "<p>Sin efectos</p>";
                        }
                        $html_hab .= "<details>";
                        echo $html_hab;
                    }
                } else {
                    echo "<p>El personaje no tiene habilidades.</p>";
                }
                ?>
            </details>
        </div>
        <div>
            <?php
            $html = "<label for='ptos_habilidad'>Puntos disponibles: </label>
                     <input id='ptos_habilidad' name='ptos_habilidad' type='number' min='0' max='{$personaje->getPuntosHabilidad()}' step='1' value='{$personaje->getPuntosHabilidad()}' readonly>
                     <table>
                        <thead>
                            <tr>
                                <th>Atributo</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>";
            $total_ptos_init = 0;
            foreach ($personaje->getAtributos() as $key => $value) {
                $total_ptos_init += $value;
                $html .= "<tr>
                            <td>
                                {$key}
                            </td>
                            <td>
                                <input class='{$key}' type='number' min='{$value}' step='1' value='{$value}'>
                            </td>
                          </tr>";
            }
            $html .= "</tbody>
                              </table>";
            echo $html;
            ?>
        </div>
        <div id="inv_pj">

        </div>
        <div>
            <form action="/LogeVerse/updateCharacter" method="POST" enctype="multipart/form-data">
                <div style="display: none;">
                    <input name="id_pj" value="{$personaje->getId()}" readonly>
                    <!-- Controlar si se actualizará la historia -->
                    <input name='update_historia' value='false' readonly>
                    <textarea id="historia_input" name="historia"></textarea>
                    <!-- Controlar si se actualizará la imagen -->
                    <input name='update_img' value='false' readonly>
                    <input name="image_pj" type="file" value="{$personaje->getImgData()}" readonly>
                    <!-- Controlar si se actualizarán los atributos -->
                    <input name='update_atr' value='false' readonly>
                    <input value="<?php echo $total_ptos_init ?>">
                    <?php
                    foreach ($personaje->getAtributos() as $key => $value) {
                        echo "<input class='{$key}' name='atr_{$atributos[$key]}' value='{$value}'>";
                    }
                    ?>
                </div>
                <button id="btn_update" class="hidden" type="submit">ACTUALIZAR</button>
            </form>
        </div>
    </main>
    <?php
    //Añadimos el pie de página común al resto de páginas
    include 'LogeVerse/views/shared/footer.html';
    ?>
</body>

</html>