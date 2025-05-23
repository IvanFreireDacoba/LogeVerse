<?php
//Contiene los atributos de los personajes que
//definen sus capacidades para realizar ciertas acciones
final class Atributo implements toDatabase
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

    //=====================================MÉTODOS========================================
    //Método para obtener los datos de la instancia en un array
    public function toDatabase(): array
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'descripcion' => $this->getDescripcion()
        ];
    }

    //Método para imprimir un resumen de la instancia en HTML
    public function toShortHTML(): string
    {
        return "<div class='atributo {$this->getNombre()}'>
                    <p>{$this->getNombre()}</p>
                </div>";
    }

    //Método para imprimir los datos de la instancia en HTML
    public function toHTML(): string
    {
        return "<div class='atributo {$this->getNombre()}'>
                    <details>
                        <summary>{$this->getNombre()}</summary>
                        <p>{$this->getDescripcion()}</p>
                    </details>
                </div>";
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