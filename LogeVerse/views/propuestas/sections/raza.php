<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("Location: " . url_init . "/LogeVerse/inicio");
    exit;
}
?>
<section id="Raza" class="propuesta" hidden>
    <h4>Proponer Raza</h4>
    <form action="<?php echo url_init ?>/LogeVerse/proponer" method="POST">
        <div class="triple_col_grid">
            <div>
                <input id="proposal_type" name="proposal_type" value="raza" hidden required>
                <div>
                    <label for="raza_nombre">Nombre: </label>
                    <input id="raza_nombre" name="raza_nombre" type="text" placeholder="Nombre de la raza." required>
                </div>
                <div class="charFormImage">
                    <div class="drop-area dropArea">
                        <span class="placeholder-text" class="placeholder">Arrastra una imagen<br>o haz clic para
                            seleccionar</span>
                        <img class="preview" style="display: none;" />
                        <input type="file" class="imagen_form" name="raza_imagen" accept="image/*" />
                    </div>
                    <button type="reset" class="resetImage">Eliminar imagen</button>
                </div>
            </div>
            <div>
                <label for="raza_descripcion">Descripción: </label>
                <textarea id="raza_descripcion" name="raza_descripcion" type="text"
                    placeholder="Breve descipción física de la raza." required></textarea>
            </div>
            <div>
                <label for="raza_historia">Historia: </label>
                <textarea id="raza_historia" name="raza_historia" type="text"
                    placeholder="Contexto histórico de la raza." required></textarea>
            </div>
        </div>
        <div>
            <label for="raza_velocidad">Velocidad: </label>
            <input id="raza_velocidad" name="raza_velocidad" type="number" min="1" step="1" value="1">
            <a id="raza_velocidad_descripción" class="slow"> EXTREMEDAMENTE LENTA</a>
        </div>
        <div id="raza_extra" class="triple_col_grid">
            <div>
                <table>
                    <thead>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                    </thead>
                    <tbody id="atr_raza_tbody">
                        <?php
                        foreach ($atributos as $atributo) {
                            echo "<tr>
                                              <td>" . $atributo["nombre"] . "</td>";
                            echo "<td><input type='number' name='raza_atr_" . $atributo["id"] . "' min='0' step='1' value='3' required></td>
                                             </tr>";
                        }
                        ?>
                        <tr id="fila_puntos_raza">
                            <th>Total: </th>
                            <th id="total_puntos_raza">18</th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="div_raza_pasivas double_col_grid">
                <div>
                    <h4>Pasivas seleccionadas</h4>
                    <div id="race_selected_pasives">
                    </div>
                </div>
                <div>
                    <h4>Pasivas disponibles</h4>
                    <div id="race_avaliable_pasives">
                        <?php
                        if (!empty($pasivas)) {
                            foreach ($pasivas as $pasiva) {
                                $salida = "<div class='raza_pasiva' id='raza_pasiva_" . $pasiva["id"] . "' id_pasiva='" . $pasiva["id"] . "'>
                                                        <p title='" . $pasiva["descripcion"] . "'>" . $pasiva["nombre"] . "</p>
                                                        <input type='number' name='raza_pasiva_" . $pasiva["id"] . "' value='" . $pasiva["id"] . "' hidden disabled>
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
                <h4>Idiomas:</h4>
                <?php
                if (!empty($idiomas)) {
                    foreach ($idiomas as $idioma) {
                        $salida = "<div class='raza_idioma' id='raza_idioma_" . $idioma["id"] . "' id_pasiva='" . $idioma["id_pasiva"] . "'>
                                                        <p title='" . $idioma["descripcion"] . "'>" . $idioma["nombre"] . "</p>
                                                        <input type='number' name='raza_idioma_" . $idioma["id"] . "' value='" . $idioma["id"] . "' hidden disabled>
                                                   </div>";
                        echo $salida;
                    }
                } else {
                    echo "<p>Error al conectar con la base de datos, por favor refresca la página.</p>";
                }
                ?>
            </div>
        </div>
        <button class="btn_proponer" type="submit">PROPONER</button>
        <button class="btn_reset" type="reset">Limpiar formulario</button>
    </form>
</section>