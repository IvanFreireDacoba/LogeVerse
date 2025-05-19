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

    //Comprobar que las contraseñas coinciden desde el servidor
    if ($password == $password_rep) {
        //Hash de la contraseña
        $password = password_hash($password, PASSWORD_DEFAULT);
        try{
            //Comprobar si el usuario ya existe
            $pdo = conectar();
            $stmt = $pdo->prepare("SELECT * FROM jugador WHERE nombre = :usuario OR correo = :email");
            $stmt->bindParam(':usuario', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            //Comprobar si la consulta ha devuelto algún resultado
            if($stmt->rowCount() == 0){
                //Si el jugador no existe se guarda en la base de datos
                $stmt = $pdo->prepare("INSERT INTO jugador (nombre, correo, hash) VALUES (:usuario, :email, :password)");
                $stmt->bindParam(':usuario', $username, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->execute();
                //Comprobar si la consulta ha devuelto algún resultado
                if($stmt->rowCount() > 0){
                    //Si el jugador se ha guardado, se guarda en la sesión
                    $jugador = new Jugador(
                        $pdo->lastInsertId(),
                        $username,
                        $email,
                        0,
                        0,
                        [],
                        [],
                        null,

                    );
                    //Guardar el jugador en la sesión
                    $_SESSION["usuario"] = $jugador;
                    //Cerrar la conexión a la base de datos
                    $pdo = null;
                    //Ir al perfil del jugador
                    header("Location: ../controllers/profile.controller.php");
                    exit;
                } else {
                    //Cerrar la conexión a la base de datos
                    $pdo = null;
                    //Guardar el error en la sesión
                    $_SESSION["alert"] = "Error al guardar el usuario.";
                }
            } else {
                //Cerrar la conexión a la base de datos
                $pdo = null;
                //Guardar el error en la sesión
                $_SESSION["alert"] = "El usuario o el correo ya existen.";
            }
        } catch (PDOException $e) {
            //Cerrar la conexión a la base de datos
            $pdo = null;
            //Guardar el error en la sesión
            $_SESSION["alert"] = "Error al conectar a la base de datos.";
        }
    } else {
        //Guardar el error en la sesión
        $_SESSION["alert"] = "Las contraseñas no coinciden. Por favor, no modifiques el JavaScript.";
    }
    //Si se produce algun error, se redirige al formulario de registro
    header("Location: ../controllers/register.controller.php");
    exit;