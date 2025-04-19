<?php



//Carga de las clases para el módulo de login
include_once '../classes/include_classes.php';

session_start();

//Funciones de conexión a la base de datos
include_once './toDatabase.module.php';

//Gestión del login

//Preparación de los datos recibidos por post
if ($_POST) {
    foreach ($_POST as $key => $value) {
        $$key = htmlspecialchars($value);
    }
}

//Conexión a la base de datos
try {
    $pdo = conectar();
    //Consulta de los datos del jugador
    $stmt = $pdo->prepare("SELECT * FROM jugador WHERE nombre = :usuario OR correo = :usuario");
    $stmt->bindParam(':usuario', $username, PDO::PARAM_STR);
    $stmt->execute();
    //Comprobar si la consulta ha devuelto algún resultado
    if ($stmt->rowCount() > 0) {
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        //Si el jugador existe, se comprueba la contraseña
        if (password_verify($password, $data["hash"])) {
            //Si la contraseña es correcta, se guarda el jugador en la sesión
            //Instanciar el objeto jugador
            $jugador = new Jugador(
                $data["id"],
                $data["nombre"],
                $data["correo"],
                $data["puntos"],
                $data["notificaciones"]
            );

            //Guardar el jugador en la sesión
            $_SESSION["usuario"] = $jugador;

            //Cerrar la conexión a la base de datos
            $pdo = null;

            //Ir al perfil del jugador
            header("Location: ../controllers/profile.controller.php");
            exit;
        } else {
            //Guardar el error en la sesión
            $_SESSION["alert"] = "Contraseña incorrecta.";
        }
    } else {
        //Guardar el error en la sesión
        $_SESSION["alert"] = "El usuario no existe.";
    }
    //Cerrar la conexión a la base de datos
    $pdo = null;
    //Volver a la página de login
    header("Location: ../controllers/login.controller.php");
    exit;
} catch (PDOException $e) {
    //Cerrar la conexión a la base de datos
    $pdo = null;
    //Guardar el error en la sesión
    $_SESSION["alert"] = "Imposible conectar a la base de datos.";
    //Volver a la página de login
    header("Location: ../controllers/login.controller.php");
    exit;
}