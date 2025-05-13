<?php
//Control de acceso solo a usuarios con la sesion iniciada
if (!isset($_SESSION["usuario"])) {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: ../controllers/index.controller.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    //Añadimos el head de la página común al resto de páginas
    include_once '../views/shared/head.php';
    ?>
</head>

<body>
    <?php
    //Añadimos la cabecera de la página comín al resto de páginas
    // y el menú de navegación
    include_once '../views/shared/header.php';
    ?>
    <main id="profile_body">
        <h1 id="profile_title">Perfil de usuario</h1>
        <div id="profile_info">
            <p><strong>Nombre:</strong> <?php echo $_SESSION["usuario"]->getNombre(); ?></p>
            <p><strong>Correo:</strong> <?php echo $_SESSION["usuario"]->getCorreo(); ?></p>
        </div>
        <hr>
        <h2 id="profile_title">Configuración de la cuenta</h2>
        <div id="profile_settings">
            <form action="../modules/settings.module.php" method="POST">
                <div>
                    <label for="name">Nombre:</label>
                    <input type="text" id="name" name="name" value="<?php echo $_SESSION["usuario"]->getNombre(); ?>" required>
                </div>
                <div>
                    <label for="email">Correo:</label>
                    <input type="email" id="email" name="email" value="<?php echo $_SESSION["usuario"]->getCorreo(); ?>" required>
                </div>
                <div>
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password">
                    <br>
                    <label for="password_confirm">Confirmar contraseña:</label>
                    <input type="password" id="password_confirm" name="password_confirm">
                </div>
                <button>Actualizar</button>
            </form>
            <br>
            <button id="remove_btn">ELIMINAR CUENTA</button>
        </div>
    </main>
    <?php
    //Añadimos el pie de página común al resto de páginas
    include '../views/shared/footer.html';
    ?>
</body>

</html>

