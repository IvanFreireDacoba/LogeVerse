<?php

//Clase Raza: contiene la información de una raza
//Fuente: https://dnd5e.fandom.com/es/wiki/Razas

require_once "Pasiva.class.php";
require_once "Atributo.class.php";
require_once "Idioma.class.php";

final class Raza
{
    private int $id;
    private string $raza;
    private string $descripcion;
    private string $historia;
    private array $atributos;
    private int $velocidad;
    private array $pasivas;
    private array $idiomas;

    public function __construct(int $id, string $raza, string $descripcion, string $historia, array $atributos, array $cantidades, int $velocidad, array $pasivas, array $idiomas)
    {
        $this->id = $id;
        $this->raza = $raza;
        $this->descripcion = $descripcion;
        $this->historia = $historia;
        $this->setAtributos($atributos, $cantidades);
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
    public function setAtributos(array $atributos, array $cantidades): self
    {
        for ($i = 0; $i < count($atributos); $i++) {
            $this->atributos = [$atributos[$i], $cantidades[$i]];
        }
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
