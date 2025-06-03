<?php

//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("/LogeVerse/inicio");
    exit;
}

include_once "LogeVerse/classes/include_classes.php";
include_once "LogeVerse/modules/hydrators.module.php";
include_once "LogeVerse/modules/toDatabase.module.php";

// ================================ REFRESCAR U OBTENER DATOS ===============================
function refrescarUsuario(PDO $pdo, int $id_usuario): Jugador
{
    //Obtenemos y guardamos el jugador
    $stmt = $pdo->prepare("SELECT jugador.*, imagen_perfil.img_data FROM jugador LEFT JOIN imagen_perfil ON jugador.id = imagen_perfil.id_jugador WHERE jugador.id = :id_usuario;");
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    //Obtenemos las propuestas realizadas por el jugador
    $stmt = $pdo->prepare("SELECT * FROM propuestas WHERE propuestas.id_jugador = :id_jugador ORDER BY propuestas.fecha;");
    $stmt->bindParam(':id_jugador', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $data["propuestas"] = [];
    $data["propuestas"] = $stmt->fetchAll(PDO::FETCH_FUNC, function ($id_prop, $tipo, $aceptado, $fecha) {
        return [$id_prop, $tipo, $aceptado, $fecha];
    });
    $stmt->closeCursor();
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
    $stmt->closeCursor();
    //Checkeamos el estado administrador
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE id = :id_jugador;");
    $stmt->bindParam(':id_jugador', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $data["admin"] = $stmt->rowCount() === 1;
    $stmt->closeCursor();
    //Devolvemos el objeto jugador construido con todos sus parámetros
    return hydrateJugador($data);
}

function refrescarPersonaje(PDO $pdo, $id_personaje): Personaje
{
    $stmt = $pdo->prepare("SELECT personaje.*, imagen_personaje.img_data FROM personaje LEFT JOIN imagen_personaje ON personaje.id = imagen_personaje.id_personaje WHERE personaje.id = :id_personaje;");
    $stmt->bindParam(':id_personaje', $id_personaje, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    //Construimos la raza del personaje
    $id_raza = $data["raza"];
    $data["raza"] = refrescarRaza($pdo, $id_raza);
    //Construimos la clase del personaje
    $id_clase = $data["clase"];
    $data["clase"] = refrescarClase($pdo, $id_clase);
    //Construimos el inventario del personaje
    $data["inventario"] = refrescarInventario($pdo, $id_personaje);
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
    $stmt = $pdo->prepare("SELECT raza.*, imagen_raza.img_data AS 'imagen' FROM raza LEFT JOIN imagen_raza ON raza.id = imagen_raza.id_raza WHERE raza.id = :id_raza;");
    $stmt->bindParam("id_raza", $id_raza, PDO::PARAM_INT);
    $stmt->execute();
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
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
        $datos["pasivas"][] = hydratePasiva($datosPasiva);
    }
    //Obtenemos y construimos los atributos (nombre y cantidad) de la raza
    $stmt = $pdo->prepare("SELECT atributo.nombre, atributo_raza.cantidad FROM atributo_raza JOIN atributo ON atributo_raza.id_atributo = atributo.id WHERE atributo_raza.id_raza = :id_raza;");
    $stmt->bindParam("id_raza", $id_raza, PDO::PARAM_INT);
    $stmt->execute();
    $datos["atributos"] = [];
    $datos["cantidades"] = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $atributo) {
        $datos["atributos"][] = $atributo["nombre"];
        $datos["cantidades"][] = $atributo["cantidad"];
    }
    $stmt->closeCursor();
    //Obtenemos los idiomas de la raza
    $stmt = $pdo->prepare("SELECT idioma.* FROM idioma JOIN idioma_raza JOIN raza ON idioma.id = idioma_raza.id_idioma AND idioma_raza.id_raza = raza.id WHERE raza.id = :id_raza;");
    $stmt->bindParam("id_raza", $id_raza, PDO::PARAM_INT);
    $stmt->execute();
    $datos["idiomas"] = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $idioma) {
        $datos["idiomas"][] = new Idioma(
            $idioma["id"],
            $idioma["nombre"],
            $idioma["descripcion"]
        );
    }
    $stmt->closeCursor();
    //Devolver la raza hidratada con los datos
    return hydrateRaza($datos);
}

function refrescarClase(PDO $pdo, int $id_clase): Clase
{
    //Recogemos los datos de la clase
    $stmt = $pdo->prepare("SELECT clase.*, imagen_clase.img_data AS 'imagen' FROM clase LEFT JOIN imagen_clase ON clase.id = imagen_clase.id_clase WHERE id = :id_clase");
    $stmt->bindParam("id_clase", $id_clase, PDO::PARAM_INT);
    $stmt->execute();
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    //Obtener el objeto inicial de la clase
    $stmt = $pdo->prepare("SELECT objeto.id, objeto.tipo FROM objeto LEFT JOIN clase ON objeto.id = clase.equipo_inicial WHERE clase.id = :id_clase;");
    $stmt->bindParam("id_clase", $id_clase, PDO::PARAM_INT);
    $stmt->execute();
    $objeto = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    $datos["equipo_inicial"] = obtenerObjeto($pdo, $objeto["id"], $objeto["tipo"]);
    //Devolver la clase hidratada con los datos
    return hydrateClase($datos);
}

function refrescarInventario(PDO $pdo, int $id_jugador): Inventario
{
    //Obtener los objetos del inventario y sus cantidades
    $stmt = $pdo->prepare("SELECT * FROM inventario WHERE inventario.id_personaje = :id_jugador;");
    $stmt->bindParam("id_jugador", $id_jugador, PDO::PARAM_INT);
    $stmt->execute();
    $objetos = [];
    $cantidades = [];
    //Generar la estructura de datos del inventario
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $dato) {
        $objetos[] = obtenerObjeto($pdo, $dato["id_objeto"], false);
        $cantidades[] = $dato["cantidad"];
    }
    $stmt->closeCursor();
    //Devolver el inventario
    return new Inventario(
        $id_jugador,
        $objetos,
        $cantidades,
    );
}

