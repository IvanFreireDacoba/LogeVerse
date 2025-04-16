<?php

//Clase Raza: contiene la información de una raza
//Fuente: https://dnd5e.fandom.com/es/wiki/Razas
final class Raza
{
    use Identificable; //Trait para la identificación
    private string $nombre;
    private string $descripcion;
    private string $historia;
    private array $atributos;
    private int $velocidad;
    private array $pasivas;
    private array $idiomas;

    //=====================================CONSTRUCTOR=====================================
    public function __construct(int $id, string $nombre, string $descripcion, string $historia, array $atributos, array $cantidades, int $velocidad, array $pasivas, array $idiomas)
    {
        $this->setId($id);
        $this->setNombre($nombre);
        $this->setDescripcion($descripcion);
        $this->setHistoria($historia);
        $this->setAtributos($atributos, $cantidades);
        $this->setVelocidad($velocidad);
        $this->setPasivas($pasivas);
        $this->setIdiomas($idiomas);
    }

    //=====================================GETTERS & SETTERS=====================================
    //GSRaza
    public function getNombre(): string
    {
        return $this->nombre;
    }
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
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
