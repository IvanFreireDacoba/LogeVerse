<?php

include_once "../classes/include_classes.php";
include_once "./hydrators.module.php";


function refrescarUsuario(PDO $pdo, int $id_usuario): Jugador
{
    //Obtenemos y guardamos el jugador
    $stmt = $pdo->prepare("SELECT * FROM jugador LEFT JOIN imagen_perfil ON jugador.id = imagen_perfil.id__jugador WHERE jugador.id = :id_usuario;");
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    //Obtenemos las propuestas realizadas por el jugador
    $stmt = $pdo->prepare("SELECT * FROM propuestas WHERE propuestas.id_jugador = :id_jugador ORDER BY propuestas.fecha;");
    $stmt->bindParam(':id_jugador', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $data["propuestas"] = [];
    $data["propuestas"] = $stmt->fetchAll(PDO::FETCH_FUNC, function ($id_prop, $tipo, $aceptado, $fecha) {
        return [$id_prop, $tipo, $aceptado, $fecha];
    });
    //Obtenemos los personajes del jugador
    $stmt = $pdo->prepare("SELECT id FROM personaje WHERE personaje.propietario = :id_jugador;");
    $stmt->bindParam(':id_jugador', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $personajes = [];
    //Guardamos cada personaje en el array de personajes
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $id_personaje) {
        $personajes[] = refrescarPersonaje($pdo, $id_personaje);
    }
    $data["personajes"] = $personajes;
    //Devolvemos el objeto jugador construido con todos sus parámetros
    return hydrateJugador($data);
}

function refrescarPersonaje(PDO $pdo, $id_personaje): Personaje
{
    $stmt = $pdo->prepare("SELECT * FROM personaje LEFT JOIN imagen_personaje ON personaje.id = imagen_personaje.id_personaje WHERE personaje.propietario = :id_personaje;");
    $stmt->bindParam(':id_personaje', $id_personaje, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    //Construimos la raza del personaje
    $id_raza = $data["raza"];
    $data["raza"] = refrescarRaza($pdo, $id_raza);
    //Construimos la clase del personaje
    $id_clase = $data["clase"];
    $data["clase"] = refrescarClase($pdo, $id_clase);
    //Construimos el inventario del personaje
    $data["Inventario"] = refrescarInventario($pdo, $id_personaje);
    //Buscamos las habilidades del personaje
    $data["habilidades"] = refrescarHabilidades($pdo, $id_personaje, $id_clase);
    //Buscamos los atributos del personaje
    $data["atributos"] = refrescarAtributos($pdo, $id_personaje);
    //Buscamos las incursiones en las que ha participado el personaje
    $data["incursiones"] = refrescarIncursiones($pdo, $id_personaje);
    //Devolver el personaje hidratado con los datos
    return hydratePersonaje($data);
}

function refrescarRaza(PDO $pdo, int $id_raza): Raza
{
    //Obtenemos los datos de la raza
    $stmt = $pdo->prepare("SELECT * FROM raza LEFT JOIN imagen_raza ON raza.id = imagen_raza.id_raza WHERE raza.id = :id_raza;");
    $stmt->bindParam("id_raza", $id_raza, PDO::PARAM_INT);
    $stmt->execute();
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);
    //Obtenemos las pasivas de la raza
    $stmt = $pdo->prepare("SELECT pasiva.id, pasiva.nombre, pasiva.descripcion FROM raza JOIN pasiva_raza JOIN pasiva ON raza.id = pasiva_raza.id_raza AND pasiva_raza.id_pasiva = pasiva.id WHERE pasiva_raza.id_raza = :id_raza;");
    $pasivas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //Construimos las pasivas y las añadimos a $datos["pasivas"]
    $datos["pasivas"] = [];
    foreach ($pasivas as $pasiva) {
        $datosPasiva = [];
        $datosPasiva["id"] = $pasiva["id"];
        $datosPasiva["nombre"] = $pasiva["nombre"];
        $datosPasiva["descripcion"] = $pasiva["descripcion"];
        $datosPasiva["efectos"] = obtenerEfectosPasiva($pdo, $pasiva["id"]);
        $datos["pasivas"] = hydratePasiva($datosPasiva);
    }
    //Obtenemos y construimos los atributos (nombre y cantidad) de la raza
    $stmt = $pdo->prepare("SELECT atributo.nombre, atributo_raza.cantidad FROM atributo_raza JOIN atributo ON atributo_raza.id_atributo = atributo.id WHERE atributo_raza.id_raza = :id_raza;");
    $stmt->bindParam("id_raza", $id_raza, PDO::PARAM_INT);
    $stmt->execute();
    $datos["atributos"] = [];
    $datos["cantidades"] = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $atributo) {
        $datos["atributos"] = $atributo["nombre"];
        $datos["cantidades"] = $atributo["cantidad"];
    }
    //Obtenemos los idiomas de la raza
    $stmt = $pdo->prepare("SELECT idioma.* FROM idioma JOIN idioma_raza JOIN raza ON idioma.id = idioma_raza.id_idioma AND idioma_raza.id_raza = raza.id WHERE raza.id = :id_raza;");
    $stmt->bindParam("id_raza", $id_raza, PDO::PARAM_INT);
    $stmt->execute();
    $datos["idiomas"] = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $idioma) {
        $datos["idiomas"] = new Idioma(
            $idioma["id"],
            $idioma["nombre"],
            $idioma["descripcion"]
        );
    }
    //Devolver la raza hidratada con los datos
    return hydrateRaza($datos);
}

