<?php

//Clase Personaje: contiene los datos de un personaje.

require_once "Raza.class.php";
require_once "Clase.class.php";
require_once "Inventario.class.php";
class Personaje
{
    private int $id;
    private string $nombre;
    private float $experiencia;
    private Raza $raza;
    private Clase $clase;
    private array $atributos;
    private array $habilidades;
    private Inventario $inventario;
    private array $incursiones;
    private float $dinero;
    private int $puntos_habilidad;


    public function __construct(int $id, String $nombre, float $experiencia, Raza $raza, Clase $clase, Inventario $inventario, array $incursiones = [], float $dinero = 0, int $puntos_habilidad = 0)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->experiencia = $experiencia;
        $this->raza = $raza;
        $this->clase = $clase;
        $this->inventario = $inventario;
        $this->incursiones = $incursiones;
        $this->dinero = $dinero;
        $this->puntos_habilidad = $puntos_habilidad;
    }
}