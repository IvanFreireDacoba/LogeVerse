<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    //Añadimos el head de la página común al resto de páginas
    include_once 'LogeVerse/views/shared/head.php';
    ?>
</head>

<body>
    <?php
    //Añadimos la cabecera de la página comín al resto de páginas
    // y el menú de navegación
    include_once 'LogeVerse/views/shared/header.php';
    ?>
    <main id="formulario_login">
        <form action="/LogeVerse/logear" method="POST" id="form_login" aria-labelledby="form_login_heading">
            <h2 id="form_login_heading">Iniciar sesión</h2>
            <div>
                <label for="username">Usuario/Correo</label>
                <input id="username" type="text" name="username" required autocomplete="username"
                    placeholder="Ej. usuario@correo.com" aria-required="true">
            </div>
            <br>
            <div>
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" required autocomplete="current-password"
                    placeholder="Introduce tu contraseña" aria-required="true">
            </div>
            <br>
            <button type="submit" aria-label="Enviar formulario para iniciar sesión">
                Iniciar sesión</button>
            <button type="" button id="btn_registro" onclick="location.href='register.controller.php'"
                aria-label="Ir a la página de registro">
                ¿Necesitas registrarte?</button>
        </form>
    </main>
    <?php
    //Añadimos el pie de página común al resto de páginas
    include 'LogeVerse/views/shared/footer.html';
    ?>
</body>

</html>