<?php
//Control de acceso solo a usuarios con la sesion iniciada
if (!isset($_SESSION["usuario"])) {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: LogeVerse/inicio");
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
    <script type="module" src="/LogeVerse/views/propuestas/scripts/mainScript.js"></script>
    <link rel="stylesheet" href="/LogeVerse/views/propuestas/styles/propuestas.styles.css">
</head>

<body>
    <?php
    //Añadimos la cabecera de la página comín al resto de páginas
    // y el menú de navegación
    include_once 'LogeVerse/views/shared/header.php';
    ?>
    <main>
        <h2>Sección de propuesta</h2>
        <p>¡Aquí podrás proponer cualquier idea para el juego!</p>
        <p>Proponer:
            <select id="seleccionPropuesta">
                <option selected hidden disabled>--SELECCIÓN--</option>
                <option id="divClase">Clase</option>
                <option id="divRaza">Raza</option>
                <option id="divEfecto">Efecto</option>
                <option id="divPasiva">Pasiva</option>
                <option id="divHabilidad">Habilidad</option>
                <option id="divObjeto">Objeto</option>
                <option id="divIdioma">Idioma</option>
            </select>
        </p>
        <section id="formularios">
            <?php
            include_once 'LogeVerse/views/propuestas/sections/clase.php';
            include_once 'LogeVerse/views/propuestas/sections/raza.php';
            include_once 'LogeVerse/views/propuestas/sections/efecto.php';
            include_once 'LogeVerse/views/propuestas/sections/pasiva.php';
            include_once 'LogeVerse/views/propuestas/sections/habilidad.php';
            include_once 'LogeVerse/views/propuestas/sections/objeto.php';
            include_once 'LogeVerse/views/propuestas/sections/idioma.php';
            ?>
        </section>
    </main>
    <?php
    //Añadimos el pie de página común al resto de páginas
    include 'LogeVerse/views/shared/footer.html';
    ?>
</body>

</html>