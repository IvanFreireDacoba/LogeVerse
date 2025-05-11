<?php

require_once '../classes/include_classes.php';

/**
 * Summary of conectar
 * @throws \PDOException
 * @return PDO
 * 
 * Función para conectarse a la base de datos mediante PDO
 */
function conectar(): PDO
{
    $host = 'localhost';
    $db = 'dndmanager';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    /* INSTALAR composer require vlucas/phpdotenv cuando se migre a LogeCraft
        $host = $_ENV['DB_HOST'];
        $db = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];
        $charset = $_ENV['DB_CHARSET'];
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    */
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
        $datos["imagen"] === null ? $pdo_img = $conexion->prepare("INSERT INTO imagen_personaje (
                                                                                                    id_personaje,
                                                                                                    img_data)
                                                                                VALUES (
                                                                                        {$last_id},
                                                                                        " . $datos["imagen"] . ");") : null;
        try {
            $datos["imagen"] === null ? $pdo_img->execute() : null;
        } catch (PDOException $e) {
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