function refrescarClase(PDO $pdo, int $id_clase): Clase
{
    //Recogemos los datos de la clase
    $stmt = $pdo->prepare("SELECT * FROM clase LEFT JOIN imagen_clase ON clase.id = imagen_clase.id_clase WHERE id = :id_clase");
    $stmt->bindParam("id_clase", $id_clase, PDO::PARAM_INT);
    $stmt->execute();
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);
    //Obtener el objeto inicial de la clase
    $stmt = $pdo->prepare("SELECT objeto.id, objeto.tipo FROM objeto LEFT JOIN clase ON objeto.id = clase.equipo_inicial WHERE clase.id = :id_clase;");
    $stmt->bindParam("id_clase", $id_clase, PDO::PARAM_INT);
    $stmt->execute();
    $objeto = $stmt->fetch(PDO::FETCH_ASSOC);
    $objeto = obtenerObjeto($pdo, $objeto["id"], $objeto["tipo"]);
    return new Clase(
        $id_clase,
        $datos["nombre"],
        $datos["nombre"],
        $datos["nombre"],
        $datos["nombre"],
        $datos["nombre"],
        $datos["nombre"],
        $datos["nombre"],
        $datos["nombre"],
        $datos["nombre"],
        $datos["nombre"],
        $datos["img_data"],
    );
}

function refrescarInventario(PDO $pdo, int $id_jugador): Inventario
{
    $stmt = $pdo->prepare("SELECT * FROM inventario WHERE inventario.id_personaje = :id_jugador;");
    $stmt->bindParam("id_jugador", $id_jugador, PDO::PARAM_INT);
    $stmt->execute();
    $objetos = [];
    $cantidades = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $dato) {
        $objetos = obtenerObjeto($pdo, $dato["id_objeto"], false);
        $cantidades = $dato["cantidad"];
    }

    return new Inventario(
        $id_jugador,
        $objetos,
        $cantidades,
    );
}

function refrescarHabilidades(PDO $pdo, int $id_jugador, int $id_clase): array
{
    $habilidades = [];
    $stmt = $pdo->prepare("SELECT habilidad.id FROM habilidad JOIN clase_habilidad JOIN clase ON clase.id = clase_habilidad.id_clase AND clase_habilidad.id_habilidad = habilidad.id WHERE clase.id = :id_clase;");
    $stmt->bindParam("id_clase", $id_clase, PDO::PARAM_INT);
    $stmt->execute();
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $idHabilidad) {
        $habilidades = obtenerHabilidad($pdo, $idHabilidad);
    }
    return $habilidades;
}

