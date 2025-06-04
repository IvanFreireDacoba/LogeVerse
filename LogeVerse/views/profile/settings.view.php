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
<?php
//Añadimos el head de la página común al resto de páginas
include_once root_dir . 'LogeVerse/views/shared/head.php';
?>
<script src="/LogeVerse/views/profile/scripts/drop_user_confirmation.js"></script>
</head>

<body>
    <?php
    //Añadimos la cabecera de la página comín al resto de páginas
    // y el menú de navegación
    include_once root_dir . 'LogeVerse/views/shared/header.php';
    ?>
    <main id="profile_body">
        <h1 id="profile_title">Perfil de usuario</h1>
        <div id="profile_info">
            <p><strong>Nombre:</strong> <?php echo $_SESSION["usuario"]->getNombre(); ?></p>
            <p><strong>Correo:</strong> <?php echo $_SESSION["usuario"]->getCorreo(); ?></p>
        </div>
        <hr>
        <h2 id="profile_title">Configuración de la cuenta</h2>
        <section id="profile_settings">
            <div id="users">
                <form action="<?php url_init ?>/LogeVerse/cambiarPerfil" method="POST">
                    <div>
                        <label for="name">Nombre:</label>
                        <input type="text" id="name" name="name"
                            value="<?php echo $_SESSION["usuario"]->getNombre(); ?>" required>
                    </div>
                    <div>
                        <label for="email">Correo:</label>
                        <input type="email" id="email" name="email"
                            value="<?php echo $_SESSION["usuario"]->getCorreo(); ?>" required>
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
                <form action="<?php echo url_init ?>/LogeVerse/eliminarPerfil" method="POST">
                    <input id="confirmation" name="confirmation" value=""
                        data_username="<?php echo htmlspecialchars(strtoupper($_SESSION["usuario"]->getNombre())); ?>"
                        hidden>
                    <button id="remove_btn" type="submit">ELIMINAR CUENTA</button>
                </form>
            </div>
            <?php if ($_SESSION["usuario"]->getAdmin()) {
                if (checkAdmin($_SESSION["usuario"]->getId())) {
                    echo '<div>
                            <button onclick="' . "window.location.href='/LogeVerse/portalAdmin'" . '">Portal Admin</button>

                            <script src="admin_debugger.js"></script>
                            <ce-debugger status="<?php echo isset($_SESSION["debug"]) && $_SESSION["debug"] ? "on" : "off" ?></ce-debugger>
                            <form id="debug_form" action="" method="POST" hidden>
                                <input id="debug" name="debug" value="<?php echo isset($_SESSION["debug"]) && $_SESSION["debug"] ? "on" : "off" ?>">
                            </form>
                                    
                          </div>';
                }
            } ?>
        </section>
    </main>
    <?php
    //Añadimos el pie de página común al resto de páginas
    include_once root_dir . 'LogeVerse/views/shared/footer.html';
    ?>
</body>

</html>