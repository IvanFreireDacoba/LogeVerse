<?php
//Clase Efecto
//Los efectos definen las acciones que ejercen las habilidades, pasivas u objetos
//que pueden ejercerse sobre personajes, enemigos, el mapa u objetos

class Efecto
{
    use Identificable; //Trait para la identificación
    private string $nombre;
    private string $descripcion;
    private int $cantidad;
    private int $duracion;
    private string $tipo;
    //'damage' -> Causa daño
    //'status' -> Provoca un estado
    // 'debuf' -> Causa debuff
    // 'buff'  -> Causa buff
    // 'other' -> Causa un tipo de efecto diferente
    // 'none'  -> No causa nada

    private int $turnos_restantes;

    //=====================================CONSTRUCTOR=====================================
    public function __construct(
        int $id,
        string $nombre,
        string $descripcion,
        int $cantidad,
        int $duracion,
        string $tipo
    ) {
        $this->setId($id);
        $this->setNombre($nombre);
        $this->setDescripcion($descripcion);
        $this->setCantidad($cantidad);
        $this->setDuracion($duracion);
        $this->setTipo($tipo);
        $this->setTurnosRestantes($duracion);
    }

    //=====================================MÉTODOS========================================
    //Devuelve el array de los parámetros del efecto para la base de datos
    public function toDatabase(): array
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'descripcion' => $this->getDescripcion(),
            'cantidad' => $this->getCantidad(),
            'duracion' => $this->getDuracion(),
            'tipo' => $this->getTipo()
        ];
    }

    //Simula el paso de un turno del efecto para un personaje
    public function turnoEfecto(Personaje $personaje): void
    {
        if (in_array($this, $personaje->getEfectos())) {
            $personaje->dropEfecto($this);
            switch ($this->tipo) {
                case 'damage':
                    $personaje->takeDamage($this->cantidad);
                    break;
                case 'status':
                    $personaje->applyStatus($this);
                    break;
                case 'debuf':
                    $personaje->applyBuff($this);
                    break;
                case 'buff':
                    $personaje->applyBuff($this, false);
                    break;
                default:
                    // No se aplica ningún efecto
                    break;
            }
            $this->turnos_restantes--;
            if ($this->turnos_restantes > 0) {
                $personaje->applyEfecto($this);
            }
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

    //GSCantidad
    public function getCantidad(): int
    {
        return $this->cantidad;
    }
    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;
        return $this;
    }

    //GSDuracion
    public function getDuracion(): int
    {
        return $this->duracion;
    }
    public function setDuracion(int $duracion): self
    {
        $this->duracion = $duracion;
        return $this;
    }

    //GSTipo
    public function getTipo(): string
    {
        return $this->tipo;
    }
    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;
        return $this;
    }

    //GSTurnosRestantes
    public function getTurnosRestantes(): int
    {
        return $this->turnos_restantes;
    }
    public function setTurnosRestantes(int $turnos_restantes): self
    {
        $this->turnos_restantes = $turnos_restantes;
        return $this;
    }
}
