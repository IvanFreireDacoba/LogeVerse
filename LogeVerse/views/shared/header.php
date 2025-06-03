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
        <li>
            <div><a href="/LogeVerse/inicio">Inicio</a></div>
        </li>

        <?php
        //Botón de propuestas, visible solo si el usuario está logueado
        if (isset($_SESSION["usuario"])) {
            echo '<li><div><a href="/LogeVerse/propuestas">Propuestas</a></div></li>';
        }
        ?>

        <?php
        //Botón de inicio de sesión/registro/perfil
        if (isset($_SESSION["usuario"])) {
            if ($title === "Perfil") {
                echo '<li><div><a href="/LogeVerse/cerrarSesion">Cerrar Sesión</a></div></li>';
            } else {
                echo '<li><div><a href="/LogeVerse/perfil">' . $_SESSION["usuario"]->getNombre() . '</a></div></li>';
            }
        } else if (isset($title) && $title === "Login") {
            echo '<li><div><a href="/LogeVerse/registrarse">Registrarse</a></li>';
        } else {
            echo '<li><div><a href="/LogeVerse/login">Login</a></div></li>';
        }
        ?>
    </ul>
</nav>