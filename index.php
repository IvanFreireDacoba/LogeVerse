<?php

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$routes = [
  //controladores de entrada a las vistas
  '/' => "controllers/index.controller.php"
];

session_start();
if (!isset($_SESSION['userName'])) {
  session_destroy();
}

if (array_key_exists($uri, $routes)) {
  require $routes[$uri];
} else {
  header("Location: /");
}