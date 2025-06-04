<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("Location: " . url_init . "/LogeVerse/inicio");
    exit;
}
?>
<section id="Habilidad" class="propuesta" hidden>
    <h4>Proponer Habilidad</h4>
    <form action="<?php echo url_init ?>/LogeVerse/proponer" method="POST">
        <input id="proposal_type" name="proposal_type" value="habilidad" hidden required>
        <div>
            <label for="habilidad_nombre">Nombre: </label>
            <input id="habilidad_nombre" name="habilidad_nombre" type="text" placeholder="Nombre de la habilidad."
                required>
        </div>
        <div>
            <label for="habilidad_descripcion">Descripción: </label>
            <textarea id="habilidad_descripcion" name="habilidad_descripcion" type="text"
                placeholder="Breve descipción de la habilidad." required></textarea>
        </div>
        <div>
            <label for="habilidad_tipo">Tipo: </label>
            <select id="habilidad_tipo" name="habilidad_tipo" required>
                <option value="fisico">Físico</option>
                <option value="magico">Mágico</option>
                <option value="estado">Estado</option>
                <option value="campo">Campo</option>
                <option value="aux">Auxiliar</option>
                <option value="otro">Otro</option>
            </select>
            <details>
                <summary>Contexto</summary>
                <ul>
                    <li>
                        <h4>Físico</h4>
                        <p>Habilidades de carácter físico: golpes, embestidas...</p>
                    </li>
                    <li>
                        <h4>Mágico</h4>
                        <p>Habilidades de carácter mágico: hechizos, invocaciones...</p>
                    </li>
                    <li>
                        <h4>Estado</h4>
                        <p>Habilidades de estado: quemaduras, parálisis, envenenamiento...</p>
                    </li>
                    <li>
                        <h4>Campo</h4>
                        <p>Habilidades de efectos sobre el entorno: clima, naturaleza...</p>
                    </li>
                    <li>
                        <h4>Auxiliar</h4>
                        <p>Habilidades de ayuda: potenciadores y debuffs.</p>
                    </li>
                    <li>
                        <h4>Otro</h4>
                        <p>Habilidades que no encajan con el resto de tipos.</p>
                    </li>
                </ul>
            </details>
        </div>
        <div>
            <label for="habilidad_nivel">Nivel: </label>
            <input title="Nivel necesario para utilizar la habilidad." id="habilidad_nivel" name="habilidad_nivel"
                type="number" required>
        </div>
        <div id="checkbox_habilidad_efectos">
            <label for="has_effects">Efectos </label>
            <input id="has_effects" name="has_effects" type="checkbox">
        </div>
        <div id="habilidad_efectos_select" class="habilidad_efectos_select_style" hidden>
            <div>
                <h4>Efectos seleccionados</h4>
                <div id="habilidad_selected_efects">
                </div>
            </div>
            <div>
                <h4>Efectos disponibles</h4>
                <div id="habilidad_avaliable_efects">
                    <?php
                    if (!empty($efectos)) {
                        foreach ($efectos as $efecto) {
                            $salida = "<div class='habilidad_efecto' id='habilidad_efecto_" . $efecto["id"] . "'>
                                                    <p>" . $efecto["nombre"] . ": [duración: ";
                            $salida .= $efecto["duracion"] === 0 ? "Instanáneo" : $efecto["duracion"];
                            $salida .= " || cantidad : " . $efecto["cantidad"] . "]</p>
                                                <p>" . $efecto["descripcion"] . "</p>
                                                <input type='number' name='habilidad_efecto_" . $efecto["id"] . "' value='" . $efecto["id"] . "' hidden disabled>
                                                <div class='div_modificador' title='Modificador del efecto -> la cantidad del efecto se multiplicará por este modificador.' hidden>
                                                    <label for='mod_habilidad_efecto_" . $efecto["id"] . "'>Mod. </label>
                                                    <input class='modificador' id='mod_habilidad_efecto_" . $efecto["id"] . "' name='mod_habilidad_efecto_" . $efecto["id"] . "' type='number' min='1' value='1' hidden disabled>
                                                </div>
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
        <button class="btn_proponer" type="submit">PROPONER</button>
        <button class="btn_reset" type="reset">Limpiar formulario</button>
    </form>
</section>