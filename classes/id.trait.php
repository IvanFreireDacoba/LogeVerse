<?php
trait Identificable
{
    protected int $id;

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    //Obtiene la imagen formateada para ser mostrada en el navegador
    public function getFormattedImg($data = null): string
    {
        //Comprueba si la imagen no es nula; si lo es, devuelve la imagen por defecto
        if(!is_null($data)){
            //Guarda la información de la imagen tras pasarla de binario a base 64
            $base64 = base64_encode($data);

            //Utiliza la clase finfo (FileInfo) para obtener el tipo MIME
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($data);

            //Comprueba que el MIME es de tipo imagen
            if(str_starts_with($mimeType, 'image/')) {
                //Genera el string de la imagen
                $img = "data:$mimeType;base64,$base64";
            } else {
                //Si no es una imagen, devuelve la imagen por defecto
                $img = "../resources/player/default.png";
            }
        } else {
            $img = "../resources/player/default.png";
        }
        return $img;
    }
}