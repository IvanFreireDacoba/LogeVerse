<?php

require_once 'LogeVerse/classes/include_classes.php';

//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("/LogeVerse/inicio");
    exit;
}

/**
 * Summary of conectar
 * @throws \PDOException
 * @return PDO
 * 
 * Función para conectarse a la base de datos mediante PDO
 */
function conectar(): PDO
{
    $host = $_ENV['db_servername'];
    $db = $_ENV['db_logeverse_database'];
    $user = $_ENV['db_logeverse_username'];
    $pass = $_ENV['db_logeverse_password'];
    $charset = $_ENV['DB_CHARSET'];
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
}

/**
 * Summary of generarPersonaje
 * @param pdo $conexion
 * @param array $datos
 * @return int
 * 
 * Genera un nuevo personaje en la base de datos, devuelve 0 si ha ocurrido un error durante la generación
 */
function generarPersonaje(pdo $conexion, array $datos): int
{
    //Comenzamos una transacción para asegurar la consistencia de los datos
    $conexion->beginTransaction();
    $pdo = $conexion->prepare("INSERT INTO personaje (
                                                                 propietario,
                                                                 raza,
                                                                 clase,
                                                                 nombre,
                                                                 historia,
                                                                 experiencia,
                                                                 dinero,
                                                                 puntos_habilidad,
                                                                 estado) 
                                                         VALUES (
                                                                 :propietario,
                                                                 :raza,
                                                                 :clase,
                                                                 :nombre,
                                                                 :historia,
                                                                 0,
                                                                 0,
                                                                 :puntos_habilidad,
                                                                 1
                                                                );");
    $pdo->bindValue(':propietario', $datos["propietario"]);
    $pdo->bindValue(':raza', $datos["raza"]->getId());
    $pdo->bindValue(':clase', $datos["clase"]->getId());
    $pdo->bindValue(':nombre', $datos["nombre"]);
    $pdo->bindValue(':historia', $datos["historia"]);
    $pdo->bindValue(':puntos_habilidad', $datos["puntos_habilidad"]);

    //Intentamos ejecutar el insert desde el try
    try {
        $pdo->execute();
        //Obtenemos el id del personaje recién creado
        $last_id = $conexion->lastInsertId();
    } catch (PDOException $e) {
        //Si ocurre un error, hacemos un rollback y devolvemos 0
        $conexion->rollBack();
        $last_id = 0;
    }

    //Si el insert ha sido exitoso, terminamos de construir el personaje
    $values = null; //Valoes a instroducir en el insert
    if ($last_id > 0) {
        //Para los atributos
        for ($i = 1; isset($datos[$i]); $i++) {
            $values .= $i != 1 ? ", " : null;
            $values .= "(" . $i . ", " . $last_id . ", " . $datos[$i] . ")";
        }
        $pdo_atr = $conexion->prepare("INSERT INTO atributo_personaje (
                                                                              id_atributo,
                                                                              id_personaje,
                                                                              cantidad)
                                                                      VALUES $values;");
        try {
            $pdo_atr->execute();
        } catch (PDOException $e) {
            $last_id = 0;
        }
    }
    if ($last_id > 0) {
        //Para el inventario
        $pdo_inv = $conexion->prepare("INSERT INTO inventario (
                                                                              id_personaje,
                                                                              id_objeto,
                                                                              cantidad)
                                                        VALUES (" . $last_id . "," .
            $datos["clase"]->getEquipoInicial()->getId() . ", 1);");
        try {
            $pdo_inv->execute();
        } catch (PDOException $e) {
            $last_id = 0;
        }
    }

    if ($last_id > 0) {
        //Para la imagen
        if ($datos["imagen"] !== null) {
            $pdo_img = $conexion->prepare("INSERT INTO imagen_personaje (
                                                                                id_personaje,
                                                                                img_data)
                                                                        VALUES (
                                                                                " . $last_id . ",
                                                                                :img_data);");
            $pdo_img->bindParam(":img_data", $datos["imagen"], PDO::PARAM_LOB);
            try {
                $pdo_img->execute();
            } catch (PDOException $e) {
                $_SESSION["alert"] = "Error subiendo la imagen.";
            }
            $pdo_img = null;
        }
    }
    //Ejecutamos los inserts desde un try para controlar posibles errores
    if ($last_id > 0) {
        //Si todo ha ido bien, hacemos un commit y devolvemos el id del personaje
        $conexion->commit();
    } else {
        //Si ocurre un error, hacemos un rollback y devolvemos 0
        $conexion->rollBack();
    }
    return $last_id;
}

