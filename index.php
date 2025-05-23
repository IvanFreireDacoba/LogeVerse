<?php

//Carga de variables de entorno
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$routes = [
  //=============================LogeCraft=================================

  //controladores de entrada a las vistas
  '/' => "LogeCraft/controllers/index.controller.php",
  '/descargas' => "LogeCraft/controllers/download.controller.php",
  '/login' => "LogeCraft/controllers/login.controller.php",
  '/register' => "LogeCraft/controllers/register.controller.php",
  '/profile' => "LogeCraft/controllers/profile.controller.php",
  '/ajustes' => 'LogeCraft/controllers/settings.controller.php',

  //módulos
  '/close' => "LogeCraft/modules/close.module.php",
  '/logear' => "LogeCraft/modules/login.module.php",
  '/registrar' => "LogeCraft/modules/register.module.php",
  '/updateImage' => "LogeCraft/modules/updateImage.module.php",
  '/profileImage' => "LogeCraft/modules/profileImage.module.php",
  '/confirmar' => 'LogeCraft/modules/mailConfirmation.module.php',

  //recursos
  '/resources/version.json' => "LogeCraft/resources/version.json",
  '/Mods&Plugins' => "LogeCraft/licenses/modsNplugins/ModsNPlugins.html",

  //=======================================================================

  //=============================LogeVerse=================================

  //controladores de entrada a las vistas
  '/LogeVerse' => "LogeVerse/controllers/index.controller.php",
  '/LogeVerse/login' => "LogeVerse/controllers/login.controller.php",
  '/LogeVerse/register' => "LogeVerse/controllers/register.controller.php",
  '/LogeVerse/profile' => "LogeVerse/controllers/profile.controller.php",

  //módulos


  //recursos
  
  
  //=======================================================================
];

session_start();
if (!isset($_SESSION['usuario'])) {
  session_destroy();
}

if (array_key_exists($uri, $routes)) {
  require($routes[$uri]);
} else {
  header("Location: /");
}
