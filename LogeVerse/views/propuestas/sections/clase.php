<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("Location: " . url_init . "/LogeVerse/inicio");
    exit;
}
?>
<section id="Clase" class="propuesta" hidden>
    <h4>Proponer Clase</h4>
    <form action="<?php echo url_init ?>/LogeVerse/proponer" method="POST" enctype="multipart/form-data">
        <div class="triple_col_grid">
            <section class="name_img__type_section">
                <input id="proposal_type" name="proposal_type" value="clase" hidden required>
                <div class="no_border">
                    <label for="clase_nombre">Nombre: </label>
                    <input id="clase_nombre" name="clase_nombre" type="text" placeholder="Nombre de la clase." required>
                </div>
                <div class="charFormImage no_border">
                    <div class="drop-area dropArea">
                        <span class="placeholder-text placeholder">Arrastra una imagen<br>o haz clic para
                            seleccionar</span>
                        <img class="preview" style="display: none;" />
                        <input type="file" class="imagen_form" name="clase_imagen" accept="image/*" />
                    </div>
                    <button type="reset" class="resetImage">Eliminar imagen</button>
                </div>
            </section>
            <section class="description_section no_border">
                <div class="no_border">
                    <h4 for="clase_descripcion">Descripción: </h4>
                    <textarea id="clase_descripcion" name="clase_descripcion" type="text"
                        placeholder="Breve descipción de la clase." required></textarea>
                </div>
            </section>
            <section class="extra_sec_1">
                <div class="no_border">
                    <label for="clase_hp_atr">Puntos de Salud<br>Atributo ref.: </label>
                    <select class="select_class_atr" id="clase_hp_atr" name="clase_hp_atr" required>
                        <?php
                        foreach ($atributos as $atributo) {
                            echo "<option value='" . $atributo["id"] . "' title='" . $atributo["descripcion"] . "'>" . $atributo["nombre"] . "</option>";
                        }
                        ?>
                    </select>
                    <label for="clase_hp_mod" title="Modificador salud"> Mod: </label>
                    <input class="input_mod_atr" id="clase_hp_mod" name="clase_hp_mod" type="number" min="1" step="1" value="1" required>
                </div>
                <div class="no_border">
                    <label for="clase_atq_atr">Puntos de Ataque<br>Atributo ref.: </label>
                    <select class="select_class_atr" id="clase_atq_atr" name="clase_atq_atr">
                        <?php
                        foreach ($atributos as $atributo) {
                            echo "<option value='" . $atributo["id"] . "' title='" . $atributo["descripcion"] . "'>" . $atributo["nombre"] . "</option>";
                        }
                        ?>
                    </select>
                    <label for="clase_atq_mod" title="Modificador ataque"> Mod: </label>
                    <input class="input_mod_atr" id="clase_atq_mod" name="clase_atq_mod" type="number" min="1" step="1" value="1" required>
                </div>
                <div class="no_border">
                    <label for="clase_def_atr">Puntos de Defensa<br>Atributo ref.: </label>
                    <select class="select_class_atr" id="clase_def_atr" name="clase_def_atr">
                        <?php
                        foreach ($atributos as $atributo) {
                            echo "<option value='" . $atributo["id"] . "' title='" . $atributo["descripcion"] . "'>" . $atributo["nombre"] . "</option>";
                        }
                        ?>
                    </select>
                    <label for="clase_def_mod" title="Modificador defensa"> Mod: </label>
                    <input class="input_mod_atr" id="clase_def_mod" name="clase_def_mod" type="number" min="1" step="1" value="1" required>
                </div>
                <div class="no_border">
                    <label for="clase_dado">Dado de golpe: </label>
                    <input title="Dado (nº caras) que utiliza la clase para sus lanzamientos de golpes." id="clase_dado"
                        name="clase_dado" type="number" min="1" step="1" value="6" required>
                </div>
            </section>
        </div>
        <div class="no_border">
            <div id="clase_habilidades_select" class="clase_habilidades_select_style double_col_grid no_border">
                <div>
                    <h4>Habilidades seleccionadas</h4>
                    <div id="clase_selected_habilities" class="no_border">
                    </div>
                </div>
                <div>
                    <h4>Habilidades disponibles</h4>
                    <div id="clase_avaliable_habilities" class="no_border">
                        <?php
                        if (!empty($habilidades)) {
                            foreach ($habilidades as $habilidad) {
                                $salida = "<div class='clase_habilidad' id='clase_habilidad_" . $habilidad["id"] . "'>
                                                            <p title='" . $habilidad["descripcion"] . "'>" . $habilidad["nombre"] . "</p>
                                                            <input type='number' class='hidden' name='clase_habilidad_" . $habilidad["id"] . "' value='" . $habilidad["id"] . "' disabled>
                                                        </div>";
                                echo $salida;
                            }
                        } else {
                            echo "<p>Error al conectar con la base de datos, por favor refresca la página.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <p>Equipo inicial: </p>
            <div id="class_items_selector">
                <div class="tarjeta_objeto selected">
                    <input value="1" name="clase_equipo" hidden>
                    <p>Sin equipación</p>
                </div>
                <?php
                foreach ($objetos as $objeto) {
                    echo "<div class='tooltip-container tarjeta_objeto'>
                                            <input value='" . $objeto[0]->getId() . "' hidden disabled>
                                            <p>" . $objeto[0]->getNombre() . "</p>
                                            <div class='tooltip-content'>
                                                " . print_r($objeto[1]) . "
                                            </div>
                                          </div>";
                }
                ?>
            </div>
        </div>
        <button class="btn_proponer" type="submit">PROPONER</button>
        <button class="btn_reset" type="reset">Limpiar formulario</button>
    </form>
    <details>
        <summary>Información:</summary>
        <p>Los modificadores de Puntos de Salud, Ataque y Defensa establecen cuantas veces</p>
        <p>se multiplican los puntos en el atributo de referencia para dicha propiedad.</p>
        <br>
        <p>Por ejemplo: si el "Ataque" de una Clase utiliza el atributo "Fuerza" con un modificador de "2"
        </p>
        <p>un personaje con 12 puntos de "Fuerza" tendrá un ataque base de: 12 x 2 = 24</p>
    </details>
</section>