<?php

class Incursion
{
    use Identificable; //Trait para la identificación
    private string $nombre;
    private string $historia;

    //=====================================CONSTRUCTOR=====================================
    public function __construct(int $id, string $nombre, string $historia)
    {
        $this->setId($id);
        $this->setNombre($nombre);
        $this->setHistoria($historia);
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
    
    //GSHistoria
    public function getHistoria(): string
    {
        return $this->historia;
    }
    public function setHistoria(string $historia): self
    {
        $this->historia = $historia;
        return $this;
    }
}