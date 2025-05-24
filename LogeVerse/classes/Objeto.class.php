<?php

//Clase Objeto: contiene los atributos de un objeto (construido para la base de datos)

/*Clase abstracta base para el resto de objetos*/
abstract class Objeto implements toDatabase
{
    use Identificable; //Trait para la identificación
    protected string $nombre;         //nombre del objeto
    protected string $tipo;           //tipo de objeto
                                      //(arma, armadura, consumible, material, evento, paquete, agrupacion)
    protected string $descripcion;    //descripción del objeto
    protected string $imagen;         //imagen del objeto (url o path)
    protected float $precio;          //precio del objeto
    protected array $efectos;         //efectos del objeto

    //=====================================CONSTRUCTOR=====================================
    //Constructor de la clase Objeto para utilizar en las clases hijas
    public function __construct
    (
        int $id,
        string $nombre,
        string $tipo,
        string $descripcion,
        float $precio,
        array $efectos = [new Efecto(0, "Ninguno", "No tiene ningún efecto", 0, 0, "none")],
        ?string $imagen = null,
    ) {
        $this->setId($id);
        $this->setNombre($nombre);
        $this->setTipo($tipo);
        $this->setDescripcion($descripcion);
        $this->setImagen($imagen);
        $this->setPrecio($precio);
        $this->setEfectos($efectos);
    }

    //=====================================MÉTODOS========================================
    //toDatabase devuelve los datos del objeto que irán a la tabla general
    // de objetos de la base de datos
    public function toDatabase(): array
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

    //Devuelve información resumida del objeto en HTML
    public function toShortHTML(): string
    {
        return "<p>" . $this->getNombre() . " || " . $this->getPrecio() . "</p>";
    }