function refrescarAtributos(PDO $pdo, int $id_personaje): array
{
    $atributos = [];
    $stmt = $pdo->prepare("SELECT atributo.nombre, atributo_personaje.cantidad FROM atributo JOIN atributo_personaje ON atributo.id = atributo_personaje.id_personaje WHERE atributo_personaje.id_personaje = :id_personaje;");
    $stmt->bindParam("id_personaje", $id_personaje);
    $stmt->execute();
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $atr) {
        $atributos = [$atr["nombre"], $atr["cantidad"]];
    }
    return $atributos;
}

function refrescarIncursiones($pdo, $id_personaje): array
{
    $incursiones = [];
    $stmt = $pdo->prepare("SELECT * FROM incursion JOIN incursion_personaje ON incursion.id = incursion_personaje.id_incursion WHERE incursion_personaje.id_personaje = :id_personaje;");
    $stmt->bindParam("id_personaje", $id_personaje, PDO::PARAM_INT);
    $stmt->execute;
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $incursion) {
        $incursiones = [
            new Incursion(
                $incursion["id"],
                $incursion["nombre"],
                $incursion["hsitoria"],
            ),
            $incursion["sobrevive"],
            $incursion["salud"],
            $incursion["finalizada"],
        ];
    }
    return $incursiones;
}

function obtenerEfectosPasiva(PDO $pdo, int $id_pasiva): array
{
    $efectos = [];
    $stmt = $pdo->prepare("SELECT efecto.* FROM efecto JOIN efecto_pasiva JOIN pasiva ON efecto.id = efecto_pasiva.id_efecto AND pasiva.id = efecto_pasiva.id_pasiva WHERE pasiva.id = :id_pasiva;");
    $stmt->bindParam("id:pasiva", $id_pasiva, PDO::PARAM_INT);
    $stmt->execute();
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $efecto) {
        $efectos = new Efecto(
            $efecto["id"],
            $efecto["nombre"],
            $efecto["descripcion"],
            $efecto["cantidad"],
            $efecto["duracion"],
            $efecto["tipo"],
        );
    }
    return $efectos;
}

