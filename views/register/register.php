<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    include_once '../views/shared/head.php';
    ?>
</head>

<body>
    <?php
    //Añadimos la cabecera de la página comín al resto de páginas
    // y el menú de navegación
    include_once '../views/shared/header.php';
    ?>
    <main id="formulario_login">
        <form action="../modules/register.module.php" method="POST" id="form_login" aria-labelledby="form_login_heading">
            <h2 id="form_login_heading">Registar usuario</h2>
            <div>
                <label for="username">Usuario</label>
                <input id="username" type="text" name="username" required
                    placeholder="Ejemplo" aria-required="true">
            </div>
            <br>
            <div>
                <label for="email">Correo</label>
                <input id="email" type="email" name="email" required
                    placeholder="Ej. usuario@correo.com" aria-required="true">
            </div>
            <br>
            <div>
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" required
                    placeholder="Introduce tu contraseña" aria-required="true">
            </div>
            <br>
            <div>
                <label for="password_rep">Repetir contraseña</label>
                <input type="password" name="password_rep" id="password_rep" required
                    placeholder="Repite tu contraseña" aria-required="true">
            </div>
            <br>
            <button type="submit" aria-label="Enviar formulario para registrarse">
                Registrarse</button>
            <button type="" button id="btn_login" onclick="location.href='login.controller.php'"
                aria-label="Ir a la página de login">
                ¿Ya estás registrado?</button>
        </form>
    </main>
    <?php
    //Añadimos el pie de página común al resto de páginas
    include '../views/shared/footer.html';
    ?>
</body>

</html>