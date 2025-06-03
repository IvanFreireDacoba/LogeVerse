<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("/LogeVerse/inicio");
    exit;
}
?>
<section id="Pasiva" class="propuesta" hidden>
    <h4>Proponer Pasiva</h4>
    <form action="/LogeVerse/proponer" method="POST">
        <input id="proposal_type" name="proposal_type" value="pasiva" hidden required>
        <div>
            <label for="pasiva_nombre">Nombre: </label>
            <input id="pasiva_nombre" name="pasiva_nombre" type="text" placeholder="Nombre de la pasiva." required>
        </div>
        <div>
            <label for="pasiva_descripcion">Descripción: </label>
            <textarea id="pasiva_descripcion" name="pasiva_descripcion" type="text"
                placeholder="Breve descipción de la pasiva." required></textarea>
        </div>
        <div id="checkbox_pasiva_efectos">
            <label for="has_effects">Efectos </label>
            <input id="has_effects" name="has_effects" type="checkbox">
        </div>
        <div id="pasiva_efectos_select" class="pasiva_efectos_select_style" hidden>
            <div>
                <h4>Efectos seleccionados</h4>
                <div id="pasiva_selected_efects">
                </div>
            </div>
            <div>
                <h4>Efectos disponibles</h4>
                <div id="pasiva_avaliable_efects">
                    <?php
                    try {
                        foreach ($efectos as $efecto) {
                            $salida = "<div class='pasiva_efecto' id='pasiva_efecto_" . $efecto["id"] . "'>
                                                    <p>" . $efecto["nombre"] . ": [duración: ";
                            $salida .= $efecto["duracion"] === 0 ? "Instanáneo" : $efecto["duracion"];
                            $salida .= " || cantidad : " . $efecto["cantidad"] . "]</p>
                                                <p>" . $efecto["descripcion"] . "</p>
                                                <input type='number' name='pasiva_efecto_" . $efecto["id"] . "' value='" . $efecto["id"] . "' hidden disabled>
                                                <div class='div_modificador' title='Modificador del efecto -> la cantidad del efecto se multiplicará por este modificador.' hidden>
                                                    <label for='mod_pasiva_efecto_" . $efecto["id"] . "'>Mod. </label>
                                                    <input class='modificador' id='mod_pasiva_efecto_" . $efecto["id"] . "' name='mod_pasiva_efecto_" . $efecto["id"] . "' type='number' min='1' value='1' hidden disabled>
                                                </div>
                                               </div>";
                            echo $salida;
                        }
                    } catch (Error $e) {
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