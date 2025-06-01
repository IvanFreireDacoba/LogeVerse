<?php

//Clase Personaje: contiene los datos de un personaje.
class Personaje implements toDatabase
{
    use Identificable;            //Trait para la identificación de objetos
    private string $nombre;
    private string $historia;
    private int $propietario;
    private int $experiencia;     //Con la experiencia se calcula el nivel
    private Raza $raza;
    private Clase $clase;
    private array $atributos;
    private array $habilidades;
    private Inventario $inventario;
    private array $incursiones;
    private float $dinero;
    private int $puntos_habilidad;
    private string $img_data;     //Imagen del personaje
    private bool $estado;         //Estado del personaje (vivo o muerto)
    private array $on_incursion;  //Incursión en la que está actualmente el personaje
    private int $hp_now;          //Vida actual del personaje
    private int $hp_max;          //Vida máxima del personaje
    private int $def_base;        //Defensa base del personaje sin cambios
    private int $def_mod;         //Modificador de defensa del personaje
    private int $golpe_base;      //Golpe base del personaje sin cambios
    private int $golpe_mod;       //Modificador de golpe del personaje
    private int $lvl_now;         //Nivel actual del personaje
    private array $efectos;       //Efectos que sufre el personaje actualmente

    //=====================================CONSTRUCTOR=====================================
    public function __construct(int $id, int $propietario, Raza $raza, Clase $clase, string $nombre, string $historia, int $experiencia, float $dinero, int $puntos_habilidad, array $habilidades, Inventario $inventario, array $atributos, bool $estado = true, array $incursiones = [], ?string $imagen = null)
    {
        $this->setId($id);
        $this->setNombre($nombre);
        $this->setHistoria($historia);
        $this->setPropietario($propietario);
        $this->setExperiencia($experiencia);
        $this->setRaza($raza);
        $this->setClase($clase);
        $this->setAtributos($atributos);
        $this->setHabilidades($habilidades);
        $this->setInventario($inventario);
        $this->setIncursiones($incursiones);
        $this->setDinero($dinero);
        $this->setPuntosHabilidad($puntos_habilidad);
        $this->setEstado($estado);
        $this->setImgData($imagen);
        $this->setOnIncursion(false);
        $this->setLvlNow();
        $this->setStats();
        $this->setEfectos([]);
    }

    //=====================================MÉTODOS========================================
    //Método para actualizar/añadir el personaje en la base de datos
    public function toDatabase(): array
    {
        return [
            'id' => $this->getId(),
            'propietario' => $this->getPropietario(),
            'raza' => $this->getRaza()->getId(),
            'clase' => $this->getClase()->getId(),
            'nombre' => $this->getNombre(),
            'historia' => $this->getHistoria(),
            'experiencia' => $this->getExperiencia(),
            'dinero' => $this->getDinero(),
            'puntos_habilidad' => $this->getPuntosHabilidad(),
            'estado' => $this->getEstado(),
        ];
    }

    //Método para obtener un resumen de la información del personaje en HTML
    public function toShortHTML(): string
    {
        $html = "<div class='personaje'>";
        $html .= "<img class='pj_img' src='{$this->getImgData()}' alt='Imagen del personaje'><div>";
        $html .= "<p class='pj_name'>{$this->getNombre()}</p>";
        $html .= "<p class='pj_lvl'>Lvl {$this->calcularLvl()}</p>";
        $html .= "<p  class='pj_race'>{$this->getRaza()->getNombre()}</p>";
        $html .= "<p  class='pj_class'>{$this->getClase()->getNombre()}</p>";
        $html .= "</div></div>";
        return $html;
    }

    //Método para obtener la informacion del personaje en formato HTML
    public function toHTML(): string
    {
        $html = "<div class='personaje'>";
        $html .= "<h2>{$this->getNombre()}</h2>";
        $html .= "<p><strong>Raza:</strong> {$this->getRaza()->getNombre()}</p>";
        $html .= "<p><strong>Clase:</strong> {$this->getClase()->getNombre()}</p>";
        $html .= "<p><strong>Nivel:</strong> {$this->calcularLvl()}</p>";
        $html .= "<div class='atributospersonaje'>";
        foreach ($this->getAtributos() as $atributo => $valor) {
            $html .= "<div class='atributo'>";
            $html .= "<p><strong>$atributo:</strong> $valor</p>";
            $html .= "</div>";
        }
        $html .= "<div class='estadisticaspersonaje'>";
        //Pensar en una manera de mostrar las estadisticas del personaje


        /*=================== PENDIENTE ====================*/


        $html .= "</div></div>";
        return $html;
    }

