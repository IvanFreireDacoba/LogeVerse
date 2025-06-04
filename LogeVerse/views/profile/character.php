<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("Location: " . url_init . "/LogeVerse/inicio");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    //Añadimos el head de la página común al resto de páginas
    include_once root_dir . 'LogeVerse/views/shared/head.php';
    ?>
    <link rel="stylesheet" href="/LogeVerse/views/profile/styles/character.css">
</head>

<body>
    <?php
    //Añadimos la cabecera de la página comín al resto de páginas
    // y el menú de navegación
    include_once root_dir . 'LogeVerse/views/shared/header.php';
    ?>
    <main id="ficha_personaje">
        <div id="header_pj">
            <div class="col1">
                <p id="nombre_personaje">Nombre: <?php echo $personaje->getNombre() ?></p>
                <p id="lvl_personaje">Nv. <?php echo $personaje->getLvlNow() ?></p>
                <img id="img_personaje" src="<?php echo $personaje->getImgData() ?>" alt="Imagen del personaje">
            </div>
            <div class="col2">
                <div id="atributos_personaje">
                    <?php
                    $html = "";
                    if ($personaje->getPuntosHabilidad() > 0) {
                        $html .= "<p>Puntos disponibles: <a id='ptos_habilidad'>{$personaje->getPuntosHabilidad()}</a></p>";
                    }
                    $html .= "
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
                            <td id='table_{$key}' class='td_input'>
                                <input class='{$key} atr_input' type='number' min='{$value}' step='1' value='{$value}' readonly>
                                <button class='plus_atr'>+</button>
                            </td>
                          </tr>";
                    }
                    $html .= "</tbody>
                              </table>";
                    echo $html;
                    ?>
                </div>
                <div id="datos_personaje">
                    <div id="raza_clase_personaje">
                        <div id="raza_pj_div">
                            <p id="raza_pj"><?php echo $personaje->getRaza()->getNombre() ?></p>
                            <img id="raza_img_pj"
                                src="<?php echo $personaje->getRaza()->getImagen() ?>" alt="Imagen de la raza">
                        </div>
                        <div id="clase_pj_div">
                            <p id="clase_pj"><?php echo $personaje->getClase()->getNombre() ?></p>
                            <img id="clase_img_pj"
                                src="<?php echo $personaje->getClase()->getImagen() ?>" alt="Imagen de la clase">
                        </div>
                    </div>
                    <p id="xp_personaje">Sig. Nivel:
                        <?php echo $personaje->getExpNext() ?>
                    </p>
                    <p id="dinero_personaje">Dinero: <?php echo $personaje->getDinero() ?> LCs</p>
                    <p id="estado_personaje">Estado:
                        <?php echo $personaje->getEstado() ? "<a class='green sphere'></a>" : "<a class='red sphere'></a>"; ?>
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
            <details id="idiomas_pj">
                <summary>Idiomas</summary>
                <?php
                if (!empty($personaje->getRaza()->getIdiomas())) {
                    foreach ($personaje->getRaza()->getIdiomas() as $idioma) {
                        echo "<p title='{$idioma->getDescripcion()}'>{$idioma->getNombre()}</p>";
                    }
                } else {
                    echo "<p>El personaje no conoce ningún idioma.</p>";
                }
                ?>
            </details>
        </div>
        <div id="inv_pj">
            <h2>Inventario</h2>
            <div>
                <?php
                foreach ($personaje->getInventario()->getObjetos() as $objeto) {
                    $html_objeto = "<div class='objeto ficha_objeto' id='{$objeto[0]->getId()}'>
                                            <img src='{$objeto[0]->getImagen()}' alt='Imagen del objeto'>
                                            <p>{$objeto[0]->getNombre()}</p>
                                            <p>{$objeto[0]->getDescripcion()}</p>
                                            <p>Cantidad: {$objeto[1]}</p>
                                        </div>";
                    echo $html_objeto;
                }
                ?>
            </div>
        </div>
        <form action="<?php echo url_init ?>/LogeVerse/updateCharacter" method="POST" enctype="multipart/form-data">
            <div class="hidden">
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
        <div class="dangerZone">
            <form>
                <input class="hidden" name="id_pj" value="<?php echo $personaje->getId() ?>" readonly>
                <button>ELIMINAR PERSONAJE</butt>
            </form>
        </div>
    </main>
    <?php
    //Añadimos el pie de página común al resto de páginas
    include_once root_dir . 'LogeVerse/views/shared/footer.html';
    ?>
</body>

</html>