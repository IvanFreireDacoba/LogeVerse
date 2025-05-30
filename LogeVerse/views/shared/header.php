<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("/LogeVerse/inicio");
    exit;
}
?>
<nav>
    <ul>
        <li><a href="index.controller.php">Inicio</a></li>

        <?php
            //Botón de propuestas, visible solo si el usuario está logueado
            if(isset($_SESSION["usuario"])) {
                echo '<li><a href="/LogeVerse/propuestas">Propuestas</a></li>';
            }
        ?>

        <?php
            //Botón de inicio de sesión/registro/perfil
            if(isset($_SESSION["usuario"])) {
                if($title === "Perfil"){
                    echo '<li><a href="/LogeVerse/cerrarSesion">Cerrar Sesión</a></li>';
                } else {
                    echo '<li><a href="/LogeVerse/perfil">' . $_SESSION["usuario"]->getNombre() . '</a></li>';
                }
            } else if(isset($title) && $title === "Login"){
                echo '<li><a href="/LogeVerse/registrarse">Registrarse</a></li>';
            } else {
                echo '<li><a href="/LogeVerse/login">Login</a></li>';
            }
        ?>
    </ul>
</nav>