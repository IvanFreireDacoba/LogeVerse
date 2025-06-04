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
    include_once root_dir . 'LogeVerse/views/shared/head.php';
    ?>
    <script type="module" src="/LogeVerse/views/register/scripts/mainScript.js"></script>
</head>

<body>
    <?php
    //Añadimos la cabecera de la página comín al resto de páginas
    // y el menú de navegación
    include_once root_dir . 'LogeVerse/views/shared/header.php';
    ?>
    <main id="formulario_login">
        <form action="<?php echo url_init ?>/LogeVerse/registrar" method="POST" id="form_login" aria-labelledby="form_login_heading">
            <h2 id="form_login_heading">Registar usuario</h2>
            <div>
                <label for="username">Usuario</label>
                <input id="username" type="text" name="username" required placeholder="Ejemplo" aria-required="true"
                    maxlength="50" autocomplete="username">
            </div>
            <br>
            <div>
                <label for="email">Correo</label>
                <input id="email" type="email" name="email" required placeholder="Ej. usuario@correo.com"
                    aria-required="true" maxlength="75" autocomplete="email">
            </div>
            <br>
            <div>
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" required placeholder="Introduce tu contraseña"
                    aria-required="true">
            </div>
            <br>
            <div id="div_password_rep">
                <label for="password_rep">Repetir contraseña</label>
                <input type="password" name="password_rep" id="password_rep" required placeholder="Repite tu contraseña"
                    aria-required="true">
            </div>
            <p><input id="cb_password" type="checkbox"> Mostrar contraseña</p>
            <br>
            <button type="submit" id="btn_submit" aria-label="Enviar formulario para registrarse">
                Registrarse</button>
            <button button id="btn_login" onclick="location.href='<?php echo url_init ?>/LogeVerse/login'"
                aria-label="Ir a la página de login">
                ¿Ya estás registrado?</button>
        </form>
    </main>
    <?php
    //Añadimos el pie de página común al resto de páginas
    include_once root_dir . 'LogeVerse/views/shared/footer.html';
    ?>
</body>

</html>