<!DOCTYPE html>
<html lang="es">

<head>
    <?php
        //Incluir el head común (meta, title)
        include 'views/shared/head.php';
    ?>
    <link rel="icon" href="./resources/shared/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="./views/index/styles/index.css">
</head>

<body>
    <?php
        //Incluir el header común (nav)
        include 'views/shared/head.php';
    ?>
    <h1>TFC - DnD_GM_WebManager</h1>
    <h2>Diseñado por: Iván Freire Dacoba</h2>
    <hr>
    <main>
        <div>
            <p>Índex Temporal -> Producción</p>
            <a href="login.controller.php">Login</a>
            <br>
            <a href="register.controller.php">Register</a>
            <br>
            <a href="profile.controller.php">Perfil</a>
        </div>
    </main>
    <?php
        //Incluir el footer común (nav)
        /* añadir LogeVerse/ para la migración */
        include 'views/shared/footer.html';
    ?>
</body>

</html>