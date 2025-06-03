<?php

//Include de todas las clases del proyecto
//El orden se realiza en función de las dependencias entre clases
//Sin embargo, las propias clases podrían incluir las clases que necesitan
//en su interior, pero no es recomendable por motivos de legibilidad y mantenimiento

    //Trait para la identificación
    include_once 'LogeVerse/classes/id.trait.php';

    //Interfaz
    include_once 'database.interface.php';

    //Clases independientes
    include_once 'LogeVerse/classes/Atributo.class.php';
    include_once 'LogeVerse/classes/Efecto.class.php';
    include_once 'LogeVerse/classes/Idioma.class.php';
    include_once 'LogeVerse/classes/Incursion.class.php';
    include_once 'LogeVerse/classes/Evento.class.php';

    //Dependientes de Efecto
    include_once 'LogeVerse/classes/Pasiva.class.php';
    include_once 'LogeVerse/classes/Objeto.class.php'; //Incluye todos los tipos de objeto
    
    //Dependiente de Efecto y Atributo
    include_once 'LogeVerse/classes/Habilidad.class.php';

    //Dependiente de Idioma, Pasiva y Atributo
    include_once 'LogeVerse/classes/Raza.class.php';

    //Dependiente de Habilidad y Objeto
    include_once 'LogeVerse/classes/Clase.class.php';
    
    //Dependiente de Objeto
    include_once 'LogeVerse/classes/Inventario.class.php';

    //Dependiente de Inventario, Raza y Clase
    include_once 'LogeVerse/classes/Personaje.class.php';

    //Dependiente de Personaje
    include_once 'LogeVerse/classes/Jugador.class.php';