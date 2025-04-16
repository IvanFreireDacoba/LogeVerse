<?php

//Habilidades de los personajes
//Las habilidades son las acciones que puede realizar un personaje en el juego,
//inclyendo ataques, defensas, curaciones, etc.

class Habilidad
{
    use Identificable; //Trait para la identificación
    private string $nombre;
    private string $descripcion;
    private Atributo $tipo;
    //atributo que se utiliza para la tirada de habilidad
    private int $coste;
    private array $efectos;

    //=====================================CONSTRUCTOR=====================================
    public function __construct
    (
        int $id,
        string $nombre,
        string $descripcion,
        Atributo $tipo,
        int $coste,
        array $efectos
    ) {
        $this->setId($id);
        $this->setNombre($nombre);
        $this->setDescripcion($descripcion);
        $this->setTipo($tipo);
        $this->setCoste($coste);
        $this->setEfectos($efectos);
    }

    //=====================================MÉTODOS========================================
    //Devuelve el array de los parámetros de la habilidad para la base de datos
    public function toDatabase(): array
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'descripcion' => $this->getDescripcion(),
            'tipo' => $this->getTipo()->getId(),
            'coste' => $this->getCoste()
        ];
    }

    //Añade un nuevo efecto a la habilidad
    public function addEfecto(Efecto $efecto): self
    {
        $this->efectos[] = $efecto;
        return $this;
    }

    //Aplica la habilidad al personaje
    public function aplicarHabilidad(Personaje $personaje): void
    {
        foreach ($this->efectos as $efecto) {
            $personaje->applyEfecto($efecto);
        }
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

    //GSTipo
    public function getTipo(): Atributo
    {
        return $this->tipo;
    }
    public function setTipo(Atributo $tipo): self
    {
        $this->tipo = $tipo;
        return $this;
    }

    //GSCoste
    public function getCoste(): int
    {
        return $this->coste;
    }
    public function setCoste(int $coste): self
    {
        $this->coste = $coste;
        return $this;
    }

    //GSEfectos
    public function getEfectos(): array
    {
        return $this->efectos;
    }
    public function setEfectos(array $efectos): self
    {
        $this->efectos = $efectos;
        return $this;
    }
}