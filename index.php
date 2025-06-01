<?php

//Establecemos la constante para permitir el acceso a modulos
define('IN_CONTROLLER', true);

//Carga de variables de entorno
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

// **********CAMBIAR AL MIGRAR**************
$location = "Location: /LogeVerse/inicio";  //$location = "Location: /";
// ****************************************

if (str_starts_with($uri, "/LogeVerse")) {
  include_once "LogeVerse/modules/functions.module.php";
  $location = "Location: /LogeVerse/inicio";
}

$routes = [
  //=============================LogeVerse=================================

  //controladores de entrada a las vistas [v.]
  '/LogeVerse/nuevoPersonaje' => "LogeVerse/controllers/v.newCharacter.controller.php",
  '/LogeVerse/perfil/personaje' => "LogeVerse/controllers/v.character.controller.php",
  '/LogeVerse/perfil/ajustes' => "LogeVerse/controllers/v.settings.controller.php",
  '/LogeVerse/propuestas' => "LogeVerse/controllers/v.propuestas.controller.php",
  '/LogeVerse/registrarse' => "LogeVerse/controllers/v.register.controller.php",
  '/LogeVerse/portalAdmin' => "LogeVerse/controllers/v.admin.controller.php",
  '/LogeVerse/perfil' => "LogeVerse/controllers/v.profile.controller.php",
  '/LogeVerse/inicio' => "LogeVerse/controllers/v.index.controller.php",
  '/LogeVerse/login' => "LogeVerse/controllers/v.login.controller.php",
  //controladores de módulos [m.]
  "/LogeVerse/updateCharacter" => "LogeVerse/controllers/m.updateCharacter.controller.php",
  '/LogeVerse/crearPersonaje' => "LogeVerse/controllers/m.newCharacter.controller.php",
  '/LogeVerse/eliminarPerfil' => "LogeVerse/controllers/m.drop_profile.controller.php",
  '/LogeVerse/cambiarPerfil' => "LogeVerse/controllers/m.settings.controller.php",
  '/LogeVerse/proponer' => "LogeVerse/controllers/m.propuesta.controller.php",
  '/LogeVerse/logear' => "LogeVerse/controllers/m.login.controller.php",
  //controladores de módulos de comandos [c.]
  '/LogeVerse/registrar' => "LogeVerse/controllers/c.register.controller.php",
  '/LogeVerse/cerrarSesion' => "LogeVerse/controllers/c.close.controller.php",
  //controladores de módulos de propuesta aceptada [ma.]
  '/LogeVerse/aceptar/Habilidad' => "LogeVerse/controllers/ma.habilidad.controller.php",
  '/LogeVerse/aceptar/Atributo' => "LogeVerse/controllers/ma.atributo.controller.php",
  '/LogeVerse/aceptar/Efecto' => "LogeVerse/controllers/ma.efecto.controller.php",
  '/LogeVerse/aceptar/Objeto' => "LogeVerse/controllers/ma.objeto.controller.php",
  '/LogeVerse/aceptar/Pasiva' => "LogeVerse/controllers/ma.pasiva.controller.php",
  '/LogeVerse/aceptar/Clase' => "LogeVerse/controllers/ma.clase.controller.php",
  '/LogeVerse/aceptar/Raza' => "LogeVerse/controllers/ma.raza.controller.php",

  //=======================================================================
];

session_start();

$blacklist = [
  'GLOBALS',
  '_SESSION',
  '_COOKIE',
  '_SERVER',
  '_ENV',
  '_FILES',
  '_GET',
  '_POST',
  'php_errormsg',
  'HTTP_RAW_POST_DATA',
  'http_response_header'
];

foreach ($_POST as $key => $value) {
  //Comprobar el envío de valores con al menos un caracter
  if (!preg_match('/^\w+$/', $key) || in_array($key, $blacklist)) {
    //Abortar la petición POST e informar
    $_SESSION["alert"] = "Detectada variable ilegal.";
    header("Location: /LogeVerse/inicio");
    exit;
  }
}

if (array_key_exists($uri, $routes)) {
  require($routes[$uri]);
} else {
  header($location);
  exit;
}
