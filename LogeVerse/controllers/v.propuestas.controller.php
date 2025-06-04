<?php
$title = "Propuestas";
if (isset($_SESSION["usuario"])) {
    if (isset($_SESSION["POST"])) {
        foreach ($_SESSION["POST"] as $key => $value) {
            $$key = $value;
        }
        unset($_SESSION["POST"]);
    }
    //Establecemos unas variables globales de objetos, efectos y atributos
    try {
        $pdo = conectar();
        //Objetos
        $stmt = $pdo->prepare("SELECT objeto.id as id, nombre, descripcion, precio, obj1, obj2, ambos, imagen_objeto.img_data as imagen FROM paquete JOIN objeto ON paquete.id = objeto.id LEFT JOIN imagen_objeto ON objeto.id = imagen_objeto.id_objeto;");
        $stmt->execute();
        $datos_objetos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $objetos = [];
        foreach ($datos_objetos as $objeto) {
            $obj = new Paquete(
                $objeto["id"],
                $objeto["nombre"],
                $objeto["tipo"],
                $objeto["descripcion"],
                $objeto["precio"],
                $objeto["efectos"],
                $objeto["obj1"],
                $objeto["obj2"],
                $objeto["ambos"],
                $objeto["imagen"],
            );
            if ($obj !== null) {
                $contenido = [];
                $obj->desglosar($contenido);
                $objetos[] = [$obj, $contenido];
            }
        }
        //Efectos
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT id, nombre, descripcion, cantidad, duracion, tipo FROM efecto ORDER BY nombre;");
        $stmt->execute();
        $efectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Atributos
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT id, nombre, descripcion FROM atributo;");
        $stmt->execute();
        $atributos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Habilidades
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT id, nombre, descripcion, tipo, nivel FROM habilidad ORDER BY nivel;");
        $stmt->execute();
        $habilidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Pasivas
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT id, nombre, descripcion FROM pasiva ORDER BY nombre;");
        $stmt->execute();
        $pasivas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Idiomas
        $stmt->closeCursor();
        $stmt = $pdo->prepare("SELECT id, nombre, descripcion, id_pasiva FROM idioma ORDER BY nombre;");
        $stmt->execute();
        $idiomas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo = null;
    } catch (Exception $e) {
        $objetos = [];
        $efectos = [];
        $atributos = [];
        $habilidades = [];
        $pasivas = [];
        $idiomas = [];
    }
    require 'LogeVerse/views/propuestas/propuestas.php';
} else {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location:  " . url_init . "/LogeVerse/inicio");
    exit;
}