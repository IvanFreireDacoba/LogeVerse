<!DOCTYPE html>
<html lang="es">

<head>
    <?php
        //Incluir el head común (meta, title)
        include 'LogeVerse/views/shared/head.php';
    ?>
    <link rel="icon" href="/LogeVerse/resources/shared/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="./views/index/styles/index.css">
</head>

<body>
    <?php
        //Incluir el header común (nav)
        include 'LogeVerse/views/shared/header.php';
    ?>
    <h1>TFC - DnD_GM_WebManager</h1>
    <h2>Diseñado por: Iván Freire Dacoba</h2>
    <hr>
    <main>
        <div>
            <p>Índex Temporal -> Producción</p>
            <a href="/LogeVerse/login">Login</a>
            <br>
            <a href="/LogeVerse/registrarse">Register</a>
            <br>
            <a href="/LogeVerse/perfil">Perfil</a>
        </div>
    </main>
    <?php
        //Incluir el footer común (nav)
        include 'LogeVerse/views/shared/footer.html';
    ?>
</body>

</html>