<?php

//Clase Evento
//Contiene los eventos que se pueden producir en el juego
//y que pueden ser activados por el jugador o por el sistema

class Evento
{
    use Identificable; //Trait para la identificación
    private string $nombre;
    private string $descripcion;

    //=====================================CONSTRUCTOR=====================================
    public function __construct(int $id, string $nombre, string $descripcion)
    {
        $this->setId($id);
        $this->setNombre($nombre);
        $this->setDescripcion($descripcion); 
    }

    //===================================GETTERS & SETTERS=================================
    //GSNombre
    public function getNombre(): string
    {
        return $this->nombre;
    }
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;    
    }

    //GSDescripcion
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }
    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;
        return $this;    
    }

}