function refrescarHabilidades(PDO $pdo, int $id_personaje, int $id_clase): array
{
    $habilidades = [];
    //Obtener las habiliades de la clase
    $stmt = $pdo->prepare("SELECT habilidad.id FROM habilidad JOIN clase_habilidad JOIN clase ON clase.id = clase_habilidad.id_clase AND clase_habilidad.id_habilidad = habilidad.id WHERE clase.id = :id_clase;");
    $stmt->bindParam("id_clase", $id_clase, PDO::PARAM_INT);
    $stmt->execute();
    //Generar las instancias de las habilidades de clase y almacenarlas
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $idHabilidad) {
        $habilidades[] = obtenerHabilidad($pdo, $idHabilidad);
    }
    $stmt->closeCursor();
    //Obtener las habilidades del personaje
    $stmt = $pdo->prepare("SELECT habilidad.* FROM habilidad JOIN habilidad_personaje ON habilidad.id = habilidad_personaje.id_habilidad WHERE habilidad_personaje.id_personaje = :id_personaje;");
    $stmt->bindParam("id_personaje", $id_personaje, PDO::PARAM_INT);
    //Generar las instancias de las habilidades de personaje y almacenarlas SOLO si no están duplicadas
    //Primero me guardo los ids en un array temporal
    $tmp_ids = [];
    foreach ($habilidades as $habilidad) {
        $tmp_ids[] = $habilidad->getId();
    }
    //Luego almacenamos solo las habilidades que no se encuentran en las de tipo clase (son del personaje)
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $idHabilidad) {
        $habilidad = obtenerHabilidad($pdo, $idHabilidad);
        if (!in_array($habilidad->getId(), $tmp_ids)) {
            $habilidades[] = $habilidad;
        }
    }
    return $habilidades;
}

function refrescarAtributos(PDO $pdo, int $id_personaje): array
{
    //Obtenemos los atributos de un personaje
    $atributos = [];
    $stmt = $pdo->prepare("SELECT atributo.nombre, atributo_personaje.cantidad FROM atributo JOIN atributo_personaje ON atributo.id = atributo_personaje.id_atributo WHERE atributo_personaje.id_personaje = :id_personaje;");
    $stmt->bindParam("id_personaje", $id_personaje);
    $stmt->execute();
    //Los almacenamos en el array de atributos asociando una cantidad (valor) a cada nombre (clave)
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $atr) {
        $atributos[$atr["nombre"]] = $atr["cantidad"];
    }
    $stmt->closeCursor();
    //Devolvemos el array de atributos
    return $atributos;
}

function refrescarIncursiones($pdo, $id_personaje): array
{
    $incursiones = [];
    //Obtenemos los datos de la incursión y el desempeño del personaje en la misma
    $stmt = $pdo->prepare("SELECT * FROM incursion JOIN incursion_personaje ON incursion.id = incursion_personaje.id_incursion WHERE incursion_personaje.id_personaje = :id_personaje;");
    $stmt->bindParam("id_personaje", $id_personaje, PDO::PARAM_INT);
    $stmt->execute();
    //Para cada incursión generamos la estructura de datos y la añadimos al array
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $incursion) {
        $incursiones[] = [
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
    $stmt->closeCursor();
    //Devolvemos el array de incursiones
    return $incursiones;
}

function obtenerEfectosPasiva(PDO $pdo, int $id_pasiva): array
{
    $efectos = [];
    //Buscamos todos los datos de los efectos que tiene la pasiva
    $stmt = $pdo->prepare("SELECT efecto.*, efecto_pasiva.modificador FROM efecto JOIN efecto_pasiva JOIN pasiva ON efecto.id = efecto_pasiva.id_efecto AND pasiva.id = efecto_pasiva.id_pasiva WHERE pasiva.id = :id_pasiva;");
    $stmt->bindParam("id:pasiva", $id_pasiva, PDO::PARAM_INT);
    $stmt->execute();
    //Instanciamos y añadimos cada efecto al array de efectos
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $efecto) {
        $efectos[] = [
            new Efecto(
                $efecto["id"],
                $efecto["nombre"],
                $efecto["descripcion"],
                $efecto["cantidad"],
                $efecto["duracion"],
                $efecto["tipo"],
            ),
            $efecto["modificador"]
        ];
    }
    $stmt->closeCursor();
    //Devolvemos los efectos
    return $efectos;
}

