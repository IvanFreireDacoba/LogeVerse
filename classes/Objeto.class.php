<?php

//Clase Objeto: contiene los atributos de un objeto (construido para la base de datos)

//Clase abstracta base para el resto de objetos
abstract class Objeto
{
    private int $id;                //id del objeto
    private string $nombre;         //nombre del objeto
    private array $tipo;            //tipo de objeto (arma, armadura, consumible, material, evento, paquete)
    private string $descripcion;    //descripción del objeto
    private string $imagen;         //imagen del objeto (url o path)
    private float $precio;          //precio del objeto

    //======================================FUNCIONES======================================
    //toDatabase devuelve los datos del objeto que irán a la tabla general
    // de objetos de la base de datos
    public function toDatabase()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'tipo' => $this->tipo,
            'descripcion' => $this->descripcion,
            'imagen' => $this->imagen,
            'precio' => $this->precio,
        ];
    }

    //===================================GETTERS & SETTERS=================================
    //GSId
    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

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

    //GSTipo
    public function getTipo(): array
    {
        return $this->tipo;
    }
    public function setTipo(array $tipo): self
    {
        $this->tipo = $tipo;
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

    //GSimagen
    public function getImagen(): string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;
        return $this;
    }

    //GSprecio
    public function getprecio(): float
    {
        return $this->precio;
    }
    public function setprecio(float $precio): self
    {
        $this->precio = $precio;
        return $this;
    }
}

class Arma extends Objeto
{
    private int $modificador; //modificador de daño base del arma
    private int $material; //material del arma
    private string $tipo; //tipo de arma (cuerpo a cuerpo, a distancia, magicas)
    private bool $doble; //si el arma es de doble mano o no
    private array $atributos; //atributos del arma (daño, [atributo], regeneración)
}

class Armadura extends Objeto
{
    private string $corporal; //parte del cuerpo que protege la armadura
    private int $modificador; //modificador de la armadura
    private int $material; //material de la armadura
    private array $atributos; //atributos de la armadura (vida_max, daño, [atributo], regeneración)
    private array $efecto; //efecto de la armadura sobre otros entes
}

class Consumible extends Objeto
{
    private array $efecto; //efecto del consumible (curacion, vida_max, daño, armadura, [atributo], regeneración)
    private int $duracion; //duración del consumible en turnos
}

//Clase Evento: contiene objetos de evento(!basico) o que no pertenecen al resto de clases(basico)
class Evento extends Objeto
{
    private bool $basico; //si es básico o no, si es básico se puede eliminar del inventario o vernderlo
    private string $uso; //uso del objeto
    private int $evento; //evento durante el cual se puede usar || evento que se activa al tener el objeto en el inventario
}

class Paquete extends Objeto
{
    private array $objetos; //objetos que contiene el paquete
}