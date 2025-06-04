<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("Location: " . url_init . "/LogeVerse/inicio");
    exit;
}
?>
<section id="Objeto" class="propuesta" hidden>
    <h4>Proponer Objeto</h4>
    <p>No disponible</p>
    <form action="<?php echo url_init ?>/LogeVerse/proponer" method="POST" enctype="multipart/form-data" hidden>
        <input id="proposal_type" name="proposal_type" value="objeto" hidden required>
        <div>
            <label for="objeto_nombre">Nombre: </label>
            <input id="objeto_nombre" name="objeto_nombre" type="text" placeholder="Nombre de la objeto." required>
        </div>
        <div>
            <label for="objeto_tipo">Tipo: </label>
            <select id="objeto_tipo" name="objeto_tipo" required>
                <option value="arma" title="Objetos para el combate">Arma</option>
                <option value="armadura" title="Objetos equipables">Armadura</option>
                <option value="base" title="Objetos base">Base</option>
                <option value="consumible" title="Objetos consumibles">Consumible</option>
                <option value="paquete" title="Conjunto de objetos">Paquete</option>
            </select>
        </div>
        <div class="charFormImage">
            <div class="drop-area" class="dropArea">
                <span class="placeholder-text placeholder">Arrastra una imagen<br>o haz clic para
                    seleccionar</span>
                <img class="preview" style="display: none;" />
                <input type="file" class="imagen_form" name="objeto_imagen" accept="image/*" />
            </div>
            <button type="reset" class="resetImage">Eliminar imagen</button>
        </div>
        <div>
            <label for="objeto_descripcion">Descripción: </label>
            <textarea id="objeto_descripcion" name="objeto_descripcion" type="text"
                placeholder="Breve descipción de la objeto." required></textarea>
        </div>
        <di>
            <label for="objeto_precio">Precio: </label>
            <input id="objeto_precio" name="objeto_precio" type="number" min="0" value="1" step="1">
            <a> oro</a>
        </di>
        <div id="checkbox_objeto_efectos">
            <label for="has_effects">Efectos </label>
            <input id="has_effects" name="has_effects" type="checkbox">
        </div>
        <div id="tipo_objeto">
            <div id="arma">

            </div>
            <div id="armadura" hidden>

            </div>
            <div id="base">

            </div>
            <div id="consumible">

            </div>
            <div id="paquete">

            </div>
        </div>
        <div id="objeto_efectos_select" class="objeto_efectos_select_style" hidden>
            <div>
                <h4>Efectos seleccionados</h4>
                <div id="objeto_selected_efects">
                </div>
            </div>
            <div>
                <h4>Efectos disponibles</h4>
                <div id="objeto_avaliable_efects">
                    <?php
                    if (!empty($efectos)) {
                        foreach ($efectos as $efecto) {
                            $salida = "<div class='objeto_efecto' id='objeto_efecto_" . $efecto["id"] . "'>
                                                    <p>" . $efecto["nombre"] . ": [duración: ";
                            $salida .= $efecto["duracion"] === 0 ? "Instanáneo" : $efecto["duracion"];
                            $salida .= " || cantidad : " . $efecto["cantidad"] . "]</p>
                                                <p>" . $efecto["descripcion"] . "</p>
                                                <input type='number' name='objeto_efecto_" . $efecto["id"] . "' value='" . $efecto["id"] . "' hidden disabled>
                                                <div class='div_modificador' title='Modificador del efecto -> la cantidad del efecto se multiplicará por este modificador.' hidden>
                                                    <label for='mod_objeto_efecto_" . $efecto["id"] . "'>Mod. </label>
                                                    <input class='modificador' id='mod_objeto_efecto_" . $efecto["id"] . "' name='mod_objeto_efecto_" . $efecto["id"] . "' type='number' min='1' value='1' hidden disabled>
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
    </form>
</section>