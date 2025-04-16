<?php

//Clase Inventario: contiene un id asociado al personaje y un array de ids de objetos
//Métodos:
//          addItem -> añade un item al array de objetos
//          removeItem -> elimina SOLO un item del array de objetos
//          useItem -> usa un item del array de objetos, si es consumible lo elimina del inventario
//          searchItem -> busca un item en el array de objetos, devuelve true o false
class Inventario
{
    use Identificable; //Trait para la identificación
    private array $objetos;

    //=====================================CONSTRUCTOR=====================================
    public function __construct(int $id, array $objetos, array $cantidades)
    {
        $this->setId($id);
        $this->setObjetos($objetos, $cantidades);
    }

    //=====================================MÉTODOS========================================
    //Añadir item
    public function addItem(Objeto $item, int $cantidad = 1): void
    {
        $this->objetos[] = [$item, $cantidad];
    }

    //Eliminar item
    public function removeItem(Objeto $item): bool
    {
        $located = false;
        if ($this->searchItem($item, $index)) {
            //El unset puede generar espacios vacíos en el array
            unset($this->objetos[$index]);
            //Reindexar el array para evitar espacios vacíos
            $this->objetos = array_values($this->objetos);
            $located = true;
        }
        return $located;
    }

    //Usar item
    //Los items de tipo consumible tienen su cantidad en valores negativos
    //por ello se debe gestionar el tratamiento de los mismos correctamente
    public function useItem(Objeto $item, string &$output = null): bool
    {
        $located = false;
        $index = -1;
        $quantity = 0;
        if ($this->searchItem($item, $index)) {
            $located = true;
            $quantity = abs($this->getCantidad($index));
            $quantity--;
            if ($quantity === 0) {
                $this->removeItem($item);
                $output = "Consumido: " . $item->getNombre();
            } else {
                if ($item instanceof Consumible) {
                    $quantity = -$quantity;
                    $this->setCantidad($quantity, $index);
                } else {
                    $this->setCantidad($quantity, $index);
                }
                $output = "Consumido: " . $item->getNombre() . "\nCantidad restante: " . abs($quantity);
            }
        } else {
            $output = "El item no se encuentra en el inventario";
        }
        return $located;
    }

    //Buscar item
    public function searchItem(Objeto $item, int &$id = -1): bool
    {
        $located = false;
        for ($i = 0; $i < count($this->objetos); $i++) {
            if ($this->objetos[$i][0]->getId() === $item->getID()) {
                $located = true;
                $id = $i;
                break;
            }
        }
        return $located;
    }

    //Obtener Cantidad de un item
    public function getCantidad(int $index): int
    {
        return $this->objetos[$index][1];
    }

    //Setear la cantidad de un item
    public function setCantidad(int $cantidad, int $index): self
    {
        $this->objetos[$index][1] = $cantidad;
        return $this;
    }

    //===================================GETTERS & SETTERS=================================
    //GSObjetos
    public function getObjetos(): array
    {
        return $this->objetos;
    }
    public function setObjetos(array $objetos, array $cantidades): self
    {
        //IMPORTANTE: Puede borrar la lista de objetos si se setea, utilizar removeItem()
        if (count($objetos) == count($cantidades)) {
            for ($i = 0; $i < count($objetos); $i++) {
                $this->objetos[$i] = [$objetos[$i], $cantidades[$i]];
            }
        }
        return $this;
    }
}