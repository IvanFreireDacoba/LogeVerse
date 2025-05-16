<?php

//Módulo con los hydrator de cada clase

include_once "../classes/include_classes.php";

function hydrateRaza(array $datos): Raza
{
    if(!isset($datos["imagen"])){
        $datos["imagen"] = null;
    }

    return new Raza(
        $datos["id"],
        $datos["nombre"],
        $datos["caracteristicas"],
        $datos["historia"],
        $datos["atributos"],
        $datos["cantidades"],
        $datos["velocidad"],
        $datos["pasivas"],
        $datos["idiomas"],
        $datos["imagen"],
    );
}

function hydrateClase(array $datos): Clase
{
    if(!isset($datos["imagen"])){
        $datos["imagen"] = null;
    }

    return new Clase(
        $datos["id"],
        $datos["nombre"],
        $datos["descripcion"],
        $datos["dado_golpe"],
        $datos["equipo_inicial"],
        $datos["hp_atr"],
        $datos["hp_mod"],
        $datos["def_atr"],
        $datos["def_mod"],
        $datos["golpe_atr"],
        $datos["golpe_mod"],
        $datos["imagen"],
    );
}

function hydrateJugador(array $datos): Jugador {

    if(!isset($datos["img_data"])){
        $datos["img_data"] = null;
    }

    $jugador = new Jugador(
        $datos["id"],
        $datos["nombre"],
        $datos["correo"],
        $datos["puntos"],
        $datos["notificaciones"],
        $datos["personajes"],
        $datos["propuestas"],
        $datos["img_data"],
    );

    $jugador->setAdmin($datos["admin"]);  

    return $jugador;
}

function hydratePersonaje(array $datos): Personaje {

    if(!isset($datos["img_data"])){
        $datos["img_data"] = null;
    }
    
    return new Personaje(
        $datos["id"],
        $datos["propietario"],
        $datos["raza"],
        $datos["clase"],
        $datos["nombre"],
        $datos["historia"],
        $datos["experiencia"],
        $datos["dinero"],
        $datos["puntos_habilidad"],
        $datos["habilidades"],
        $datos["inventario"],
        $datos["atributos"],
        $datos["estado"],
        $datos["incursiones"],
        $datos["img_data"],
    );
}

function hydratePasiva(array $datos): Pasiva {
    return new Pasiva(
        $datos["id"],
        $datos["nombre"],
        $datos["descripcion"],
        $datos["efectos"],
    );
}

function hydrateHabilidad(array $datos): Habilidad {
    return new Habilidad(
        $datos["id"],
        $datos["nomre"],
        $datos["descripcion"],
        $datos["tipo"],
        $datos["coste"],
        $datos["efectos"],
    );
}
