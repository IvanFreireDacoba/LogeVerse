<?php

$host = 'localhost';
$db = 'dndmanager';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

//Scripts
$script_estructura = file_get_contents("/opt/lampp/htdocs/LogeVerse/DB_Schema/scripts/structure.sql");
$script_procedimientos = file_get_contents("/opt/lampp/htdocs/LogeVerse/DB_Schema/scripts/procedures.sql");
$script_inserciones = file_get_contents("/opt/lampp/htdocs/LogeVerse/DB_Schema/scripts/datum.sql");

//Proceso 1 -> intentar conectarse a la base de datos.
try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Proceso 2 -> Si se conecta a la base de datos, comprueba que exista algún dato en la tabla atributos
    $stmt = $pdo->prepare("SELECT id FROM atributo;");
    $stmt->execute();

    //Si no encuentra datos, rellena la base de datos
    if ($stmt->rowCount() === 0) {
        cargarDatos($pdo, $script_inserciones);
    } else {
        echo '<h1>La base de datos ya está lista.</h1>
      <button onclick="window.location.href=\'/LogeVerse\'">INICIAR</button>';
    }
} catch (\PDOException $e) {
    //Conectar sin especificar la base de datos para poder crearla
    try {
        $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS $db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        //Cerrar la conexión actual y crea una nueva apuntando a la nueva base de datos
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        cargarEstructura($pdo, $script_estructura);
        cargarProcedumientos($pdo, $script_procedimientos);
        cargarDatos($pdo, $script_inserciones);
    } catch (\PDOException $ex) {
        die("Error al crear la base de datos: " . $ex->getMessage());
    }
}

/* ======================= FUNCIONES ======================== */
/* ======================= FUNCIONES ======================== */

function cargarEstructura($pdo, $script_estructura)
{
    try {
        ejecutarPorBloques($pdo, $script_estructura);
        echo "Estructura cargada correctamente.<br>";
    } catch (\PDOException $e) {
        die("Error al cargar la estructura: " . $e->getMessage());
    }
}

function cargarProcedumientos($pdo, $script_procedures)
{
    try {
        ejecutarPorBloques($pdo, $script_procedures);
        echo "Procedimientos establecidos correctamente.<br>";
    } catch (\PDOException $e) {
        die("Error al establecer los procedimientos: " . $e->getMessage());
    }
}

function cargarDatos($pdo, $script_inserciones)
{
    try {
        ejecutarPorBloques($pdo, $script_inserciones);
        echo "Datos insertados correctamente.<br>";
    } catch (\PDOException $e) {
        die("Error al insertar los datos: " . $e->getMessage());
    }
}

//Divide el contenido en bloques por punto y coma (;) que terminan una sentencia
function ejecutarPorBloques($pdo, $script)
{
    $sentencias = explode(";", $script);
    foreach ($sentencias as $sentencia) {
        $sentencia = trim($sentencia);
        if (!empty($sentencia)) {
            $pdo->exec($sentencia);
        }
    }
}