function obtenerObjeto(PDO $pdo, int $id, $tipo): Objeto
{
    //Buscar el tipo del objeto si $tipo entra como false 
    if ($tipo === false) {
        $stmt = $pdo->prepare("SELECT tipo FROM objeto WHERE id = :id_objeto;");
        $stmt->bindParam("id_objeto", $id, PDO::PARAM_INT);
        $stmt->execute();
        $tipo = $stmt->fetchColumn();
        $stmt->closeCursor();
    }
    //Seleccionamos todos los efectos del objeto
    $stmt = $pdo->prepare("SELECT efecto.*, efecto_objeto.modificador FROM efecto JOIN efecto_objeto ON efecto.id = efecto_objeto.id_objeto WHERE efecto_objeto.id_objeto = :id_objeto");
    $stmt->bindParam("id_objeto", $id, PDO::PARAM_INT);
    $stmt->execute();
    //Instanciamos cada efecto y lo guardamos en un array de efectos
    $efectos = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $efecto) {
        $efectos[] = [
            new Efecto(
                $efecto["id"],
                $efecto["nombre"],
                $efecto["descripcion"],
                $efecto["cantidad"],
                $efecto["duracion"],
                $efecto["tipo"],
            ),
            $efecto["modificador"]
        ];
    }
    $stmt->closeCursor();
    //En función de su tipo instanciamos y tratamos el objeto
    switch ($tipo) {
        case "arma": {
            //Datos e instancia de objeto tipo arma
            $stmt = $pdo->prepare("SELECT * FROM objeto JOIN arma LEFT JOIN imagen_objeto ON objeto.id = arma.id AND objeto.id = imagen_objeto.id_objeto WHERE objeto.id = :id_objeto;");
            $stmt->bindParam("id_objeto", $id, PDO::PARAM_INT);
            $stmt->execute();
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            //Instancia
            $objeto = new Arma(
                $id,
                $datos["nombre"],
                "arma",
                $datos["descripcion"],
                $datos["precio"],
                $efectos,
                $datos["modificador"],
                $datos["material"],
                $datos["combate"],
                $datos["doble"],
                $datos["img_data"],
            );
            break;
        }
        case "armadura": {
            //Datos e instancia de objeto tipo armadura
            $stmt = $pdo->prepare("SELECT * FROM objeto JOIN armadura LEFT JOIN imagen_objeto ON objeto.id = armadura.id AND objeto.id = imagen_objeto.id_objeto WHERE objeto.id = :id_objeto;");
            $stmt->bindParam("id_objeto", $id, PDO::PARAM_INT);
            $stmt->execute();
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            //Instancia
            $objeto = new Armadura(
                $id,
                $datos["nombre"],
                "armadura",
                $datos["descripcion"],
                $datos["precio"],
                $efectos,
                $datos["corporal"],
                $datos["modificador"],
                $datos["material"],
                $datos["img_data"],
            );
            break;
        }
        case "base": {
            //Datos e instancia de objeto tipo base
            $stmt = $pdo->prepare("SELECT * FROM objeto JOIN base LEFT JOIN imagen_objeto ON objeto.id = base.id AND objeto.id = imagen_objeto.id_objeto WHERE objeto.id = :id_objeto;");
            $stmt->bindParam("id_objeto", $id, PDO::PARAM_INT);
            $stmt->execute();
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            //Recogemos los eventos del objeto base
            $eventos = [];
            $stmt = $pdo->prepare("SELECT evento.* FROM evento JOIN base_evento ON evento.id = base_evento.id_evento WHERE base_evento.id_objeto = :id_objeto;");
            $stmt->bindParam("id_objeto", $id, PDO::PARAM_INT);
            $stmt->execute();
            //Instanciamos cada evento y lo guardamos en un array de eventos
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $evento) {
                $eventos[] = new Evento(
                    $evento["id"],
                    $evento["nombre"],
                    $evento["descripcion"],
                );
            }
            $stmt->closeCursor();
            //Instancia
            $objeto = new Base(
                $id,
                $datos["nombre"],
                "base",
                $datos["descripcion"],
                $datos["precio"],
                $efectos,
                $datos["basico"],
                $datos["uso"],
                $eventos,
                $datos["img_data"],
            );
            break;
        }
        case "consumible": {
            //Datos en instancia de objeto tipo consumible 
            $stmt = $pdo->prepare("SELECT * FROM objeto JOIN consumible LEFT JOIN imagen_objeto ON objeto.id = consumible.id AND objeto.id = imagen_objeto.id_objeto WHERE objeto.id = :id_objeto;");
            $stmt->bindParam("id_objeto", $id, PDO::PARAM_INT);
            $stmt->execute();
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            //Instancia
            $objeto = new Consumible(
                $id,
                $datos["nombre"],
                "consumible",
                $datos["descripcion"],
                $datos["precio"],
                $efectos,
                $datos["usos"],
                $datos["img_data"],
            );
            break;
        }
        case "paquete":
        case "agrupacion": {
            //Datos en instancia de objeto tipo paquete o agrupación
            $stmt = $pdo->prepare("SELECT * FROM objeto JOIN paquete LEFT JOIN imagen_objeto ON objeto.id = paquete.id AND objeto.id = imagen_objeto.id_objeto WHERE objeto.id = :id_objeto;");
            $stmt->bindParam("id_objeto", $id, PDO::PARAM_INT);
            $stmt->execute();
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            //Recursión para obtener los objetos internos del paquete
            $ob1 = obtenerObjeto($pdo, $datos["obj1"], false);
            $ob2 = obtenerObjeto($pdo, $datos["obj2"], false);
            //Instancia
            $objeto = new Paquete(
                $id,
                $datos["nombre"],
                $datos["tipo"],
                $datos["descripcion"],
                $datos["precio"],
                $efectos,
                $ob1,
                $ob2,
                $datos["ambos"],
                $datos["img_data"],
            );
            break;
        }
        default: {
            //Por defecto creamos el objeto "vacío" de la base de datos, sin tomarlo.
            //Nunca se debería entrar por aquí ya que el tipo de objeto debe estar recogido en la ENUM
            $objeto = new Base(
                1,
                "Nada",
                "base",
                "",
                0,
                [],
                1,
                "Carente de uso",
                [],
                null,
            );
            break;
        }
    }
    //Devolver el objeto
    return $objeto;
}

function obtenerHabilidad($pdo, $id_habilidad)
{
    //Seleccionamos una habilidad y tomamos sus datos
    $stmt = $pdo->prepare("SELECT * FROM habilidad WHERE id = :id_habilidad;");
    $stmt->bindParam("id_habilidad", $id_habilidad);
    $stmt->execute();
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    //Seleccionamos los efectos de dicha habilidad
    $stmt->prepare("SELECT efecto.*, efecto_habilidad.modificador FROM efecto JOIN  JOIN habilidad ON efecto_habilidad.id_efecto = efecto.id AND efecto_habilidad.id_habilidad = habilidad.id  WHERE habilidad.id = :id_habilidad;");
    $stmt->bindParam("id_habilidad", $id_habilidad);
    //Instanciamos cada efecto y lo añadimos al array de $datos["efectos"] 
    $datos["efectos"] = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $efecto) {
        $datos["efectos"][] = [
            new Efecto(
                $efecto["id"],
                $efecto["nombre"],
                $efecto["descripcion"],
                $efecto["cantidad"],
                $efecto["duracion"],
                $efecto["tipo"],
            ),
            $efecto["modificador"]
        ];
    }
    //Devolvemos la instancia de la habilidad hidratada
    return hydrateHabilidad($datos);
}

