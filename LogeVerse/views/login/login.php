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
    include_once root_dir . "LogeVerse/views/shared/head.php";
    ?>
    <script type="module" src="/LogeVerse/views/login/scripts/mainScript.js"></script>
    <link rel="stylesheet" type="text/css" href="/LogeVerse/views/login/styles/login.style.css">
</head>

<body>
    <?php
    //Añadimos la cabecera de la página comín al resto de páginas
    // y el menú de navegación
    include_once root_dir . "LogeVerse/views/shared/header.php";
    ?>
    <main id="formulario_login" class="center_text">
        <form action="<?php echo url_init ?>/LogeVerse/logear" method="POST" id="form_login" aria-labelledby="form_login_heading">
            <h2 id="form_login_heading">Iniciar sesión</h2>
            <div class="no_border">
                <label for="username">Usuario / Correo</label>
                <br>
                <input id="username" type="text" name="username" required autocomplete="username"
                    placeholder="Ej. usuario@correo.com" aria-required="true">
            </div>
            <br>
            <div class="no_border">
                <label for="password">Contraseña</label>
                <br>
                <input type="password" name="password" id="password" required autocomplete="current-password"
                    placeholder="Introduce tu contraseña" aria-required="true">
                <p><input type="checkbox" id="cb_password"> Mostrar contraseña</p>
            </div>
            <br>
            <button type="submit" id="btn_login" aria-label="Enviar formulario para iniciar sesión" disabled>
                Iniciar sesión</button>
            <button type="" button id="btn_registro" onclick="location.href='<?php echo url_init ?>/LogeVerse/registrarse'"
                aria-label="Ir a la página de registro">
                ¿Necesitas registrarte?</button>
        </form>
    </main>
    <?php
    //Añadimos el pie de página común al resto de páginas
    include_once root_dir . "LogeVerse/views/shared/footer.html";
    ?>
</body>

</html>