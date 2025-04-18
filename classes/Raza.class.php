<?php

//Clase Raza: contiene la información de una raza
//Fuente: https://dnd5e.fandom.com/es/wiki/Razas
final class Raza implements toDatabase
{
    use Identificable; //Trait para la identificación
    private string $nombre;
    private string $descripcion;
    private string $historia;
    private array $atributos;
    private int $velocidad;
    private array $pasivas;
    private array $idiomas;
    private string $imagen;

    //=====================================CONSTRUCTOR=====================================
    public function __construct(int $id, string $nombre, string $descripcion, string $historia, array $atributos, array $cantidades, int $velocidad, array $pasivas = [], array $idiomas = [], string $imagen = null)
    {
        $this->setId($id);
        $this->setNombre($nombre);
        $this->setDescripcion($descripcion);
        $this->setHistoria($historia);
        $this->setAtributos($atributos, $cantidades);
        $this->setVelocidad($velocidad);
        $this->setPasivas($pasivas);
        $this->setIdiomas($idiomas);
        $this->setImagen($imagen);
    }

    //=====================================MÉTODOS========================================
    //Método para actualizar/añadir la raza en la base de datos
    public function toDatabase(): array
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'caracteristicas' => $this->getDescripcion(),
            'historia' => $this->getHistoria(),
            'atributos' => json_encode($this->getAtributos()),
            'velocidad' => $this->getVelocidad(),
        ];
    } 

    //Método para obtener un resumen de los datos de la raza en HTML
    public function toShortHTML(): string
    {
        $html = "<div class='raza'>";
        $html .= "<h2>" . $this->getNombre() . "</h2>";
        $html .= "<p>Velocidad: " . $this->getVelocidad() . "</p>";
        $html .= "</div>";
        return $html;
    }

    //Método para obtener los datos de la raza en HTML
    public function toHTML(): string
    {
        $html = "<div class='raza'>";
        $html .= "<h2>" . $this->getNombre() . "</h2>";
        $html .= "<p>" . $this->getDescripcion() . "</p>";

        /*
            ===============PENDIENTE=============
            Sacar los atributos, idiomas y pasivas de la raza
            ===============PENDIENTE============= 
        */

        $html .= "<p>Historia: " . $this->getHistoria() . "</p>";
        $html .= "<p>Velocidad: " . $this->getVelocidad() . "</p>";
        $html .= "</div>";
        return $html;
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

    //GSImagen
    public function getImagen(): string
    {
        return $this->imagen;
    }
    public function setImagen(string $imagen = null): self
    {
        $this->imagen = $this->getFormattedImg($imagen);
        return $this;
    }
}
