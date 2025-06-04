<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("Location: " . url_init . "/LogeVerse/inicio");
    exit;
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
            //Instanciar y refrescar el objeto jugado y guardarlo el usuario de la sesión.
            $_SESSION["usuario"] = refrescarUsuario($pdo, $data["id"]);
            //Cerrar la conexión a la base de datos
            $pdo = null;
            //Ir al perfil del jugador
            header("Location: " . url_init . "/LogeVerse/perfil");
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
    header("Location: " . url_init . "/LogeVerse/login");
    exit;
} catch (PDOException $e) {
    //Cerrar la conexión a la base de datos
    $pdo = null;
    //Guardar el error en la sesión
    $_SESSION["alert"] = "Imposible conectar a la base de datos.";
    //Volver a la página de login
    header("Location: " . url_init . "/LogeVerse/login");
    exit;
}