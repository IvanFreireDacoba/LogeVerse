<?php

//Carga de variables de entorno
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

if (str_starts_with($uri, "/LogeVerse")) {
  include_once "LogeVerse/classes/include_classes.php";
  include_once "LogeVerse/modules/functions.module.php";
}

$routes = [
  // **********BORRAR AL MIGRAR**************
  '/' => "LogeVerse/redirect.php",        //*
  // ****************************************

  //=============================LogeVerse=================================

  //controladores de entrada a las vistas
  '/LogeVerse/inicio' => "LogeVerse/controllers/index.controller.php",
  '/LogeVerse/login' => "LogeVerse/controllers/login.controller.php",
  '/LogeVerse/registrarse' => "LogeVerse/controllers/register.controller.php",
  '/LogeVerse/perfil' => "LogeVerse/controllers/profile.controller.php",
  '/LogeVerse/perfil/ajustes' => "LogeVerse/controllers/settings.controller.php",
  '/LogeVerse/nuevoPersonaje' => "LogeVerse/controllers/newCharacter.controller.php",
  '/LogeVerse/propuestas' => "LogeVerse/controllers/propuestas.controller.php",
  '/LogeVerse/portalAdmin' => "LogeVerse/controllers/admin.controller.php",

  //módulos
  '/LogeVerse/crearPersonaje' => "LogeVerse/modules/newCharacter.module.php",
  '/LogeVerse/cambiarPerfil' => "LogeVerse/modules/settings.module.php",
  '/LogeVerse/eliminarPerfil' => "LogeVerse/modules/drop_profile.module.php",
  '/LogeVerse/proponer' => "LogeVerse/modules/propuesta.module.php",
  '/LogeVerse/registrar' => "LogeVerse/modules/register.module.php",
  '/LogeVerse/logear' => "LogeVerse/modules/login.module.php",
  '/LogeVerse/cerrarSesion' => "LogeVerse/modules/close.module.php",
  //módulos de propuesta aceptada
  '/LogeVerse/aceptar/Atributo' => "LogeVerse/modules/aceptar/acept_atributo.module.php",
  '/LogeVerse/aceptar/Clase' => "LogeVerse/modules/aceptar/acept_clase.module.php",
  '/LogeVerse/aceptar/Efecto' => "LogeVerse/modules/aceptar/acept_efecto.module.php",
  '/LogeVerse/aceptar/Habilidad' => "LogeVerse/modules/aceptar/acept_habilidad.module.php",
  '/LogeVerse/aceptar/Objeto' => "LogeVerse/modules/aceptar/acept_objeto.module.php",
  '/LogeVerse/aceptar/Pasiva' => "LogeVerse/modules/aceptar/acept_pasiva.module.php",
  '/LogeVerse/aceptar/Raza' => "LogeVerse/modules/aceptar/acept_raza.module.php",

  //recursos


  //=======================================================================
];

session_start();

if (array_key_exists($uri, $routes)) {
  require($routes[$uri]);
} else {
  header("Location: /");
  exit;
}
