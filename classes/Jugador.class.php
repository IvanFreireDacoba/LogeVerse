<?php

//Clase Jugador: contiene los datos de un jugador.
//El array personajes contiene los personajes asociados a dicho jugador (Personaje::$id).
class Jugador
{
    use Identificable; //Trait para la identificación
    private string $nombre;
    private string $correo;
    private int $puntos;
    private array $personajes;
    private string $img_data;
    private bool $admin = false;
    private array $propuestas;
    private int $notificaciones;


    //=====================================CONSTRUCTOR=====================================
    public function __construct(int $id, string $nombre, string  $correo, int $puntos, int $notificaciones , array $personajes = [], $propuestas = [], ?string $imagen = null)
    {
        $this->setId($id);
        $this->setNombre($nombre);
        $this->setCorreo($correo);
        $this->setNotificaciones($notificaciones);
        $this->setPropuestas($propuestas);
        $this->setPuntos($puntos);
        $this->setPersonajes($personajes);
        $this->setPersonajes($propuestas);
        $this->setImgData($imagen);
    }

    //=====================================MÉTODOS========================================
    //Guardar jugador en la base de datos
    public function guardar(): array
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'correo' => $this->getCorreo(),
        ];
    }

    //Cambia entre administrador y no administrador
    public function changeAdmin(): void
    {
        $this->getAdmin() ? $this->setAdmin(false) : $this->setAdmin(true);
        return;
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

    //GSCorreo
    public function getCorreo(): string
    {
        return $this->correo;
    }
    public function setCorreo(string $correo): self
    {
        $this->correo = $correo;
        return $this;
    }

    //GSPuntos
    public function getPuntos(): int
    {
        return $this->puntos;
    }
    public function setPuntos(int $puntos): self
    {
        $this->puntos = $puntos;
        return $this;
    }

    //GSPersonajes
    public function getPersonajes(): array
    {
        return $this->personajes;
    }
    public function setPersonajes(array $personajes): self
    {
        $this->personajes = $personajes;
        return $this;
    }

    //GSAdmin
    public function getAdmin(): bool
    {
        return $this->admin;
    }
    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;
        return $this;
    }

    //GSImgData
    public function getImgData(): string
    {
        return $this->img_data;
    }
    public function setImgData(?string $img_data): self
    {
        $this->img_data = $this->getFormattedImg($img_data);
        return $this;
    }

    //GSPropuestas
    public function getPropuestas(): array
    {
        return $this->propuestas;
    }
    public function setPropuestas(array $propuestas): self
    {
        $this->propuestas = $propuestas;
        return $this;
    }

    //GSNotificaciones
    public function getNotificaciones(): int
    {
        return $this->notificaciones;
    }
    public function setNotificaciones(int $notificaciones): self
    {
        $this->notificaciones = $notificaciones;
        return $this;
    }
}