<?php

//Clase Clase: contiene la información de una clase
//Fuente: https://nivel20.com/games/dnd-5/professions

require_once "./Inventario.class.php";
class Clase
{
    private int $id;
    private string $nombre;
    private string $descripcion;
    private int $puntos_golpe;
    private array $equipoInicial;
    private array $multiclase;

    //No tienen setter ya que son valores que NUNCA deben ser cambiados,
    //solo leidos y cargados al constructor desde la base de datos

    public function __construct(int $id, string $nombre, string $descripcion){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }

    //GSId
    public function getId()
    {
        return $this->id;
    }

    //GSNombre
    public function getNombre()
    {
        return $this->nombre;
    }

    //GSDescripcion
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    //GSEquipoInicial
    public function getEquipoInicial()
    {
        return $this->equipoInicial;
    }

    //GSPuntosGolpe
    public function getPuntosGolpe(): int
    {
        return $this->puntos_golpe;
    }

    //GSMulticlase
    public function getMulticlase()
    {
        return $this->multiclase;
    }
}