<?php
class Pasiva
{

    use Identificable; //Trait para la identificación
    private string $nombre;
    private string $descripcion;
    private array $efectos;

    //=====================================CONSTRUCTOR=====================================
    public function __construct
    (
        int $id,
        string $nombre,
        string $descripcion,
        array $efectos
    ) {
        $this->setId($id);
        $this->setNombre($nombre);
        $this->setDescripcion($descripcion);
        $this->setEfectos($efectos);
    }

    //=====================================MÉTODOS========================================
    //Devuelve el array de los parámetros de la pasiva para la base de datos
    public function toDatabase(): array
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'descripcion' => $this->getDescripcion()
        ];
    }

    //Añade un nuevo efecto a la pasiva
    public function addEfecto(Efecto $efecto): self
    {
        $this->efectos[] = $efecto;
        return $this;
    }

    //Aplica la pasiva al personaje
    public function aplicarPasiva(Personaje $personaje): void
    {
        foreach ($this->efectos as $efecto) {
            $personaje->applyEfecto($efecto);
        }
    }


    //=====================================GETTERS & SETTERS=====================================
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