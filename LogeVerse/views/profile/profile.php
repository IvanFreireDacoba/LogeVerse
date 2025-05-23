<?php
//Control de acceso solo a usuarios con la sesion iniciada
if (!isset($_SESSION["usuario"])) {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: LogeVerse/inicio");
    exit;
}
?>

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
    <main id="profile_body">
        <h1 id="profile_title">Perfil de usuario</h1>
        <div id="profile_info">
            <p><strong>Nombre:</strong> <?php echo $_SESSION["usuario"]->getNombre(); ?></p>
            <p><strong>Correo:</strong> <?php echo $_SESSION["usuario"]->getCorreo(); ?></p>
        </div>
        <button id="btn_settings_profile" onclick="window.location.href='./settings.controller.php'">Ajustes</button>
        <hr>
        <section id="profile_characters">
            <?php
            //Expositor de personajes
            $personajes = $_SESSION["usuario"]->getPersonajes();
            if (!empty($personajes)) {
                foreach ($personajes as $personaje) {
                    echo $personaje->toShortHTML();
                }
            } else {
                echo "<p>¡Crea tu primer personaje ahora!</p/>";
            }
            echo '<hr>
                    <div id="newCharacter" class="personaje">
                        <form action="/LogeVerse/nuevoPersonaje" method="POST">
                            <h3>Nuevo Personaje</h3>
                            <p>
                                <label for="nombre">Nombre: </label>
                                <input type="text" name="nombre" id="nombre" placeholder="Drax el invisible">
                            </p>
                            <p>
                                <label for="historia">Historia: </label>
                                <textarea name="historia" id="historia" rows="3" cols="30" placeholder="Escribe una breve historia para desarrollar y contextualizar tu personaje."></textarea>
                            </p>
                            <button type="submit">Crear</button>
                        </form>
                      </div>';
            ?>
        </section>
    </main>
    <?php
    //Añadimos el pie de página común al resto de páginas
    include 'LogeVerse/views/shared/footer.html';
    ?>
</body>

</html>