    //Establecer las estadísticas del personaje
    public function setStats(): void
    {
        //Vida
        $this->setHpMax();
        $this->hp_now = $this->getHpMax();

        //Defensa
        $this->setDefBase();
        $this->setDefMod(0);

        //Golpe
        $this->setGolpeBase();
        $this->setGolpeMod(0);
    }

    //Obtener el valor de un atributo del personaje
    public function getAtributo(string $atributo): int
    {
        if (array_key_exists($atributo, $this->atributos)) {
            return $this->atributos[$atributo];
        } else {
            throw new Exception("El atributo " . $atributo . " no existe en el personaje.");
        }
    }

    //Obtener el nivel del personaje
    public function calcularLvl(): int
    {
        $baseExp = 100;
        $growthRate = 1.122;

        //Fórmula para calcular un nivel basado en la experiencia
        //con un crecimiento exponencial
        //Nivel 1 = 0 experiencia
        //Nivel 50 = 100.000 experiencia
        //Nivel 100 = 1.000.000 experiencia
        $nivel = log(($this->getExperiencia() / $baseExp) + 1) / log($growthRate);

        return floor($nivel) + 1;
    }

    //Calcular la experiencia necesaria para subir de nivel
    public function getExpNext(): string
    {
        $baseExp = 100;
        $growthRate = 1.122;

        $nivelActual = $this->calcularLvl();
        //Calcular la experiencia acumulada para el nivel anterior y el actual
        $expNivelAnterior = $baseExp * (pow($growthRate, $nivelActual - 1) - 1);
        $expNivelActual = $baseExp * (pow($growthRate, $nivelActual) - 1);
        //Progreso actual dentro del nivel
        $expProgreso = $this->getExperiencia() - $expNivelAnterior;
        $expParaSubir = $expNivelActual - $expNivelAnterior;

        return intval($expProgreso) . ' / ' . intval($expParaSubir);
    }

    //Subir de nivel el personaje
    public function subirNivel(): void
    {
        /*=================== PENDIENTE ====================*/
    }

    //Funciones de efectos

    //Aplicar un efecto al personaje
    public function applyEfecto(Efecto $efecto): void
    {
        /*=================== PENDIENTE ====================*/
    }

    //Añadir un efecto al personaje
    public function addEfecto(Efecto $efecto): void
    {
        if (!in_array($efecto, $this->efectos)) {
            $this->efectos[] = $efecto;
        } else {
            //Si el efecto ya existe, se actualiza su duración aumentándola
            foreach ($this->efectos as $key => $existingEfecto) {
                if ($existingEfecto->getId() === $efecto->getId()) {
                    $turnosRestantes = $efecto->getTurnosRestantes() + $this->efectos[$key]->getTurnosRestantes();
                    $this->efectos[$key]->setTurnosRestantes($turnosRestantes);
                    break;
                }
            }
        }
    }

    public function dropEfecto(Efecto $efecto): void
    {
        /*=================== PENDIENTE ====================*/
    }

    public function applyStatus(Efecto $efecto): void
    {
        /*=================== PENDIENTE ====================*/
    }

    public function applyBuff(Efecto $efecto, bool $buff = true): void
    {
        /*=================== PENDIENTE ====================*/
    }

