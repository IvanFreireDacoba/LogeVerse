<nav>
    <ul>
        <li><a href="index.controller.php">Inicio</a></li>

        <?php
            //Botón de propuestas, visible solo si el usuario está logueado
            if(isset($_session["usuario"])) {
                echo '<li><a href="propuestas.controller.php">Propuestas</a></li>';
            }
        ?>

        <?php
            //Botón de inicio de sesión/registro/perfil
            if(isset($_session["usuario"])) {
                echo '<li><a href="profile.controller.php">' . $_session["username"] . '</a></li>';
            } else if(isset($title) && $title === "Login"){
                echo '<li><a href="register.controller.php">Registrarse</a></li>';
            } else {
                echo '<li><a href="login.controller.php">Login</a></li>';
            }
        ?>

    </ul>
</nav>