<?php

//Clase Clase: contiene la información de una clase
//Fuente: https://nivel20.com/games/dnd-5/professions
class Clase
{
    use Identificable; //Trait para la identificación
    private string $nombre;
    private string $descripcion;
    private int $puntos_golpe;
    private array $hp_atr;
    private array $def_atr;
    private array $golpe_atr;
    private Objeto $equipoInicial;

    //=====================================CONSTRUCTOR=====================================
    public function __construct(int $id, string $nombre, string $descripcion, int $ptos_golpe, Objeto $equipoInicial){
        $this->setId($id);
        $this->setNombre($nombre);
        $this->setDescripcion($descripcion);
        $this->setPuntosGolpe($ptos_golpe);
        $this->setEquipoInicial($equipoInicial);
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

    //GSPuntosGolpe
    public function getPuntosGolpe(): int
    {
        return $this->puntos_golpe;
    }
    public function setPuntosGolpe(int $puntos): self
    {
        $this->puntos_golpe= $puntos;
        return $this;
    }

    //GSHpAtr
    public function getHpAtr(): array
    {
        return $this->hp_atr;
    }
    public function setHpAtr(string $atributo, int $valor): self
    {
        $this->hp_atr = [$atributo, $valor];
        return $this;
    }

    //GSDefAtr
    public function getDefAtr(): array
    {
        return $this->def_atr;
    }
    public function setDefAtr(string $atributo, int $valor): self
    {
        $this->def_atr = [$atributo, $valor];
        return $this;
    }

    //GSGolpeAtr
    public function getGolpeAtr(): array
    {
        return $this->golpe_atr;
    }
    public function setGolpeAtr(string $atributo, int $valor): self
    {
        $this->golpe_atr = [$atributo, $valor];
        return $this;
    }
}