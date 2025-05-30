<?php

include_once "LogeVerse/classes/include_classes.php";
include_once "LogeVerse/modules/toDatabase.module.php";
include_once "LogeVerse/modules/functions.module.php";

session_start();

//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("/LogeVerse/inicio");
    exit;
}

$_SESSION["POST"] = $_POST;
foreach ($_POST as $key => $value) {
    $$key = $value;
}
//Comprobar que todos los datos están correctamente inicializados
//Nombre
if (isset($nombre) || empty($nombre)) {
    $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
} else {
    $_SESSION["alert"] = "El nombre no puede estar vacío.";
    header("Location: /LogeVerse/nuevoPersonaje");
    exit;
}
//Historia
if (isset($historia) || empty($historia)) {
    $historia = htmlspecialchars($historia, ENT_QUOTES, 'UTF-8');
} else {
    $_SESSION["alert"] = "La historia no puede estar vacía.";
    header("Location: /LogeVerse/nuevoPersonaje");
    exit;
}

//Nos conectamos y buscamos los ids de razas y clases asi como los ids y nombres de los atributos
try {
    $conexion = conectar();
    //Obtener los ids de las razas
    $pdo = $conexion->prepare("SELECT id FROM raza;");
    $pdo->execute();
    $ids_razas_existentes = $pdo->fetchAll(PDO::FETCH_COLUMN, 0);
    //Obtener los ids de las clases
    $pdo = $conexion->prepare("SELECT id FROM clase;");
    $pdo->execute();
    $ids_clases_existentes = $pdo->fetchAll(PDO::FETCH_COLUMN, 0);
    //Obtener los ids y nombres de los atributos
    $pdo = $conexion->prepare("SELECT id, nombre FROM atributo;");
    $pdo->execute();
    $atributos_existentes = $pdo->fetchAll(PDO::FETCH_ASSOC);
    //Obtener de base de datos la cantidad máxima de puntos iniciales disponibles
    $max_atr_points = (int) obtenerConstante(1);
} catch (PDOException $e) {
    $_SESSION["alert"] = "Error al conectar a la base de datos. Por favor, prueba otra vez.";
    header("Location: /LogeVerse/nuevoPersonaje");
    exit;
}

//Raza
if (in_array($raza, $ids_razas_existentes)) {
    $raza = refrescarRaza($conexion, $raza);
} else {
    $_SESSION["alert"] = "La raza seleccionada no existe.";
    header("Location: /LogeVerse/nuevoPersonaje");
    exit;
}
//Clase
if (in_array($clase, $ids_clases_existentes)) {
    $clase = refrescarClase($conexion, $clase);
} else {
    $_SESSION["alert"] = "La clase seleccionada no existe.";
    header("Location: /LogeVerse/nuevoPersonaje");
    exit;
}
//Atributos
$pos = 0;
$tot_atr;
$sumatorio_ptos_habilidad = 0;
$atributos = [];
$atrs_raza = $raza->getAtributos();
foreach ($atributos_existentes as $atributo) {
    //Obtenemos el nombre del valor total del atributo que llega por POST
    $atr_tot = $atributo["id"];
    //Obtenemos el valor de cantidad de puntos que se le asignaron al atributo
    $atr_ptos_value = 'atr_ptos_' . $atributo["nombre"];
    //Si se ha asignado una cantidad negativa de puntos al atributo, es un error. 
    if ($$atr_ptos_value < 0) {
        $_SESSION["alert"] = "No se pueden asignar cantidades negativas de puntos a un atributo.";
        header("Location: /LogeVerse/nuevoPersonaje");
        exit;
    }
    //Obtenemos el valor del atributo de la raza, si existe, sino le asignamos 0
    $pos_atr = -1;
    foreach ($atrs_raza as $i => $atr_r) {
        if ($i > 0 && $atr_r[0] === $atributo["nombre"]) {
            $pos_atr = $i;
            break;
        }
    }
    $pos_atr > -1 ? $atr_raza = $atrs_raza[$pos_atr][1] : $atr_raza = 0;
    //Calculamos el total de puntos del atributo que se esperaría según los datos de la base de datos
    //en conjunto con los del formulario
    $atr_tot_check = $atr_raza + $$atr_ptos_value + $_SESSION["tiradas_pj"][$atributo["id"]];
    //Comprobamos que el total del puntos que nos llega del atributo sea coherente con
    //los que se obtendrían al sumar desde la base de datos y el formulario
    if ($$atr_tot == $atr_tot_check) {
        $sumatorio_ptos_habilidad += $$atr_ptos_value;
        //Si hemos llegado hasta aquí, el atributo es correcto y lo añadimos al array de atributos
        $atributos[] = [$atributo["id"], $$atr_tot];
    } else {
        $_SESSION["alert"] = "El total de puntos de los atributos no es correcto.";
        header("Location: /LogeVerse/nuevoPersonaje");
        exit;
    }
    $pos++;
}

//Comprobamos que el total de puntos sea coherente en asignación
/* Ya se ha comprobado que todos sean positivos, ahora se comprobará que no se asignaron de más,
para ello vamos a restar al total de puntos disponibles la cantidad de puntos utilizados y comprobar
que los $ptos_habilidad restantes (que van a base de datos) coinciden con dicho valor*/
if ($max_atr_points - $sumatorio_ptos_habilidad != $ptos_habilidad) {
    $_SESSION["alert"] = "El total de puntos de los atributos no está correctamente asignado.";
    header("Location: /LogeVerse/nuevoPersonaje");
    exit;
}
//Almacenamos los datos del personaje en la base de datos
//construimos el array de datos del personaje
$id_usuario = $_SESSION["usuario"]->getId();
$datos_pj["propietario"] = $id_usuario;
$datos_pj["nombre"] = $nombre;
$datos_pj["historia"] = $historia;
$datos_pj["raza"] = $raza;
$datos_pj["clase"] = $clase;
$datos_pj["puntos_habilidad"] = $ptos_habilidad;
//Cada atributo se relaciona con su id y su valor
foreach ($atributos_existentes as $atributo) {
    try {
        $datos_pj[$atributo["id"]] = $_POST[$atributo["id"]];
    } catch (Exception $e) {
        //Si existe algún problema con la relación, establecemos el valor en 0
        //para el atributo para dicho personaje 
        $datos_pj[$atributo["id"]] = 0;
    }
}
//Pasamos la imagen a blob para la base de datos
if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
    $datos_pj["imagen"] = file_get_contents($_FILES["imagen"]["tmp_name"]);
} else {
    $datos_pj["imagen"] = null;
}

//Creamos el personaje en la base de datos
$pj_id = generarPersonaje($conexion, $datos_pj);

//Devolvemos la información del proceso, almacenando el personaje en la sesión si todo se ha generado correctamente
if ($pj_id > 0) {
    $pj = refrescarPersonaje($conexion, $pj_id);
    $usser_pjs = $_SESSION["usuario"]->getPersonajes();
    $usser_pjs[] = $pj;
    $_SESSION["usuario"]->setPersonajes($usser_pjs);
    unset($_SESSION["POST"]);
    $_SESSION["alert"] = "Personaje creado correctamente.";
    $conexion = null; //Cerramos la conexión a la base de datos
    header("Location: /LogeVerse/perfil");
    exit;
} else {
    //Feedback al usuario en caso de error
    $_SESSION["alert"] = "Error al crear el personaje. Por favor, prueba otra vez.";
    $_SESSION["newChar"] = $_POST;
    $conexion = null; //Cerramos la conexión a la base de datos
    header("Location: /LogeVerse/nuevoPersonaje");
    exit;
}