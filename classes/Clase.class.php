<?php

//Clase Clase: contiene la información de una clase
//Fuente: https://nivel20.com/games/dnd-5/professions
class Clase implements toDatabase
{
    use Identificable; //Trait para la identificación
    private string $nombre;
    private string $descripcion;
    private int $dado_golpe;
    private array $hp_atr;
    private array $def_atr;
    private array $golpe_atr;
    private Objeto $equipoInicial;
    private string $imagen;

    //=====================================CONSTRUCTOR=====================================
    public function __construct
    (
        int $id,
        string $nombre,
        string $descripcion,
        int $dado_golpe,
        Objeto $equipoInicial,
        string $hp_atr,
        string $hp_mod,
        string $def_atr,
        string $def_mod,
        string $golpe_atr,
        string $golpe_mod,
        ?string $imagen = null
    )
    {
        $this->setId($id);
        $this->setNombre($nombre);
        $this->setDescripcion($descripcion);
        $this->setDadoGolpe($dado_golpe);
        $this->setHpAtr([$hp_atr, $hp_mod]);
        $this->setDefAtr([$def_atr, $def_mod]);
        $this->setGolpeAtr([$golpe_atr, $golpe_mod]);
        $this->setEquipoInicial($equipoInicial);
        $this->setImagen($imagen);
    }

    //=====================================MÉTODOS========================================
    //Método para actualizar/añadir la clase en la base de datos
    public function toDatabase(): array
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'descripcion' => $this->getDescripcion(),
            'dado_golpe' => $this->getDadoGolpe(),
            'equipo_inicial' => $this->getEquipoInicial()->getId(),
            'hp_atr' => $this->getHpAtr()[0],
            'hp_mod' => $this->getHpAtr()[1],
            'def_atr' => $this->getDefAtr()[0],
            'def_mod' => $this->getDefAtr()[1],
            'golpe_atr' => $this->getGolpeAtr()[0],
            'golpe_mod' => $this->getGolpeAtr()[1],
        ];
    }

    //Método para sacar una descripción resumida en HTML
    public function toShortHTML(): string
    {
        return '<div class="clase">
            <h3>' . $this->getNombre() . '</h3>
            <p>' . $this->getDescripcion() . '</p>
        </div>';
    }

    //Método para sacar una descripción completa en HTML
    public function toHTML(): string
    {
        return '<div class="clase">
            <h3>' . $this->getNombre() . '</h3>
            <p>' . $this->getDescripcion() . '</p>
            <p><strong>Dados de golpe:</strong> ' . $this->getDadoGolpe() . '</p>
            <p><strong>HP:</strong> ' . $this->getHpAtr()[0] . ' x ' . $this->getHpAtr()[1] . '</p>
            <p><strong>Defensa:</strong> ' . $this->getDefAtr()[0] . ' x ' . $this->getDefAtr()[1] . '</p>
            <p><strong>Golpe:</strong> ' . $this->getGolpeAtr()[0] . ' x ' . $this->getGolpeAtr()[1] . '</p>
        </div>';
    }

    //===================================GETTERS & SETTERS=================================
    //GSNombre
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    //GSDescripcion
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    //GSEquipoInicial
    public function getEquipoInicial()
    {
        return $this->equipoInicial;
    }
    public function setEquipoInicial(Objeto $equipo): self
    {
        $this->equipoInicial = $equipo;
        return $this;
    }

    //GSDadoGolpe
    public function getDadoGolpe(): int
    {
        return $this->dado_golpe;
    }
    public function setDadoGolpe(int $dado): self
    {
        $this->dado_golpe= $dado;
        return $this;
    }

    //GSHpAtr
    public function getHpAtr(): array
    {
        return $this->hp_atr;
    }
    public function setHpAtr(array $datos): self
    {
        $this->hp_atr = $datos;
        return $this;
    }

    //GSDefAtr
    public function getDefAtr(): array
    {
        return $this->def_atr;
    }
    public function setDefAtr(array $datos): self
    {
        $this->def_atr = $datos;
        return $this;
    }

    //GSGolpeAtr
    public function getGolpeAtr(): array
    {
        return $this->golpe_atr;
    }
    public function setGolpeAtr(array $datos): self
    {
        $this->golpe_atr = $datos;
        return $this;
    }

    //GSImagen
    public function getImagen(): string
    {
        return $this->imagen;
    }
    public function setImagen(?string $imagen = null): self
    {
        $this->imagen = $this->getFormattedImg($imagen);
        return $this;
    }
}