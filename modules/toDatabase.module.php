<?php

    //Función para conectarse a la base de datos mediante PDO
    function conectar(): PDO
    {
        $host = 'localhost';
        $db = 'dndmanager';
        $user ='root';
        $pass = '';
        $charset = 'utf8mb4';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        /* INSTALAR composer require vlucas/phpdotenv cuando se migre a LogeCraft
            $host = $_ENV['DB_HOST'];
            $db = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASS'];
            $charset = $_ENV['DB_CHARSET'];
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        */
        try {
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