function obtenerObjeto(PDO $pdo, int $id, $tipo): Objeto
{
    if ($tipo === false) {
        $stmt = $pdo->prepare("SELECT tipo FROM objeto WHERE id = :id_objeto;");
        $stmt->bindParam("id_objeto", $id, PDO::PARAM_INT);
        $stmt->execute();
        $tipo = $stmt->fetchColumn();
    }
    $stmt = $pdo->prepare("SELECT efecto.* FROM efecto JOIN efecto_objeto ON efecto.id = efecto_objeto.id_objeto WHERE efecto_objeto.id_objeto = :id_objeto");
    $stmt->bindParam("id_objeto", $id, PDO::PARAM_INT);
    $stmt->execute();
    $efectos = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $efecto) {
        $efectos = new Efecto(
            $efecto["id"],
            $efecto["nombre"],
            $efecto["descripcion"],
            $efecto["cantidad"],
            $efecto["duracion"],
            $efecto["tipo"],
        );
    }
    switch ($tipo) {
        case "arma": {
            $stmt = $pdo->prepare("SELECT * FROM objeto JOIN arma JOIN imagen_objeto ON objeto.id = arma.id AND objeto.id = imagen_objeto.id_objeto WHERE objeto.id = :id_objeto;");
            $stmt->bindParam("id_objeto", $id, PDO::PARAM_INT);
            $stmt->execute();
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            $objeto = new Arma(
                $id,
                $datos["nombre"],
                "arma",
                $datos["descripcion"],
                $datos["img_data"],
                $datos["precio"],
                $efectos,
                $datos["modificador"],
                $datos["material"],
                $datos["combate"],
                $datos["doble"],
            );
            break;
        }
        case "armadura": {
            $stmt = $pdo->prepare("SELECT * FROM objeto JOIN armadura JOIN imagen_objeto ON objeto.id = armadura.id AND objeto.id = imagen_objeto.id_objeto WHERE objeto.id = :id_objeto;");
            $stmt->bindParam("id_objeto", $id, PDO::PARAM_INT);
            $stmt->execute();
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            $objeto = new Armadura(
                $id,
                $datos["nombre"],
                "armadura",
                $datos["descripcion"],
                $datos["img_data"],
                $datos["precio"],
                $efectos,
                $datos["corporal"],
                $datos["modificador"],
                $datos["material"],
            );
            break;
        }
        case "base": {
            $stmt = $pdo->prepare("SELECT * FROM objeto JOIN base JOIN imagen_objeto ON objeto.id = base.id AND objeto.id = imagen_objeto.id_objeto WHERE objeto.id = :id_objeto;");
            $stmt->bindParam("id_objeto", $id, PDO::PARAM_INT);
            $stmt->execute();
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            //Recogermos los eventos
            $eventos = [];
            $stmt = $pdo->prepare("SELECT evento.* FROM evento JOIN base_evento ON evento.id = base_evento.id_evento WHERE base_evento.id_objeto = :id_objeto;");
            $stmt->bindParam("id_objeto", $id, PDO::PARAM_INT);
            $stmt->execute();
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $evento) {
                $eventos = new Evento (
                    $evento["id"],
                    $evento["nombre"],
                    $evento["descripcion"],
                );
            }
            $objeto = new Base(
                $id,
                $datos["nombre"],
                "base",
                $datos["descripcion"],
                $datos["img_data"],
                $datos["precio"],
                $efectos,
                $datos["basico"],
                $datos["uso"],
                $eventos,
            );
            break;
        }
        case "consumible": {
            $stmt = $pdo->prepare("SELECT * FROM objeto JOIN consumible JOIN imagen_objeto ON objeto.id = consumible.id AND objeto.id = imagen_objeto.id_objeto WHERE objeto.id = :id_objeto;");
            $stmt->bindParam("id_objeto", $id, PDO::PARAM_INT);
            $stmt->execute();
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            $objeto = new Consumible(
                $id,
                $datos["nombre"],
                "consumible",
                $datos["descripcion"],
                $datos["img_data"],
                $datos["precio"],
                $efectos,
                $datos["usos"],
            );
            break;
        }
        case "paquete":
        case "agrupacion": {
            $stmt = $pdo->prepare("SELECT * FROM objeto JOIN paquete JOIN imagen_objeto ON objeto.id = paquete.id AND objeto.id = imagen_objeto.id_objeto WHERE objeto.id = :id_objeto;");
            $stmt->bindParam("id_objeto", $id, PDO::PARAM_INT);
            $stmt->execute();
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            //Recursión para obtener los objetos internos del paquete
            $ob1 = obtenerObjeto($pdo, $datos["obj1"], false);
            $ob2 = obtenerObjeto($pdo, $datos["obj2"], false);
            $objeto = new Paquete(
                $id,
                $datos["nombre"],
                $datos["tipo"],
                $datos["descripcion"],
                $datos["img_data"],
                $datos["precio"],
                $efectos,
                $ob1,
                $ob2,
                $datos["ambos"],
            );
            break;
        }
        default: {
            $objeto = new Base(
                1,
                "Nada",
                "base",
                "",
                null,
                0,
                [],
                1,
                "Carente de uso",
                [],
            );
            break;
        }
    }

    return $objeto;
}

function obtenerHabilidad($pdo, $id_habilidad){
    $stmt = $pdo->prepare("SELECT * FROM habilidad WHERE id = :id_habilidad;");
    $stmt->bindParam("id_habilidad", $id_habilidad);
    $stmt->execute();
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->prepare("SELECT efecto.* FROM efecto JOIN efecto_habilidad JOIN habilidad ON efecto_habilidad.id_efecto = efecto.id AND efecto_habilidad.id_habilidad = habilidad.id  WHERE habilidad.id = :id_habilidad;");
    $stmt->bindParam("id_habilidad", $id_habilidad);
    $datos["efectos"];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $efecto) {
        $datos["efectos"] = new Efecto(
            $efecto["id"],
            $efecto["nombre"],
            $efecto["descripcion"],
            $efecto["cantidad"],
            $efecto["duracion"],
            $efecto["tipo"],
        );
    }
    return hydrateHabilidad($datos);
}

//================================PROPUESTAS===============================
//Gestiona la propuesta de una raza, comprobando los campos que se proponen 
function propuestaRaza(pdo $conexion, array $datos): void
{

}

//Gestiona la propuesta de una clase, comprobando los campos que se proponen 
function propuestaClase(pdo $conexion, array $datos): void
{

}