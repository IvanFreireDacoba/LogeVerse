<section id="Clase" class="propuesta" hidden>
    <h4>Proponer Clase</h4>
    <form action="/LogeVerse/proponer" method="POST" enctype="multipart/form-data">
        <div class="triple_col_grid">
            <section class="name_img__type_section">
                <input id="proposal_type" name="proposal_type" value="clase" hidden required>
                <div>
                    <label for="clase_nombre">Nombre: </label>
                    <input id="clase_nombre" name="clase_nombre" type="text" placeholder="Nombre de la clase." required>
                </div>
                <div class="charFormImage">
                    <div class="drop-area dropArea">
                        <span class="placeholder-text placeholder">Arrastra una imagen<br>o haz clic para
                            seleccionar</span>
                        <img class="preview" style="display: none;" />
                        <input type="file" class="imagen_form" name="clase_imagen" accept="image/*" />
                    </div>
                    <button type="reset" class="resetImage">Eliminar imagen</button>
                </div>
            </section>
            <section class="descrition_section">
                <div>
                    <h4 for="clase_descripcion">Descripción: </h4>
                    <textarea id="clase_descripcion" name="clase_descripcion" type="text"
                        placeholder="Breve descipción de la clase." required></textarea>
                </div>
                <div>
                    <label for="clase_dado">Dado de golpe: </label>
                    <input title="Dado (nº caras) que utiliza la clase para sus lanzamientos de golpes." id="clase_dado"
                        name="clase_dado" type="number" min="1" step="1" value="6" required>
                </div>
            </section>
            <section class="extra_sec_1">
                <div>
                    <label for="clase_hp_atr">Puntos de Salud => Atributo ref.: </label>
                    <select id="clase_hp_atr" name="clase_hp_atr" required>
                        <?php
                        foreach ($atributos as $atributo) {
                            echo "<option value='" . $atributo["id"] . "' title='" . $atributo["descripcion"] . "'>" . $atributo["nombre"] . "</option>";
                        }
                        ?>
                    </select>
                    <label for="clase_hp_mod"> || Modificador: </label>
                    <input id="clase_hp_mod" name="clase_hp_mod" type="number" min="1" step="1" value="1" required>
                </div>
                <div>
                    <label for="clase_atq_atr">Puntos de Ataque => Atributo ref.: </label>
                    <select id="clase_atq_atr" name="clase_atq_atr">
                        <?php
                        foreach ($atributos as $atributo) {
                            echo "<option value='" . $atributo["id"] . "' title='" . $atributo["descripcion"] . "'>" . $atributo["nombre"] . "</option>";
                        }
                        ?>
                    </select>
                    <label for="clase_atq_mod"> || Modificador: </label>
                    <input id="clase_atq_mod" name="clase_atq_mod" type="number" min="1" step="1" value="1" required>
                </div>
                <div>
                    <label for="clase_def_atr">Puntos de Defensa => Atributo ref.: </label>
                    <select id="clase_def_atr">
                        <?php
                        foreach ($atributos as $atributo) {
                            echo "<option value='" . $atributo["id"] . "' title='" . $atributo["descripcion"] . "'>" . $atributo["nombre"] . "</option>";
                        }
                        ?>
                    </select>
                    <label for="clase_def_mod"> || Modificador: </label>
                    <input id="clase_def_mod" name="clase_def_mod" type="number" min="1" step="1" value="1" required>
                </div>
            </section>
        </div>
        <div class="double_col_grid">
            <div id="clase_habilidades_select" class="clase_habilidades_select_style, double_col_grid">
                <div>
                    <h4>Habilidades seleccionadas</h4>
                    <div id="clase_selected_habilities">
                    </div>
                </div>
                <div>
                    <h4>Habilidades disponibles</h4>
                    <div id="clase_avaliable_habilities">
                        <?php
                        if (!empty($habilidades)) {
                            foreach ($habilidades as $habilidad) {
                                $salida = "<div class='clase_habilidad' id='clase_habilidad_" . $habilidad["id"] . "'>
                                                            <p>" . $habilidad["nombre"] . "</p>
                                                            <p>" . $habilidad["descripcion"] . "</p>
                                                            <input type='number' name='clase_habilidad_" . $habilidad["id"] . "' value='" . $habilidad["id"] . "' disabled>
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
            <div>
                <label for="clase_equipo">Equipo inicial: </label>
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