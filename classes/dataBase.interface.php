<?php

    interface toDatabase

    //Interfaz para la conversión de objetos a arrays para la base de datos
    //Contiene los métodos que deben implementar las clases que necesiten convertir sus atributos
    
    //=====================================MÉTODOS========================================
    {
        public function toDatabase(): array;
        public function toHTML(): string;
        public function toShortHTML(): string;
    }