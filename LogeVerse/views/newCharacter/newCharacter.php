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
    <script type="module" src="/LogeVerse/views/newCharacter/scripts/mainScript.js"></script>
    <link rel="stylesheet" href="/LogeVerse/views/newCharacter/styles/newCharacter.styles.css" />
</head>

<body>
    <?php
    //Añadimos la cabecera de la página comín al resto de páginas
    // y el menú de navegación
    include_once root_dir . 'LogeVerse/views/shared/header.php';
    ?>
    <main>
        <form action="<?php echo url_init ?>/LogeVerse/crearPersonaje" method="POST" enctype="multipart/form-data">
            <div id="newCharBasicInfo" class="double_col_grid no_border">
                <div class="no_border">
                    <section id="charFormName" class="no_border">
                        <label for="nombre">Nombre: </label>
                        <input id="nombre" name="nombre" type="text"
                            value="<?php echo isset($_SESSION["newChar"]) && isset($_SESSION["newChar"]["nombre"]) ? $_SESSION["newChar"]["nombre"] : null ?>"
                            placeholder="Drax el invisible" required>
                    </section>
                    <section id="charFormImage">
                        <div class="drop-area" id="dropArea">
                            <span class="placeholder-text" id="placeholder">Arrastra una imagen<br>o haz clic para
                                seleccionar</span>
                            <img id="preview" style="display: none;" />
                            <input type="file" id="imagen" name="imagen" accept="image/*" />
                        </div>
                        <button type="reset" id="resetImage">Eliminar imagen</button>
                    </section>
                </div>
                <section id="charFormHistory">
                    <label for="historia">Historia: </label>
                    <textarea id="historia" name="historia" rows="3" cols="30"
                        placeholder="Escribe una breve historia para desarrollar y contextualizar tu personaje."
                        required><?php echo isset($_SESSION["newChar"]) && isset($_SESSION["newChar"]["historia"]) ? $_SESSION["newChar"]["historia"] : null ?></textarea>
                </section>
            </div>
            <div id="selectoresMultiples" class="triple_col_grid no_border">
                <section id="charFormRace">
                    <h2>Raza</h2>
                    <div class="div_buttons_next_prev no_border">
                        <button type="button" id="prevRace">&lt;</button>
                        <div id="lista_clases" class="listado_newChar no_border">
                            <?php echo listarRazasSeleccion() ?>
                        </div>
                        <button type="button" id="nextRace">&gt;</button>
                    </div>
                </section>
                <section id="charFormClass">
                    <h2>Clase</h2>
                    <div class="div_buttons_next_prev no_border">
                        <button type="button" id="prevClass">&lt;</button>
                        <div id="lista_razas" class="listado_newChar no_border">
                            <?php echo listarClasesSeleccion() ?>
                        </div>
                        <button type="button" id="nextClass">&gt;</button>
                    </div>
                </section>
                <section id="charFormAtributes">
                    <h2>Atributos</h2>
                    <?php echo listarAtributosSeleccion() ?>
                </section>
                <button type="reset">Reiniciar</button>
                <button type="button" id="randomize">Aleatorio</button>
                <button type="submit">Crear</button>
        </form>
    </main>
    <?php
    //Añadimos el pie de página común al resto de páginas
    include_once root_dir . 'LogeVerse/views/shared/footer.html';
    ?>
</body>

</html>