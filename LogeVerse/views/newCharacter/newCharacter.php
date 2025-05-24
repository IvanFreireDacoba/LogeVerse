<?php
//Control de acceso solo a usuarios con la sesion iniciada
if (!isset($_SESSION["usuario"])) {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: LogeVerse/inicio");
    exit;
} else {

}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    //Añadimos el head de la página común al resto de páginas
    include_once 'LogeVerse/views/shared/head.php';
    ?>
    <script type="module" src="/LogeVerse/views/newCharacter/scripts/mainScript.js"></script>
    <link rel="stylesheet" href="/LogeVerse/views/newCharacter/styles/newCharacter.styles.css" />
</head>

<body>
    <?php
    //Añadimos la cabecera de la página comín al resto de páginas
    // y el menú de navegación
    include_once 'LogeVerse/views/shared/header.php';
    ?>
    <main>
        <form action="/LogeVerse/crearPersonaje" method="POST" enctype="multipart/form-data">
            <div id="newCharBasicInfo">
                <section id="charFormName">
                    <label for="nombre">Nombre: </label>
                    <input id="nombre" name="nombre" type="text"
                        value="<?php echo isset($_SESSION["newChar"]) && isset($_SESSION["newChar"]["nombre"]) ? $_SESSION["newChar"]["nombre"] : null ?>"
                        placeholder="Drax el invisible" required>
                </section>
                <section id="charFormHistory">
                    <label for="historia">Historia: </label>
                    <textarea id="historia" name="historia" rows="3" cols="30"
                        placeholder="Escribe una breve historia para desarrollar y contextualizar tu personaje."
                        required><?php echo isset($_SESSION["newChar"]) && isset($_SESSION["newChar"]["historia"]) ? $_SESSION["newChar"]["historia"] : null ?></textarea>
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
            <div id="selectoresMultiples">
                <section id="charFormRace">
                    <h3>Raza</h3>
                    <div>
                        <button type="button" id="prevRace"></button>
                        <div>
                            <?php echo listarRazasSeleccion() ?>
                        </div>
                        <button type="button" id="nextRace"></button>
                    </div>
                </section>
                <section id="charFormClass">
                    <h3>Clase</h3>
                    <div>
                        <button type="button" id="prevClass"></button>
                        <div>
                            <?php echo listarClasesSeleccion() ?>
                        </div>
                        <button type="button" id="nextClass"></button>
                    </div>
                </section>
                <section id="charFormAtributes">
                    <h3>Atributos</h3>
                    <?php echo listarAtributosSeleccion() ?>
                </section>
                <button type="reset">Reiniciar</button>
                <button type="button">Aleatorio</button>
                <button type="submit">Crear</button>
        </form>
    </main>
    <?php
    //Añadimos el pie de página común al resto de páginas
    include 'LogeVerse/views/shared/footer.html';
    ?>
</body>

</html>