/**
 * Summary of eliminarJugador
 * @param pdo $conexion
 * @param int $id
 * @return void
 * 
 * Elimina un usuario y todos los datos de este de la base de datos
 */
function eliminarJugador(pdo $conexion, int $id): void
{
    //Comenzamos la transacción
    $conexion->beginTransaction();
    try {
        //Obtener los personajes del jugador
        $stmt = $conexion->prepare("SELECT id FROM personaje WHERE propietario = :id_prop;");
        $stmt->bindParam("id_prop", $id, PDO::PARAM_INT);
        $stmt->execute();
        $personajes = $stmt->fetchAll(PDO::FETCH_COLUMN);
        //Eliminar los personajes del jugador
        $success = true;
        //Eliminamos los personajes de 1 en 1, MODO NO TRANSACCIONAL
        foreach ($personajes as $personaje) {
            $ctr = null;
            //Si ocurre algún error, $success = false
            try {
                eliminarPersonaje($conexion, $personaje, $ctr);
                if ($ctr) {
                    continue;
                } else {
                    $success = false;
                    break;
                }
            } catch (Error $e) {
                $success = false;
                break;
            }

        }
        //Si $success = false, ha fallado el borrado de algún personaje, hacemos rollback
        //y notificamos al usuario, restaurando sus personajes para que no se pierdan datos
        //ni consistencia
        if ($success) {
            //Eliminar los datos del jugador en las tablas relacionadas de la base de datos
            $tablas = [
                "imagen_perfil",
                "propuestas",
            ];

            foreach ($tablas as $tabla) {
                $stmt = $conexion->prepare("DELETE FROM $tabla WHERE id_jugador = :id_jugador;");
                $stmt->bindParam(":id_jugador", $id, PDO::PARAM_INT);
                $stmt->execute();
            }

            //Eliminar al jugador (si es administrador, borrarlo de la tabla admins)
            $stmt = $conexion->prepare("DELETE FROM admins WHERE id = :id_jugador;");
            $stmt->bindParam(":id_jugador", $id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $conexion->prepare("DELETE FROM jugador WHERE id = :id_jugador;");
            $stmt->bindParam(":id_jugador", $id, PDO::PARAM_INT);
            $stmt->execute();

            //Commit de la transacción, todo ha sido eliminado exitosamente 
            $conexion->commit();
        }
    } catch (PDOException $e) {
        $conexion->rollBack();
        error_log("Error al eliminar el usuario.");
        throw $e;
    }
}

/**
 * Summary of eliminarPersonaje
 * @param pdo $conexion
 * @param int $id
 * @return void
 * 
 * Elimina un personaje y toda la información de este de la base de datos
 * $success es opcional y define el comportamiento de la función, además devuelve
 * si esta 
 * MODOS: success == null -> no transaccional
 *        success != null -> transaccional
 */
function eliminarPersonaje(pdo $conexion, int $id, ?bool &$success = null): void
{
    try {
        $success !== null ? $conexion->beginTransaction() : null;

        //Eliminar la información del pesonaje de las tablas relacionadas en la base de datos
        $tablas = [
            "atributo_personaje",
            "habilidad_personaje",
            "imagen_personaje",
            "incursion_personaje",
            "inventario",
        ];

        foreach ($tablas as $tabla) {
            $stmt = $conexion->prepare("DELETE FROM $tabla WHERE id_personaje = :id_pj;");
            $stmt->bindParam(":id_pj", $id, PDO::PARAM_INT);
            $stmt->execute();
        }

        //Eliminar el personaje
        $stmt = $conexion->prepare("DELETE FROM personaje WHERE id = :id_pj;");
        $stmt->bindParam(":id_pj", $id, PDO::PARAM_INT);
        $stmt->execute();
        $success !== null ? $conexion->commit() : $success = true;
    } catch (PDOException $e) {
        $success !== null ? $conexion->rollBack() : $success = false;
        error_log("Error al eliminar al personaje {$id}");
        throw $e;
    }
}