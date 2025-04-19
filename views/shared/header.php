<nav>
    <ul>
        <li><a href="../../controllers/index.controller.php">Inicio</a></li>

        <?php
            if(isset($_session["username"])) {
                echo '<li><a href="profile.controller.php">' . $_session["username"] . '</a></li>';
            } else if(isset($title) && $title === "Login"){
                echo '<li><a href="register.controller.php">Registrarse</a></li>';
            } else {
                echo '<li><a href="login.controller.php">Login</a></li>';
            }
        ?>

    </ul>
</nav>