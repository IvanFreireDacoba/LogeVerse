-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando datos para la tabla dndmanager.admins: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.arma: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.armadura: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.atributo: ~6 rows (aproximadamente)
INSERT INTO `atributo` (`id`, `nombre`, `descripcion`) VALUES
	(1, 'Fuerza', 'La capacidad física de un personaje para levantar, cargar y atacar con armas cuerpo a cuerpo.'),
	(2, 'Destreza', 'La habilidad para moverse con rapidez, agilidad y reflejos, así como la capacidad para usar armas a distancia.'),
	(3, 'Constitución', 'La salud física y resistencia de un personaje, que afecta a la cantidad de puntos de vida.'),
	(4, 'Inteligencia', 'La capacidad de razonamiento lógico, conocimiento y aprendizaje.'),
	(5, 'Sabiduría', 'La percepción y juicio, que incluye la intuición y el sentido común.'),
	(6, 'Carisma', 'La capacidad para influir en otros, la presencia personal y la persuasión.');

-- Volcando datos para la tabla dndmanager.atributo_personaje: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.atributo_raza: ~41 rows (aproximadamente)
INSERT INTO `atributo_raza` (`id_atributo`, `id_raza`, `cantidad`) VALUES
	(1, 1, 4),
	(1, 2, 3),
	(1, 3, 3),
	(1, 4, 5),
	(1, 5, 4),
	(1, 6, 4),
	(1, 7, 3),
	(2, 1, 3),
	(2, 2, 4),
	(2, 3, 3),
	(2, 4, 2),
	(2, 5, 4),
	(2, 6, 2),
	(2, 7, 4),
	(3, 1, 2),
	(3, 2, 3),
	(3, 3, 3),
	(3, 4, 5),
	(3, 5, 1),
	(3, 6, 4),
	(3, 7, 4),
	(4, 1, 3),
	(4, 2, 4),
	(4, 3, 3),
	(4, 4, 2),
	(4, 5, 4),
	(4, 6, 2),
	(4, 7, 4),
	(5, 1, 4),
	(5, 2, 5),
	(5, 3, 3),
	(5, 4, 2),
	(5, 5, 4),
	(5, 6, 3),
	(5, 7, 5),
	(6, 1, 2),
	(6, 2, 3),
	(6, 3, 3),
	(6, 4, 2),
	(6, 5, 4),
	(6, 6, 2),
	(6, 7, 1);

-- Volcando datos para la tabla dndmanager.base: ~1 rows (aproximadamente)
INSERT INTO `base` (`id`, `basico`, `uso`) VALUES
	(1, 1, '"Carente de uso"');

-- Volcando datos para la tabla dndmanager.base_evento: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.clase: ~1 rows (aproximadamente)
INSERT INTO `clase` (`id`, `nombre`, `descripcion`, `dado_golpe`, `equipo_inicial`, `hp_atr`, `hp_mod`, `def_atr`, `def_mod`, `golpe_atr`, `golpe_mod`) VALUES
	(1, 'Paquete', 'Carente de habilidades. Son seres cuyo desempeño brilla por su ausencia en cualquier ámbito.', 6, 1, 1, 1, 4, 1, 3, 1);

-- Volcando datos para la tabla dndmanager.clase_habilidad: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.confirmation: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.constantes: ~1 rows (aproximadamente)
INSERT INTO `constantes` (`id`, `nombre`, `valor`) VALUES
	(1, 'max_atr_points', '3');

-- Volcando datos para la tabla dndmanager.consumible: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.efecto: ~2 rows (aproximadamente)
INSERT INTO `efecto` (`id`, `nombre`, `descripcion`, `cantidad`, `duracion`, `tipo`) VALUES
	(1, 'Armadura', 'Protege de los golpes físicos', 1, 3, 'buff'),
	(2, 'Daño', 'Causa daño al objetivo', 1, 0, 'damage');

-- Volcando datos para la tabla dndmanager.efecto_habilidad: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.efecto_objeto: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.efecto_pasiva: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.evento: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.habilidad: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.habilidad_personaje: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.idioma: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.idioma_raza: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.imagen_clase: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.imagen_objeto: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.imagen_perfil: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.imagen_personaje: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.imagen_raza: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.incursion: ~0 rows (aproximadamente)
INSERT INTO `incursion` (`id`, `nombre`, `historia`) VALUES
	(1, 'Ninguna', 'No está realizando ninguna incursión');

-- Volcando datos para la tabla dndmanager.incursion_personaje: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.inventario: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.jugador: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.objeto: ~0 rows (aproximadamente)
INSERT INTO `objeto` (`id`, `nombre`, `tipo`, `descripcion`, `precio`) VALUES
	(1, 'Nada', 'base', 'La ausencia de algo, la nada.', 0.0000);

-- Volcando datos para la tabla dndmanager.paquete: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.pasiva: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.pasiva_raza: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.personaje: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.propuestas: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_arma: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_armadura: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_atributo: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_atributo_raza: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_base: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_base_evento: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_clase: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_clase_habilidad: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_consumible: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_efecto: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_efecto_habilidad: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_efecto_objeto: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_efecto_pasiva: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_evento: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_habilidad: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_idioma: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_idioma_raza: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_imagen_clase: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_imagen_objeto: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_imagen_raza: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_objeto: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_paquete: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_pasiva: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_pasiva_raza: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.prop_raza: ~0 rows (aproximadamente)

-- Volcando datos para la tabla dndmanager.raza: ~7 rows (aproximadamente)
INSERT INTO `raza` (`id`, `nombre`, `caracteristicas`, `historia`, `velocidad`) VALUES
	(1, 'Enano', 'Robustos y resistentes', 'Expertos mineros y herreros', 25),
	(2, 'Elfo', 'Gráciles e inteligentes', 'Vinculados a la naturaleza y la magia', 30),
	(3, 'Humano', 'Versátiles y ambiciosos', 'La raza más común y adaptable', 30),
	(4, 'Dracónido', 'Descendientes de dragones', 'Herederos de linajes dracónicos', 30),
	(5, 'Gnomo', 'Curiosos e ingeniosos', 'Amantes de la invención y la magia', 25),
	(6, 'Semiorco', 'Fuertes y resistentes', 'Descendientes de humanos y orcos', 30),
	(7, 'Tiflin', 'Marcados por linaje infernal', 'Descienden de pactos demoníacos', 30);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