    public function takeDamage(int $damage): void
    {
        $this->hp_now -= $damage;
        if ($this->hp_now <= 0) {
            $this->estado = false;
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

    //GSPropietario
    public function getPropietario(): int
    {
        return $this->propietario;
    }
    public function setPropietario(int $propietario): self
    {
        $this->propietario = $propietario;
        return $this;
    }

    //GSExperiencia
    public function getExperiencia(): int
    {
        return $this->experiencia;
    }
    public function setExperiencia(int $experiencia): self
    {
        $this->experiencia = $experiencia;
        return $this;
    }

    //GSRaza
    public function getRaza(): Raza
    {
        return $this->raza;
    }
    public function setRaza(Raza $raza): self
    {
        $this->raza = $raza;
        return $this;
    }

    //GSClase
    public function getClase(): Clase
    {
        return $this->clase;
    }
    public function setClase(Clase $clase): self
    {
        $this->clase = $clase;
        return $this;
    }

    //GSInventario
    public function getInventario(): Inventario
    {
        return $this->inventario;
    }
    public function setInventario(Inventario $inventario): self
    {
        $this->inventario = $inventario;
        return $this;
    }

    //GSIncursiones
    public function getIncursiones(): array
    {
        return $this->incursiones;
    }
    public function setIncursiones(array $incursiones): self
    {
        $this->incursiones = $incursiones;
        return $this;
    }

    //GSDinero
    public function getDinero(): float
    {
        return $this->dinero;
    }
    public function setDinero(float $dinero): self
    {
        $this->dinero = $dinero;
        return $this;
    }

    //GSPuntosHabilidad
    public function getPuntosHabilidad(): int
    {
        return $this->puntos_habilidad;
    }
    public function setPuntosHabilidad(int $puntos_habilidad): self
    {
        $this->puntos_habilidad = $puntos_habilidad;
        return $this;
    }

    //GSEstado
    public function getEstado(): bool
    {
        return $this->estado;
    }
    public function setEstado(bool $estado): self
    {
        $this->estado = $estado;
        return $this;
    }

    //GSImgData
    public function getImgData(): string
    {
        return $this->img_data;
    }
    public function setImgData(?string $img_data = null): self
    {
        $this->img_data = $this->getFormattedImg("/LogeVerse/resources/player/default.png", $img_data);
        return $this;
    }

    //GSOnIncursion
    public function getOnIncursion(): Incursion
    {
        $incursionActual = new Incursion(0, "Ninguna", "No está realizando ninguna incursión");
        if ($this->on_incursion[0]) {
            $incursionActual = $this->on_incursion[1];
        }
        return $incursionActual;
    }
    public function setOnIncursion(bool $on, ?Incursion $incursion = null, ?array $status = null): self
    {
        if ($incursion == null) {
            $incursion = new Incursion(0, "Ninguna", "No está realizando ninguna incursión");
        }

        $this->on_incursion = [$on, $incursion, $status];
        return $this;
    }

    //GSHpMax
    public function getHpMax(): int
    {
        return $this->hp_max;
    }
    public function setHpMax(): self
    {
        $info_atributo = $this->getClase()->getHpAtr();
        try {
            $base = $this->getAtributo($info_atributo[0]) * $info_atributo[1];
        } catch (Exception $e) {
            $base = 0;
        }

        $base += ($this->getLvlNow() * 3);

        $this->hp_max = $base;

        return $this;
    }

    //GSAtributos
    public function getAtributos(): array
    {
        return $this->atributos;
    }
    public function setAtributos(array $atributos): self
    {
        $this->atributos = $atributos;
        return $this;
    }

    //GSHabilidades
    public function getHabilidades(): array
    {
        return $this->habilidades;
    }
    public function setHabilidades(array $habilidades): self
    {
        $this->habilidades = $habilidades;
        return $this;
    }

    //GSLvlNow
    public function getLvlNow(): int
    {
        return $this->lvl_now;
    }
    public function setLvlNow(): self
    {
        $this->lvl_now = $this->calcularLvl();
        return $this;
    }

    //GSDefBase
    public function getDefBase(): int
    {
        return $this->def_base;
    }
    public function setDefBase(): self
    {
        //Buscamos el atributo que la clase del personaje usa para calcular la defensa
        //y tomamos el valor que tiene el personaje en dicho atributo
        try {
            $valorAtributo = $this->getAtributo($this->getClase()->getDefAtr()[0]);
        } catch (Exception $e) {
            $valorAtributo = 1;
        }
        //Multiplicamos el valor del atributo por el modificador de defensa de la clase
        $this->def_base = $valorAtributo * $this->getClase()->getDefAtr()[1];
        return $this;
    }

    //GSDefMod
    public function getDefMod(): int
    {
        return $this->def_mod;
    }
    public function setDefMod(int $def_mod): self
    {
        $this->def_mod = $def_mod;
        return $this;
    }

    //GSGolpeBase
    public function getGolpeBase(): int
    {
        return $this->golpe_base;
    }
    public function setGolpeBase(): self
    {
        //Buscamos el atributo que la clase del personaje usa para calcular el golpe
        //y tomamos el valor que tiene el personaje en dicho atributo
        try {
            $valorAtributo = $this->getAtributo($this->getClase()->getGolpeAtr()[0]);
        } catch (Exception $e) {
            $valorAtributo = 1;
        }
        //Multiplicamos el valor del atributo por el modificador de golpe de la clase
        $this->golpe_base = $valorAtributo * $this->getClase()->getGolpeAtr()[1];
        return $this;
    }

    //GSGolpeMod
    public function getGolpeMod(): int
    {
        return $this->golpe_mod;
    }
    public function setGolpeMod(int $golpe_mod): self
    {
        $this->golpe_mod = $golpe_mod;
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