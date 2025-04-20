<?php
//Control de acceso solo a usuarios con la sesion iniciada
if (!isset($_SESSION["usuario"])) {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: ../controllers/index.controller.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    //Añadimos el head de la página común al resto de páginas
    include_once '../views/shared/head.php';
    ?>
    <script src="../views/propuestas/scripts/visibility.js"></script>
</head>

<body>
    <?php
    //Añadimos la cabecera de la página comín al resto de páginas
    // y el menú de navegación
    include_once '../views/shared/header.php';
    ?>
    <main>
        <h2>Sección de propuestas</h2>
        <p>¡Aquí podrás proponer cualquier idea para el juego!</p>
        <p>Proponer:
            <select id="seleccionPropuesta">
                <option>--PROPONER--</option>
                <option id="divCla">Clase</option>
                <option id="divRaza">Raza</option>
            </select>
        </p>
        <section id="formularios">
            <div id="Clase" hidden>
                <h4>Proponer Clase</h4>
                <form action="../modules/propuestas.module.php">
                    <input id="proposal_type" value="clase" hidden>

                </form>
            </div>
            <div id="Raza" hidden>
                <h4>Proponer Raza</h4>
                <form action="../modules/propuestas.module.php">
                    <input id="proposal_type" value="raza" hidden>

                </form>
            </div>
        </section>
    </main>
    <?php
    //Añadimos el pie de página común al resto de páginas
    include '../views/shared/footer.html';
    ?>
</body>

</html>