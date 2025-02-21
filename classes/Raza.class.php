<?php

//Clase Raza: contiene la información de una raza
//Fuente: https://dnd5e.fandom.com/es/wiki/Razas

final class Raza
{
    private int $id;
    private string $raza;
    private string $descripcion;
    private string $historia;
    private array $atributos;
    private array $habilidades;
    private int $velocidad;
    private array $pasivas;
    private array $idiomas;

    public function __construct(int $id, string $raza, string $descripcion, string $historia, array $atributos, array $habilidades, int $velocidad, array $pasivas, array $idiomas)
    {
        $this->id = $id;
        $this->raza = $raza;
        $this->descripcion = $descripcion;
        $this->historia = $historia;
        $this->atributos = $atributos;
        $this->habilidades = $habilidades;
        $this->velocidad = $velocidad;
        $this->pasivas = $pasivas;
        $this->idiomas = $idiomas;
    }

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

    //GSRaza
    public function getRaza(): string
    {
        return $this->raza;
    }
    public function setRaza(string $raza): self
    {
        $this->raza = $raza;
        return $this;
    }

    //GSdescripcion
    public function getdescripcion(): string
    {
        return $this->descripcion;
    }
    public function setdescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;
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

    //GSAtributos
    public function getAtributos(): array
    {
        return $this->atributos;
    }
    public function setAtributos(array $atributos): self
    {
        $this->atributos = $atributos;
        return $this;
    }

    //GSHabilidades
    public function getHabilidades(): array
    {
        return $this->habilidades;
    }
    public function setHabilidades(array $habilidades): self
    {
        $this->habilidades = $habilidades;
        return $this;
    }

    //GSVelocidad
    public function getVelocidad(): int
    {
        return $this->velocidad;
    }

    public function setVelocidad(int $velocidad): self
    {
        $this->velocidad = $velocidad;
        return $this;
    }

    //GSIdiomas
    public function getIdiomas(): array
    {
        return $this->idiomas;
    }
    public function setIdiomas(array $idiomas): self
    {
        $this->idiomas = $idiomas;
        return $this;
    }

    //GSPasivas
    public function getPasivas(): array
    {
        return $this->pasivas;
    }
    public function setPasivas(array $pasivas): self
    {
        $this->pasivas = $pasivas;

        return $this;
    }
}