    public function toHTML(bool $closed = true): string
    {
        $html = "<div class='objeto'>";
        $html .= "<h3>" . $this->getNombre() . "</h3>";

        if(!is_null($this->getImagen())){
            $html .= "<img src='" . $this->getImagen() . "' alt='Imagen de " . $this->getNombre() . "'>";
        } else {
            $html .= "<img src='../resources/object/base/default.png' alt='Imagen de " . $this->getNombre() . "'>";
        }

        $html .= "<p>" . $this->getDescripcion() . "</p>";
        $html .= "<p>Precio: " . $this->getPrecio() . "</p>";

        $closed ? $html .= "</div>" : $html;

        return $html;
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

    public function setImagen(?string $imagen = null): self
    {
        $this->imagen = $this->getFormattedImg("/LogeVerse/resources/item/default.png", $imagen);
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

    //GSEfectos
    public function getEfectos(): array
    {
        return $this->efectos;
    }
    public function setEfectos(array $efectos): self
    {
        $this->efectos = $efectos;
        return $this;
    }
}

//Clase Arma: contiene objetos equipables con uso en combate
class Arma extends Objeto
{
    private int $modificador;   //modificador de daño base del arma
    private Objeto $material;      //material del arma
    private bool $doble;        //si el arma es de doble mano o no
    private string $combate;    //tipo de arma (cuerpo a cuerpo, a distancia, magicas)

    //=====================================CONSTRUCTOR=====================================
    public function __construct
    (
        int $id,
        string $nombre,
        string $tipo,
        string $descripcion,
        float $precio,
        array $efectos,
        int $modificador,
        Objeto $material,
        string $combate,
        bool $doble = false,
        ?string $imagen = null,
    ) {
        parent::__construct($id, $nombre, $tipo, $descripcion, $precio, $efectos, $imagen);
        $this->setModificador($modificador);
        $this->setMaterial($material);
        $this->setDoble($doble);
        $this->setCombate($combate);
    }

    //=====================================MÉTODOS=========================================
    /*
        Mostrar los atributos que la armadura modifica en HTML
    */
    public function aumentos(): string
    {
        $html = "<div class='aumentos'>";
        foreach ($this->getEfectos() as $efecto) {
            if ($efecto->getTipo() === "buff" OR $efecto->getTipo() === "debuf") {
                $html .= "<p>" . $efecto->getNombre() . ": ";
                //Establecemos la clase y el signo
                $html .= "<a class='" . $efecto->getTipo() . "'>";
                $html .= $efecto->getTipo() === "buff" ? "+" : "-";
                //Calculamos el valor final del efecto tras aplicarle el modificador
                $html .= $efecto->getCantidad() * $this->getModificador() . "</a></p>";
            }
        }
        $html .= "</div>";
        return $html;
    }


    //===================================GETTERS & SETTERS=================================
    //GSModificador
    public function getModificador(): int
    {
        return $this->modificador;
    }
    public function setModificador(int $modificador): self
    {
        $this->modificador = $modificador;
        return $this;
    }

    //GSMaterial
    public function getMaterial(): Objeto
    {
        return $this->material;
    }
    public function setMaterial(Objeto $material): self
    {
        $this->material = $material;
        return $this;
    }

    //GSDoble
    public function getDoble(): bool
    {
        return $this->doble;
    }
    public function setDoble(bool $doble): self
    {
        $this->doble = $doble;
        return $this;
    }

    //GSCombate
    public function getCombate(): string
    {
        return $this->combate;
    }
    public function setCombate(string $combate): self
    {
        $this->combate = $combate;
        return $this;
    }

}

//Clase Armadura: contiene objetos equipables que protegen al personaje
class Armadura extends Objeto
{
    private string $corporal; //parte del cuerpo que protege la armadura
    private int $modificador; //modificador de la armadura
    private Objeto $material; //material de la armadura

    //=====================================CONSTRUCTOR=====================================
    public function __construct
    (
        int $id,
        string $nombre,
        string $tipo,
        string $descripcion,
        float $precio,
        array $efectos,
        string $corporal,
        int $modificador,
        Objeto $material,
        ?string $imagen = null,
    ) {
        parent::__construct($id, $nombre, $tipo, $descripcion, $precio, $efectos, $imagen);
        $this->setCorporal($corporal);
        $this->setModificador($modificador);
        $this->setMaterial($material);
    }

    //=====================================MÉTODOS=========================================
    /*
        Mostrar los atributos que la armadura modifica en HTML
    */
    public function aumentos(): string
    {
        $html = "<div class='aumentos'>";
        foreach ($this->getEfectos() as $efecto) {
            if ($efecto->getTipo() === "buff" OR $efecto->getTipo() === "debuf") {
                $html .= "<p>" . $efecto->getNombre() . ": ";
                //Establecemos la clase y el signo
                $html .= "<a class='" . $efecto->getTipo() . "'>";
                $html .= $efecto->getTipo() === "buff" ? "+" : "-";
                //Calculamos el valor final del efecto tras aplicarle el modificador
                $html .= $efecto->getCantidad() * $this->getModificador() . "</a></p>";
            }
        }
        $html .= "</div>";
        return $html;
    }


    //===================================GETTERS & SETTERS=================================
    //GSCorporal
    public function getCorporal(): string
    {
        return $this->corporal;
    }
    public function setCorporal(string $corporal): self
    {
        $this->corporal = $corporal;
        return $this;
    }

    //GSModificador
    public function getModificador(): int
    {
        return $this->modificador;
    }
    public function setModificador(int $modificador): self
    {
        $this->modificador = $modificador;
        return $this;
    }

    //GSMaterial
    public function getMaterial(): Objeto
    {
        return $this->material;
    }
    public function setMaterial(Objeto $material): self
    {
        $this->material = $material;
        return $this;
    }

}

//Clase Consumible: contiene objetos que se gastan al usarse
class Consumible extends Objeto
{
    private int $usos; //cantidad de veces que puede usarse un consumible

    //=====================================CONSTRUCTOR=====================================
    public function __construct
    (
        int $id,
        string $nombre,
        string $tipo,
        string $descripcion,
        float $precio,
        array $efectos,
        int $usos,
        ?string $imagen = null,
    ) {
        parent::__construct($id, $nombre, $tipo, $descripcion,$precio, $efectos, $imagen);
        $this->setUsos($usos);
    }

    //=====================================MÉTODOS=========================================
    /*
        Mostrar el objeto base en HTML
    */
    public function toHTML(bool $closed = true): string
    {
        $html = parent::toHTML(false);
        $html .= "<p>Efectos: ";
        foreach ($this->getEfectos() as $efecto) {
            $html .= $efecto->toShortHTML . "<br>";
        }
        $html .= "</p></div>";
        return $html;        
    }

    //===================================GETTERS & SETTERS=================================
    //GSUsos
    public function getUsos(): int
    {
        return $this->usos;
    }
    public function setUsos(int $usos): self
    {
        $this->usos = $usos;
        return $this;
    }
}

//Clase Evento: contiene objetos de evento(!basico) o que no pertenecen al resto de clases(basico)
class Base extends Objeto
{
    private bool $basico; //si es básico o no, si es básico se puede eliminar del inventario o vernderlo
    private string $uso; //uso del objeto
    private array $eventos; //evento durante el cual se puede usar || evento que se activa al tener el objeto en el inventario

    //=====================================CONSTRUCTOR=====================================
    public function __construct
    (
        int $id,
        string $nombre,
        string $tipo,
        string $descripcion,
        float $precio,
        array $efectos,
        bool $basico,
        string $uso,
        array $eventos,
        ?string $imagen = null,
    ) {
        parent::__construct($id, $nombre, $tipo, $descripcion,$precio, $efectos, $imagen);
        $this->setBasico($basico);
        $this->setUso($uso);
        $this->setEventos($eventos);
    }

    //=====================================MÉTODOS=========================================
    /*
        Mostrar el objeto base en HTML
    */
    public function toHTML(bool $closed = true): string
    {
        $html = parent::toHTML(false);
        $html .= "<p>Uso: " . $this->getUso() . "</p>";
        $html .= "</div>";
        return $html;        
    }

    //===================================GETTERS & SETTERS=================================
    //GSBasico
    public function getBasico(): bool
    {
        return $this->basico;
    }
    public function setBasico(bool $basico): self
    {
        $this->basico = $basico;
        return $this;
    }

    //GSUso
    public function getUso(): string
    {
        return $this->uso;
    }
    public function setUso(string $uso): self
    {
        $this->uso = $uso;
        return $this;
    }

    //GSEventos
    public function getEventos(): array
    {
        return $this->eventos;
    }
    public function setEventos(array $eventos): self
    {
        $this->eventos = $eventos;
        return $this;
    }

}

//Clase Paquete: contiene agrupaciones de objetos 
class Paquete extends Objeto
{
    private Objeto $objeto1;
    private Objeto $objeto2;
    private bool $ambos; //si el paquete otorga ambos objetos o solo uno

    //=====================================CONSTRUCTOR=====================================
    public function __construct
    (
        int $id,
        string $nombre,
        string $tipo,
        string $descripcion,
        float $precio,
        array $efectos,
        Objeto $objeto1,
        Objeto $objeto2,
        bool $ambos = false,
        ?string $imagen = null,
    ) {
        parent::__construct($id, $nombre, $tipo, $descripcion,$precio, $efectos, $imagen);
        $this->setObjeto1($objeto1);
        $this->setObjeto2($objeto2);
        $this->setAmbos($ambos);
    }

    //=====================================MÉTODOS=========================================
    /*
    Desglosar un paquete, modifica un array de entrada con 2 objetos y un booleano
    Funciona de manera recursiva analizando el tipo de objeto y añadiéndolo al array
    según dicho tipo.
    */
    public function desglosar(array &$objetos): void
    {
        //Array temporal para agrupar los objetos que se obtienen juntos (ambos === true)
        $tmp_array = [];
        
        //Se obtienen los objetos del paquete
        $obj1 = $this->getObjeto1();
        $obj2 = $this->getObjeto2();

        if ($this->getAmbos()) {
            //Si se obtienen ambos se añaden por separado
            

            //Se añade el objeto1 siempre que no sea una agrupación
            if ($obj1->getTipo() !== "agrupacion") {
                $objetos[] = $obj1;
                //Si es una agrupación, DEBE ser un paquete
            } elseif ($obj1 instanceof Paquete) {
                //Por ello se desglosa en el array temporal, y este se
                //añade al array de objetos
                $obj1->desglosar($tmp_array);
                $objetos = array_merge($objetos, $tmp_array);
            }

            //Misma lógica para el objeto2
            if ($obj2->getTipo() !== "agrupacion") {
                $objetos[] = $obj2;
            } elseif ($obj2 instanceof Paquete) {
                $obj2->desglosar($tmp_array);
                $objetos = array_merge($objetos, $tmp_array);
            }

            //Si los objetos se obtienen por separado se añaden en un array conjunto
        } else {
            //Array de grupo de dos grupos de objetos que NO se obtienen juntos
            $arr_group = [];

            //Análisis del objeto1
            //Si no es una agrupación, se añade al array temporal
            if ($obj1->getTipo() !== "agrupacion") {
                $tmp_array[] = $obj1;
                //Si es una agrupación, DEBE ser un paquete
                //por ello se desglosa en el array temporal
            } elseif ($obj1 instanceof Paquete) {
                $obj1->desglosar($tmp_array);
            }

            //Guardamos los datos en el array de grupo
            $arr_group = $tmp_array;
            $tmp_array = []; //Reiniciamos el array temporal para el siguiente objeto

            //Análisis del objeto2, igual que el objeto1
            if ($obj2->getTipo() !== "agrupacion") {
                $tmp_array[] = $obj2;
            } elseif ($obj2 instanceof Paquete) {
                $obj2->desglosar($tmp_array);
            }

            //Guardamos los datos en el array de grupo
            $arr_group = $tmp_array;

            //Ahora mismo el array de grupo tiene los dos grupos de objetos
            //que se obtienen por separado
            $objetos = array_merge($objetos, $arr_group);
        }
    }

    public function mostrarContenido(): string
    {
        $contenido = "<h3>Contenido del paquete</h3><ul>";

        $this->desglosar($objetos);
        foreach ($objetos as $objeto) {
            $contenido .= "<li>" . $objeto->toShortHTML() . "</li>";
        }

        $contenido .= "</ul>";
        return $contenido;
    }

    //===================================GETTERS & SETTERS=================================
    //GSObjeto1
    public function getObjeto1(): Objeto
    {
        return $this->objeto1;
    }
    public function setObjeto1(Objeto $objeto1): self
    {
        $this->objeto1 = $objeto1;
        return $this;
    }

    //GSObjeto2
    public function getObjeto2(): Objeto
    {
        return $this->objeto2;
    }
    public function setObjeto2(Objeto $objeto2): self
    {
        $this->objeto1 = $objeto2;
        return $this;
    }

    //GSAmbos
    public function getAmbos(): bool
    {
        return $this->ambos;
    }
    public function setAmbos(bool $ambos): self
    {
        $this->ambos = $ambos;
        return $this;
    }
}