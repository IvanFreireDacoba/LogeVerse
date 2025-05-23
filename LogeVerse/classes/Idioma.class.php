<?php

class Idioma
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