<?php

//Include de todas las clases del proyecto
//El orden se realiza en función de las dependencias entre clases
//Sin embargo, las propias clases podrían incluir las clases que necesitan
//en su interior, pero no es recomendable por motivos de legibilidad y mantenimiento

    //Clases independientes
    include_once 'Atributo.class.php';
    include_once 'Efecto.class.php';
    include_once 'Idioma.class.php';
    include_once 'Incursion.class.php';
    include_once 'Evento.class.php';

    //Dependientes de Efecto
    include_once 'Pasiva.class.php';
    include_once 'Objeto.class.php'; //Incluye todos los tipos de objeto
    
    //Dependiente de Efecto y Atributo
    include_once 'Habilidad.class.php';

    //Dependiente de Idioma, Pasiva y Atributo
    include_once 'Raza.class.php';

    //Dependiente de Habilidad y Objeto
    include_once 'Clase.class.php';
    
    //Dependiente de Objeto
    include_once 'Inventario.class.php';

    //Dependiente de Inventario, Raza y Clase
    include_once 'Personaje.class.php';

    //Dependiente de Personaje
    include_once 'Jugador.class.php';