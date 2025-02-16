<?php

//Clase Inventario: contiene un id asociado al personaje y un array de ids de objetos
//Métodos:
//          addItem -> añade un item al array de objetos
//          removeItem -> elimina SOLO un item del array de objetos

require_once "Objeto.class.php";
class Inventario
{
    private int $id;
    private array $objetos;

    //=====================================CONSTRUCTOR=====================================
    public function __construct(int $id, array $objetos){
        $this->id = $id;
        $this->objetos = $objetos;
    }

    //======================================FUNCIONES======================================
    //Añadir item
    public function addItem(Objeto $item){
        array_push($this->objetos[], $item);
        sort($this->objetos);
    }

    public function removeItem(int $id = null, Objeto $item = null): bool{
        if(is_null($id)){
            $index = array_search($item, $this->objetos);
        } else {
            $index = array_search($id, Objeto($this->objetos)->getId());
        }
        if($index !== false){
            unset($this->objetos[$index]);
        }
        return $index !== false;
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

    //GSObjetos
    public function getObjetos(): array
    {
        return $this->objetos;
    }
    public function setObjetos(array $objetos): self
    {
        $this->objetos = $objetos;
        return $this;
    }
}