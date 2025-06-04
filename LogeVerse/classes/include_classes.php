<?php


define('url_classes', __DIR__);

//Include de todas las clases del proyecto
//El orden se realiza en función de las dependencias entre clases
//Sin embargo, las propias clases podrían incluir las clases que necesitan
//en su interior, pero no es recomendable por motivos de legibilidad y mantenimiento

//Trait para la identificación
include_once url_classes . "/id.trait.php";

//Interfaz
include_once url_classes . "/dataBase.interface.php";

//Clases independientes
include_once url_classes . "/Atributo.class.php";
include_once url_classes . "/Efecto.class.php";
include_once url_classes . "/Idioma.class.php";
include_once url_classes . "/Incursion.class.php";
include_once url_classes . "/Evento.class.php";

//Dependientes de Efecto
include_once url_classes . "/Pasiva.class.php";
include_once url_classes . "/Objeto.class.php"; //Incluye todos los tipos de objeto

//Dependiente de Efecto y Atributo
include_once url_classes . "/Habilidad.class.php";

//Dependiente de Idioma, Pasiva y Atributo
include_once url_classes . "/Raza.class.php";

//Dependiente de Habilidad y Objeto
include_once url_classes . "/Clase.class.php";

//Dependiente de Objeto
include_once url_classes . "/Inventario.class.php";

//Dependiente de Inventario, Raza y Clase
include_once url_classes . "/Personaje.class.php";

//Dependiente de Personaje
include_once url_classes . "/Jugador.class.php";