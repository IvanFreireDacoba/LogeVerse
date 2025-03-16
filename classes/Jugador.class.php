<?php

//Clase Jugador: contiene los datos de un jugador.
//El array personajes contiene los personajes asociados a dicho jugador (Personaje::$id).
class Jugador
{
    private int $id;
    private string $nombre;
    private int $puntos;

    //=====================================CONSTRUCTOR=====================================
    public function __construct($id, $nombre, $puntos, $personajes)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->puntos = $puntos;
        $this->personajes = $personajes;
    }

    //===================================GETTERS & SETTERS=================================
    //GSId
    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

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

    //GSPuntos
    public function getPuntos(): int
    {
        return $this->puntos;
    }
    public function setPuntos(int $puntos): self
    {
        $this->puntos = $puntos;
        return $this;
    }
}