function checkAdmin($id): bool
{
    try {
        $conexion = conectar();
        $stmt = $conexion->prepare("SELECT true FROM admins WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $admin = $stmt->fetchColumn() ? true : false;
        $stmt->closeCursor();
    } catch (Error $e) {
        $_SESSION["alert"]("Error, no se ha podido verificar el status de administrador.");
        $admin = false;
    }
    return $admin;
}

// ================================== CUERPOS HTML =================================
function listarRazasSeleccion(): string
{
    /*
        <input id="estadoRazas" name="raza" data-status="1" data-max="' . count($razas) . '" value="1" hidden>;

        <div class='razaContainer' id='raza_" . $raza["id"] . "' hidden>
            <p class='nombreRaza'>{$raza->getNombre()}</p>
            <img class='imgRaza' src='{$raza->getImagen()}'>
        </div>
    */

    try {
        $pdo = conectar();
        $stmt = $pdo->prepare("SELECT id FROM raza ORDER BY id;");
        $stmt->execute();
        $ids_razas = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $stmt->closeCursor();
        $razas = [];
        //Obtener todas las razas disponibles en la base de datos 
        foreach ($ids_razas as $id_raza) {
            $razas[] = refrescarRaza($pdo, $id_raza);
        }
        //Preparar el input de raza
        $input = '<input id="estadoRazas" name="raza" data-status="1" data-max="' . count($razas) . '" value="1" hidden>';
        //Preparar el HTML con cada raza
        $div_raz = "";
        foreach ($razas as $raza) {
            $div_raz .= "<div class='razaContainer' id='raza_" . $raza->getId() . "' hidden>
                            <p class='nombreRaza'>" . $raza->getNombre() . "</p>
                            <img class='imgRaza' src='" . $raza->getImagen() . "'>
                        </div>";
        }
        //Preparar el script de carga de atributos de cada raza, seleccionamos todos los atributos de cada raza
        $stmt = $pdo->prepare("SELECT atributo.id as id, atributo.nombre as nombre, atributo.descripcion as descripcion, atributo_raza.cantidad as cantidad, raza.id as raza FROM raza JOIN atributo_raza JOIN atributo ON raza.id = atributo_raza.id_raza AND atributo.id = atributo_raza.id_atributo ORDER BY raza.id;");
        $stmt->execute();
        $atrs_razas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        $script_atr = "<script> var atr_razas = { ";
        $id_raza_actual = -1;
        foreach ($atrs_razas as $atrs_raza) {
            if ($id_raza_actual === $atrs_raza["raza"])
                $script_atr .= ", " . $atrs_raza["cantidad"];
            else {
                $script_atr .= $id_raza_actual !== -1 ? "], " : "";
                $id_raza_actual = $atrs_raza["raza"];
                $script_atr .= $id_raza_actual . ": [ null, " . $atrs_raza["cantidad"];

            }
        }
        $script_atr .= "]}; </script>";
        $salida = $input . $script_atr . $div_raz;
    } catch (Exception $error) {

        $salida = "<p>Error al cargar las razas,<br>por favor refresca la página</p>";
    }

    return $salida;
}

function listarClasesSeleccion(): string
{
    /*
        <input id="estadoClases" name="clase" data-max="' . count($clases) - 1 . '" value="" hidden>
        <div class='claseContainer' id='clase_" . $clase["id"] . "' hidden>
            <p class='nombreClase'>{$clase->getNombre()}</p>
            <img class='imgClase' src='{$clase->getImagen()}'>
        </div>"
    */
    try {
        $pdo = conectar();
        $stmt = $pdo->prepare("SELECT id FROM clase ORDER BY id;");
        $stmt->execute();
        $ids_clases = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $stmt->closeCursor();
        $clases = [];
        foreach ($ids_clases as $id_clase) {
            $clases[] = refrescarClase($pdo, $id_clase);
        }
        $salida = '<input id="estadoClases" name="clase" data-max="' . count($clases) . '" value="" hidden>';
        foreach ($clases as $clase) {
            $salida .= "<div class='claseContainer' id='clase_" . $clase->getId() . "' hidden>
                            <p class='nombreClase'>" . $clase->getNombre() . "</p>
                            <img class='imgClase' src='" . $clase->getImagen() . "'>
                        </div>";
        }
    } catch (Error $e) {
        $salida = "<p>Error al cargar las clases,<br>por favor refresca la página</p>";
    }
    return $salida;
}

function listarAtributosSeleccion(): string
{
    try {
        $pdo = conectar();
        $max_atr_points = (int) obtenerConstante(1);
        $inputs = ' <p>Puntos disponibles: <span id="ptos_habilidad_info" max-value="' . $max_atr_points . '">' . $max_atr_points . '</span></p>
                   <input type="number" id="ptos_habilidad" name="ptos_habilidad" value="' . $max_atr_points . '" hidden>';
        $stmt = $pdo->prepare("SELECT id, nombre, descripcion FROM atributo ORDER BY id;");
        $stmt->execute();
        $atributos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        $table = '<table id="tabla_atributos">
                    <thead>
                        <tr>
                            <th>Atributo</th>
                            <td></td>
                            <th>Raza</th>
                            <td></td>
                            <th>Dados</th>
                            <td></td>
                            <th>Puntos</th>
                            <td></td>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($atributos as $atributo) {
            $_SESSION["tiradas_pj"][$atributo["id"]] = lanzarDado(6);
            $inputs .= '<input type="number" name="' . $atributo["id"] . '" class="atr_input_for_js" value="0" hidden>';
            $table .= '<tr class="atr_row">
                        <td>' . $atributo["nombre"] . '</td>
                        <td>:</td>
                        <td class="atr_raza" index="' . $atributo["id"] . '">0</td>
                        <td>+</td>
                        <td class="atr_dice">' . $_SESSION["tiradas_pj"][$atributo["id"]] . '</td>
                        <td>+</td>
                        <td>
                            <input type="number"
                                name="atr_ptos_' . $atributo["nombre"] . '"
                                class="atr_pts"
                                value="0"
                                min="0"
                                required>
                        </td>
                        <td>=</td>
                        <td class="atr_total">0</td>
                    </tr>';
        }
        $table .= "</tbody></table>";
        $salida = $inputs . $table;
    } catch (Error $e) {
        $salida = "<p>Error al cargar los atributos,<br>por favor refresca la página</p>";
    }
    return $salida;
}

// ================================== FUNCIONALES ==================================

function lanzarDado(int $caras, ?int $min = 1): int
{
    return random_int($min, $caras);
}

function obtenerConstante(?int $id = null, ?string $name = null): string
{
    if ($id === null && $name === null) {
        throw new Exception("Error, parámetro de cte. necesario.");
    }
    try {
        $pdo = conectar();

        if ($id !== null) {
            $stmt = $pdo->prepare("SELECT valor FROM constantes WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        } else {
            $stmt = $pdo->prepare("SELECT valor FROM constantes WHERE nombre = :nombre");
            $stmt->bindParam(":nombre", $name, PDO::PARAM_STR);
        }
        $stmt->execute();
        $valor = $stmt->fetchColumn();
        $stmt->closeCursor();
        if ($valor === false) {
            throw new Exception("Error, constante no encontrada.");
        }
        return $valor;
    } catch (PDOException $e) {
        throw new Exception("Error, imposible conectar con la base de datos.");
    }
}

// =================================== PROPUESTAS ==================================
//Gestiona la propuesta de una raza, comprobando los campos que se proponen 
function propuestaRaza(pdo $conexion, array &$datos, string &$infoMsg, bool &$exito): int
{
    $id = 0;
    $infoMsg = "";
    $exito = false;
    $withImage = false;
    //Comprobamos que el array contiene todos los datos necesarios
    if (empty($datos["raza_nombre"])) {
        $infoMsg .= "El nombre de la raza es obligatorio.\n";
    }
    if (empty($datos["raza_descripcion"])) {
        $infoMsg .= "La descripción de la raza es obligatoria.\n";
    }
    if (empty($datos["raza_historia"])) {
        $infoMsg .= "La historia de la raza es obligatoria.\n";
    }
    if (empty($datos["raza_velocidad"])) {
        $infoMsg .= "La velocidad de la raza es obligatoria.\n";
    }
    if (!empty($datos["raza_imagen"])) {
        $withImage = true;
    }
    try {
        $stmt = $conexion->prepare("SELECT count(id) FROM atributo;");
        $stmt->execute();
        $cantidad_atributos = $stmt->fetchColumn();
    } catch (PDOException $e) {
        $infoMsg = "Error al conectar con la base de datos.";
    }
    //Gestionamos los atributos, pasivas e idiomas de la raza
    $pasivas = [];
    $idiomas = [];
    $atributos = [];
    foreach ($datos as $key => $value) {
        if (str_starts_with($key, "raza_pasiva_")) {
            $pasivas[] = $value;
        } elseif (str_starts_with($key, "raza_idioma_")) {
            $idiomas[] = $value;
        } elseif (str_starts_with($key, "raza_atr_")) {
            $id_atr = substr($key, 9);
            $atributos[] = [$id_atr, $value];
        } else {
            continue;
        }
    }
    if (count($atributos) != $cantidad_atributos) {
        $infoMsg .= "La cantidad de atributos no coincide.";
    }
    if (empty($infoMsg)) {
        try {
            $conexion->beginTransaction();
            $stmt = $conexion->prepare("CALL proponerRaza(
                                                                    :nombre,
                                                                    :descripcion,
                                                                    :historia,
                                                                    :velocidad,
                                                                    @p_resultado
                                                                 );");
            $stmt->bindParam(":nombre", $datos["raza_nombre"], PDO::PARAM_STR);
            $stmt->bindParam(":descripcion", $datos["raza_descripcion"], PDO::PARAM_STR);
            $stmt->bindParam(":historia", $datos["raza_historia"], PDO::PARAM_STR);
            $stmt->bindParam(":velocidad", $datos["raza_velocidad"], PDO::PARAM_INT);
            $stmt->execute();
            $id = $conexion->query("SELECT @p_resultado AS resultado")->fetch(PDO::FETCH_ASSOC)["resultado"];
            if ($id > 0) {
                if ($withImage) {
                    try {
                        $stmt->closeCursor();
                        $stmt = $conexion->prepare("INSERT INTO prop_imagen_raza (id_raza, img_data) VALUES (:id, :img_data);");
                        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                        $stmt->bindValue(":img_data", $datos["raza_imagen"], PDO::PARAM_LOB);
                        $stmt->execute();
                        $infoMsg .= "Imagen de la raza guardada con éxito.\n";
                    } catch (PDOException $e) {
                        $infoMsg .= "Error tratando de almacenar la imagen propuesta.";
                    }
                }
                try {
                    foreach ($atributos as $atributo) {
                        $stmt->closeCursor();
                        $stmt = $conexion->prepare("INSERT INTO prop_atributo_raza (id_atributo, id_raza, cantidad) VALUES (:id_atr, :id_raza, :cantidad);");
                        $stmt->bindParam(":id_atr", $atributo[0], PDO::PARAM_INT);
                        $stmt->bindParam(":id_raza", $id, PDO::PARAM_INT);
                        $stmt->bindParam(":cantidad", $atributo[1], PDO::PARAM_INT);
                        $stmt->execute();
                    }
                    $infoMsg = "Atributos de raza establecidos correctamente.\n" . $infoMsg;
                    foreach ($pasivas as $pasiva) {
                        $stmt->closeCursor();
                        $stmt = $conexion->prepare("INSERT INTO prop_pasiva_raza (id_raza, id_pasiva) VALUES (:id_raza, :id_pasiva);");
                        $stmt->bindParam(":id_raza", $id, PDO::PARAM_INT);
                        $stmt->bindParam(":id_pasiva", $pasiva, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                    $infoMsg .= !empty($pasivas) ? "Pasivas agregadas con éxito.\n" : "";
                    foreach ($idiomas as $idioma) {
                        $stmt->closeCursor();
                        $stmt = $conexion->prepare("INSERT INTO prop_idioma_raza (id_raza, id_idioma) VALUES (:id_raza, :id_idioma);");
                        $stmt->bindParam(":id_raza", $id, PDO::PARAM_INT);
                        $stmt->bindParam(":id_idioma", $idioma, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                    $infoMsg .= !empty($idiomas) ? "Idiomas agregados con éxito.\n" : "";
                    $conexion->commit();
                    $infoMsg = "Raza registrada con éxito.\n" . $infoMsg;
                    $exito = true;
                } catch (PDOException $e) {
                    $infoMsg = "Error construyendo la raza.\nRaza no registrada.";
                    $conexion->rollBack();
                }
            } else {
                throw new Error("Error al intentar insertar los datos.\nRaza no registrada.");
            }
        } catch (Error $e) {
            $conexion->inTransaction() ? $conexion->rollBack() : null;
            $infoMsg = $e->getMessage();
        }
    }
    return $id;
}

//Gestiona la propuesta de una clase, comprobando los campos que se proponen 
function propuestaClase(pdo $conexion, array &$datos, string &$infoMsg, bool &$exito): int
{
    $id = 0;
    $infoMsg = "";
    $exito = false;
    $withImage = false;
    //Comprobamos que el array contiene todos los datos necesarios
    if (empty($datos["clase_nombre"])) {
        $infoMsg .= "El nombre de la clase es obligatorio.\n";
    }
    if (empty($datos["clase_descripcion"])) {
        $infoMsg .= "La descripción de la clase es obligatoria.\n";
    }
    if (empty($datos["clase_dado"]) || $datos["clase_dado"] < 1) {
        $infoMsg .= "El valor de dado de golpe para la clase debe ser superior a 0.\n";
    }
    if (empty($datos["clase_equipo"])) {
        $infoMsg .= "El equipo inicial de la clase es obligatorio.\n";
    }
    if (!empty($datos["clase_imagen"])) {
        $withImage = true;
    }
    //En caso de que tenga habilidades, recorremos los datos de POST una vez buscandolas
    $habilidades_clase = [];
    foreach ($_POST as $key => $value) {
        if (str_starts_with($key, "clase_habilidad_")) {
            $habilidades_clase[] = $value;
        }
    }
    if (empty($infoMsg)) {
        try {
            $conexion->beginTransaction();
            $stmt = $conexion->prepare("CALL proponerClase(
                                                                    :nombre,
                                                                    :descripcion,
                                                                    :dado_golpe,
                                                                    :equipo_inicial,
                                                                    :hp_art_ref,
                                                                    :hp_art_mod,
                                                                    :def_art_ref,
                                                                    :def_art_mod,
                                                                    :atq_art_ref,
                                                                    :atq_art_mod,
                                                                    @p_resultado
                                                                 );");
            $stmt->bindParam(":nombre", $datos["clase_nombre"], PDO::PARAM_STR);
            $stmt->bindParam(":descripcion", $datos["clase_descripcion"], PDO::PARAM_STR);
            $stmt->bindParam(":dado_golpe", $datos["clase_dado"], PDO::PARAM_INT);
            $stmt->bindParam(":equipo_inicial", $datos["clase_equipo"], PDO::PARAM_INT);
            $stmt->bindParam(":hp_art_ref", $datos["clase_hp_atr"], PDO::PARAM_INT);
            $stmt->bindParam(":hp_art_mod", $datos["clase_hp_mod"], PDO::PARAM_INT);
            $stmt->bindParam(":def_art_ref", $datos["clase_def_atr"], PDO::PARAM_INT);
            $stmt->bindParam(":def_art_mod", $datos["clase_def_mod"], PDO::PARAM_INT);
            $stmt->bindParam(":atq_art_ref", $datos["clase_atq_atr"], PDO::PARAM_INT);
            $stmt->bindParam(":atq_art_mod", $datos["clase_atq_mod"], PDO::PARAM_INT);
            $stmt->execute();
            $id = $conexion->query("SELECT @p_resultado AS resultado")->fetch(PDO::FETCH_ASSOC)["resultado"];
            if ($id > 0) {
                $infoMsg = "Clase registrada con éxito.\n";
                $exito = true;
                if ($withImage) {
                    try {
                        $stmt->closeCursor();
                        $stmt = $conexion->prepare("INSERT INTO prop_imagen_clase (id_clase, img_data) VALUES (:id, :img_data);");
                        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                        $stmt->bindValue(":img_data", $datos["clase_imagen"], PDO::PARAM_LOB);
                        $stmt->execute();
                        $infoMsg .= "Imagen de la clase guardada con éxito.\n";
                    } catch (PDOException $e) {
                        $infoMsg .= "Error tratando de almacenar la imagen propuesta.\n";
                    }
                }
                if (!empty($habilidades_clase)) {
                    try {
                        foreach ($habilidades_clase as $habilidad) {
                            $stmt->closeCursor();
                            $stmt = $conexion->prepare("INSERT INTO prop_clase_habilidad (id_clase, id_habilidad) VALUES (:id_clase, :id_habilidad);");
                            $stmt->bindParam(":id_clase", $id, PDO::PARAM_INT);
                            $stmt->bindValue(":id_habilidad", $habilidad, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                        $infoMsg .= "Habilidades de la clase guardadas con éxito.";
                    } catch (PDOException $e) {
                        $infoMsg .= "Error tratando de almacenar las habilidades de la clase.";
                    }
                }
                $conexion->commit();
            } else {
                $conexion->rollBack();
                $infoMsg = "Error al intentar insertar los datos.\nClase no registrada.";
            }
        } catch (Exception $e) {
            if ($conexion->inTransaction()) {
                $conexion->rollBack();
            }
            $infoMsg = "Error al intentar insertar los datos.\nClase no registrada.";
        }
    }
    return $id;
}

//Gestiona la propuesta de un efecto, comprobando los campos que se proponen 
function propuestaEfecto(PDO $conexion, array $datos, string &$infoMsg, bool &$exito): int
{
    $id = 0;
    $infoMsg = "";
    $exito = false;
    //Comprobamos que el array contiene todos los datos necesarios
    if (empty($datos["efecto_nombre"])) {
        $infoMsg .= "El nombre del efecto es obligatorio.\n";
    }
    if (empty($datos["efecto_descripcion"])) {
        $infoMsg .= "La descripción del efecto es obligatoria.\n";
    }
    if (empty($datos["efecto_cantidad"])) {
        $infoMsg .= "La cantidad del efecto es obligatoria.\n";
    }
    if (empty($datos["efecto_duracion"])) {
        $infoMsg .= "La duración del efecto es obligatoria.\n";
    }
    if (empty($datos["efecto_tipo"])) {
        $infoMsg .= "El tipo de efecto es obligatorio.";
    }
    if (empty($infoMsg)) {
        try {
            $stmt = $conexion->prepare("INSERT INTO prop_efecto (nombre, descripcion, cantidad, duracion, tipo) VALUES (:nombre, :descripcion, :cantidad, :duracion, :tipo);");
            $stmt->bindParam(":nombre", $datos["efecto_nombre"], PDO::PARAM_STR);
            $stmt->bindParam(":descripcion", $datos["efecto_descripcion"], PDO::PARAM_STR);
            $cantidad = (int) $datos["efecto_cantidad"];
            $stmt->bindParam(":cantidad", $cantidad, PDO::PARAM_INT);
            $duracion = (int) $datos["efecto_duracion"];
            $stmt->bindParam(":duracion", $duracion, PDO::PARAM_INT);
            $stmt->bindParam(":tipo", $datos["efecto_tipo"], PDO::PARAM_STR);
            $stmt->execute();
            $id = $conexion->lastInsertId();
            $stmt->closeCursor();
            $exito = true;
        } catch (Exception $e) {
            $infoMsg = "Error al intentar insertar los datos.\nEfecto no registrado.";
        }
    }
    return $id;
}

//Gestiona la propuesta de una clase, comprobando los campos que se proponen 
function propuestaHabilidad(pdo $conexion, array $datos, string &$infoMsg, bool &$exito): int
{
    $id = 0;
    $infoMsg = "";
    $efects = [];
    $exito = false;
    //Comprobamos que el array contiene todos los datos necesarios
    if (empty($datos["habilidad_nombre"])) {
        $infoMsg .= "El nombre de la habilidad es obligatorio.\n";
    }
    if (empty($datos["habilidad_descripcion"])) {
        $infoMsg .= "La descripción de la habilidad es obligatoria.\n";
    }
    if (empty($datos["habilidad_tipo"])) {
        $infoMsg .= "El tipo de la habilidad es obligatorio.\n";
    }
    if (empty($datos["habilidad_nivel"])) {
        $infoMsg .= "El nivel de la habilidad es obligatorio.\n";
    }
    if (isset($datos["has_effects"])) {
        //En caso de que tenga efectos, recorremos los datos de POST una vez buscando
        //cada efecto y asociándolo a su modificador
        foreach ($_POST as $key => $value) {
            if (str_starts_with($key, "habilidad_efecto_")) {
                $id = str_replace("habilidad_efecto_", "", $key);
                $mod_key = "mod_habilidad_efecto_" . $id;
                if (isset($_POST[$mod_key])) {
                    $modifier = $_POST[$mod_key];
                    $efects[] = [$value, $modifier];
                }
            }
        }
    }
    if (empty($infoMsg)) {
        try {
            $conexion->beginTransaction();
            $stmt = $conexion->prepare("INSERT INTO prop_habilidad (nombre, descripcion, tipo, nivel) VALUES (:nombre, :descripcion, :tipo, :nivel);");
            $stmt->bindParam(":nombre", $_POST["habilidad_nombre"], PDO::PARAM_STR);
            $stmt->bindParam(":descripcion", $_POST["habilidad_descripcion"], PDO::PARAM_STR);
            $stmt->bindParam(":tipo", $_POST["habilidad_tipo"], PDO::PARAM_STR);
            $stmt->bindParam(":nivel", $_POST["habilidad_nivel"], PDO::PARAM_INT);
            $stmt->execute();
            $id = $conexion->lastInsertId();
            $stmt->closeCursor();
            $infoMsg = "Habilidad registrada con éxito.";
            if (isset($datos["has_effects"])) {
                foreach ($efects as $efect) {
                    $stmt = $conexion->prepare("INSERT INTO prop_efecto_habilidad VALUES (:id_efecto, :id_habilidad, :modificador);");
                    $stmt->bindParam(":id_efecto", $efect[0], PDO::PARAM_INT);
                    $stmt->bindParam(":id_habilidad", $id, PDO::PARAM_INT);
                    $stmt->bindParam(":modificador", $efect[1], PDO::PARAM_INT);
                    $stmt->execute();
                }
                $infoMsg .= "\nEfectos asignados a la habilidad con éxito.";
            }
            $conexion->commit();
            $stmt->closeCursor();
            $exito = true;
        } catch (Exception $e) {
            $conexion->inTransaction() ? $conexion->rollBack() : null;
            $infoMsg = "Error al intentar insertar los datos.\nHabilidad no registrada.";
        }
    }
    return $id;
}

//Gestiona la propuesta de una clase, comprobando los campos que se proponen 
function propuestaPasiva(pdo $conexion, array $datos, string &$infoMsg, bool &$exito): int
{
    $id = 0;
    $infoMsg = "";
    $efects = [];
    $exito = false;
    //Comprobamos que el array contiene todos los datos necesarios
    if (empty($datos["pasiva_nombre"])) {
        $infoMsg .= "El nombre de la pasiva es obligatorio.\n";
    }
    if (empty($datos["pasiva_descripcion"])) {
        $infoMsg .= "La descripción de la pasiva es obligatoria.\n";
    }
    if (isset($datos["has_effects"])) {
        //En caso de que tenga efectos, recorremos los datos de POST una vez buscando
        //cada efecto y asociándolo a su modificador
        foreach ($_POST as $key => $value) {
            if (str_starts_with($key, "pasiva_efecto_")) {
                $id = str_replace("pasiva_efecto_", "", $key);
                $mod_key = "mod_pasiva_efecto_" . $id;
                if (isset($_POST[$mod_key])) {
                    $modifier = $_POST[$mod_key];
                    $efects[] = [$value, $modifier];
                }
            }
        }
    }
    if (empty($infoMsg)) {
        try {
            $conexion->beginTransaction();
            $stmt = $conexion->prepare("INSERT INTO prop_pasiva (nombre, descripcion) VALUES (:nombre, :descripcion);");
            $stmt->bindParam(":nombre", $_POST["pasiva_nombre"], PDO::PARAM_STR);
            $stmt->bindParam(":descripcion", $_POST["pasiva_descripcion"], PDO::PARAM_STR);
            $stmt->execute();
            $id = $conexion->lastInsertId();
            $infoMsg = "Pasiva registrada con éxito.";
            if (isset($datos["has_effects"])) {
                foreach ($efects as $efect) {
                    $stmt->closeCursor();
                    $stmt = $conexion->prepare("INSERT INTO prop_efecto_pasiva VALUES (:id_efecto, :id_pasiva, :modificador);");
                    $stmt->bindParam(":id_efecto", $efect[0], PDO::PARAM_INT);
                    $stmt->bindParam(":id_pasiva", $id, PDO::PARAM_INT);
                    $stmt->bindParam(":modificador", $efect[1], PDO::PARAM_INT);
                    $stmt->execute();
                }
                $infoMsg .= "\nEfectos asignados a la pasiva con éxito.";
            }
            $conexion->commit();
            $stmt->closeCursor();
            $exito = true;
        } catch (Exception $e) {
            $conexion->inTransaction() ? $conexion->rollBack() : null;
            $infoMsg = "Error al intentar insertar los datos.\nPasiva no registrada.";
        }
    }
    return $id;
}

//Gestiona la propuesta de un objeto, comprobando los campos que se proponen 
function propuestaObjeto(pdo $conexion, array $datos, string &$infoMsg, bool &$exito): int
{
    return 0;
}

//Gestiona la propuesta de un idioma, comprobando los campos que se proponen 
function propuestaIdioma(pdo $conexion, array $datos, string &$infoMsg, bool &$exito): int
{
    $id = 0;
    $infoMsg = "";
    $exito = false;
    //Comprobamos que el array contiene todos los datos necesarios
    if (empty($datos["idioma_nombre"])) {
        $infoMsg .= "El nombre del idioma es obligatorio.\n";
    }
    if (empty($datos["idioma_descripcion"])) {
        $infoMsg .= "La descripción del idioma es obligatoria.\n";
    }
    if (empty($infoMsg)) {
        try {
            //Llamar al procedimiento para proponer un idioma
            $stmt = $conexion->prepare("CALL proponerIdioma(:nombre, :descripcion, @resultado)");
            $stmt->bindParam(":nombre", $datos["idioma_nombre"], PDO::PARAM_STR);
            $stmt->bindParam(":descripcion", $datos["idioma_descripcion"], PDO::PARAM_STR);
            $stmt->execute();
            $stmt = $conexion->query("SELECT @resultado AS resultado");
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            $id = (int) $fila['resultado'];
            $stmt->closeCursor();
            $exito = ($id > 0);
            $infoMsg .= $exito ? "Idioma registrado con éxito." : "Error al intentar insertar los datos.\nIdioma no registrado.";
        } catch (Exception $e) {
            $infoMsg = "Error al intentar insertar los datos.\nIdioma no registrado.";
        }
    }
    return $id;
}

//Registra una propuesta realizada por un jugador
function registrarPropuesta(pdo $pdo, int $id_jugador, int $id_propuesta, string $proposal_type): void
{
    try {
        $stmt = $pdo->prepare("INSERT INTO propuestas (id_jugador, id_prop, tipo) VALUES (:id_jug, :id_prop, :tipo);");
        $stmt->bindParam(":id_jug", $id_jugador, PDO::PARAM_INT);
        $stmt->bindParam(":id_prop", $id_propuesta, PDO::PARAM_INT);
        $stmt->bindParam(":tipo", $proposal_type, PDO::PARAM_STR);
        $stmt->execute();
    } catch (Error $e) {
        throw new Exception("Error registrando la respuesta");
    }
}