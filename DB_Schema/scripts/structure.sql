CREATE DATABASE IF NOT EXISTS `dndmanager`;
USE `dndmanager`;

CREATE TABLE IF NOT EXISTS `admins` (
  `id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `admin_jugador` FOREIGN KEY (`id`) REFERENCES `jugador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `arma` (
  `id` int(10) unsigned NOT NULL,
  `material` varchar(50) NOT NULL,
  `modificador` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `doble` enum('true','false') NOT NULL DEFAULT 'false',
  `combate` enum('fisico','magico') NOT NULL,
  `rango` tinyint(3) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  CONSTRAINT `objeto_arma` FOREIGN KEY (`id`) REFERENCES `objeto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene información de las armas disponibles';

CREATE TABLE IF NOT EXISTS `armadura` (
  `id` int(10) unsigned NOT NULL,
  `material` varchar(50) NOT NULL,
  `modificador` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `corporal` enum('Cabeza','Cuerpo','Piernas','Pies','Manos','Dedos','Cola','Cuernos','Cara','Otro') NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `objeto_armadura` FOREIGN KEY (`id`) REFERENCES `objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene información de las armaduras disponibles';

CREATE TABLE IF NOT EXISTS `atributo` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `nombre` varchar(15) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `atributo_personaje` (
  `id_atributo` tinyint(3) unsigned NOT NULL,
  `id_personaje` int(10) unsigned NOT NULL,
  `cantidad` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_atributo`,`id_personaje`),
  KEY `atributos_pj_personaje` (`id_personaje`),
  CONSTRAINT `atributos_pj_atributo` FOREIGN KEY (`id_atributo`) REFERENCES `atributo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `atributos_pj_personaje` FOREIGN KEY (`id_personaje`) REFERENCES `personaje` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene el valor actual de los atributos del personaje';

CREATE TABLE IF NOT EXISTS `atributo_raza` (
  `id_atributo` tinyint(3) unsigned NOT NULL,
  `id_raza` tinyint(3) unsigned NOT NULL,
  `cantidad` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_atributo`,`id_raza`),
  KEY `atributos_rz_raza` (`id_raza`),
  CONSTRAINT `atributos_rz_atributo` FOREIGN KEY (`id_atributo`) REFERENCES `atributo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `atributos_rz_raza` FOREIGN KEY (`id_raza`) REFERENCES `raza` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene los atributos predeterminados de una raza';

CREATE TABLE IF NOT EXISTS `base` (
  `id` int(10) unsigned NOT NULL,
  `basico` enum('true','false') NOT NULL DEFAULT 'true',
  `uso` text NOT NULL DEFAULT 'Desconocido',
  PRIMARY KEY (`id`),
  CONSTRAINT `objeto_base` FOREIGN KEY (`id`) REFERENCES `objeto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Objetos de tipo ''base'' cuyos usos pueden ser variados entre materiales o básicos y objetos especiales';

CREATE TABLE IF NOT EXISTS `base_evento` (
  `id_objeto` int(10) unsigned NOT NULL,
  `id_evento` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_objeto`,`id_evento`),
  KEY `eventos_evento` (`id_evento`),
  CONSTRAINT `eventos_evento` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `eventos_objeto` FOREIGN KEY (`id_objeto`) REFERENCES `base` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene la relación entre los objetos ''base'' y los eventos en los que participan.';

CREATE TABLE IF NOT EXISTS `clase` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `puntos_golpe` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `equipo_inicial` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `equipo_ini_clase` (`equipo_inicial`),
  CONSTRAINT `equipo_ini_clase` FOREIGN KEY (`equipo_inicial`) REFERENCES `objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `clase_habilidad` (
  `id_clase` tinyint(3) unsigned NOT NULL,
  `id_habilidad` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_clase`,`id_habilidad`),
  KEY `habilidades_class_habilidad` (`id_habilidad`),
  CONSTRAINT `habilidades_class_clase` FOREIGN KEY (`id_clase`) REFERENCES `clase` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `habilidades_class_habilidad` FOREIGN KEY (`id_habilidad`) REFERENCES `habilidad` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene la relación entre las clases y sus habilidades predeterminadas.';

CREATE TABLE IF NOT EXISTS `consumible` (
  `id` int(10) unsigned NOT NULL,
  `usos` smallint(5) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  CONSTRAINT `objeto_consumible` FOREIGN KEY (`id`) REFERENCES `objeto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `efecto` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `cantidad` mediumint(8) unsigned DEFAULT NULL,
  `duracion` tinyint(4) DEFAULT NULL,
  `tipo` enum('damage','status','debuf','buff','other','none') NOT NULL DEFAULT 'none',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `efecto_habilidad` (
  `id_efecto` int(10) unsigned NOT NULL,
  `id_habilidad` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_efecto`,`id_habilidad`),
  KEY `efectos_hab_habilidad` (`id_habilidad`),
  CONSTRAINT `efectos_hab_efecto` FOREIGN KEY (`id_efecto`) REFERENCES `efecto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `efectos_hab_habilidad` FOREIGN KEY (`id_habilidad`) REFERENCES `habilidad` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Relaciona las habilidades con uno o varios efectos';

CREATE TABLE IF NOT EXISTS `efecto_objeto` (
  `id_efecto` int(10) unsigned NOT NULL,
  `id_objeto` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_efecto`,`id_objeto`),
  KEY `efectos_obj_objeto` (`id_objeto`),
  CONSTRAINT `efectos_obj_efecto` FOREIGN KEY (`id_efecto`) REFERENCES `efecto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `efectos_obj_objeto` FOREIGN KEY (`id_objeto`) REFERENCES `objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene la relación entre los objetos los efectos que estos pueden provocar';

CREATE TABLE IF NOT EXISTS `efecto_pasiva` (
  `id_efecto` int(10) unsigned NOT NULL,
  `id_pasiva` mediumint(8) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_efecto`,`id_pasiva`),
  KEY `efectos_ps_pasiva` (`id_pasiva`),
  CONSTRAINT `efectos_ps_efecto` FOREIGN KEY (`id_efecto`) REFERENCES `efecto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `efectos_ps_pasiva` FOREIGN KEY (`id_pasiva`) REFERENCES `pasiva` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene la relación entre los efectos que aporta cada pasiva';

CREATE TABLE IF NOT EXISTS `evento` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `habilidad` (
  `id` smallint(5) unsigned NOT NULL DEFAULT 0,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `tipo` enum('fisico','magico','estado','campo','otro') NOT NULL,
  `coste` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `habilidad_personaje` (
  `id_personaje` int(10) unsigned NOT NULL,
  `id_habilidad` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_personaje`,`id_habilidad`),
  KEY `habilidades_pj_habilidad` (`id_habilidad`),
  CONSTRAINT `habilidades_pj_habilidad` FOREIGN KEY (`id_habilidad`) REFERENCES `habilidad` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `habilidades_pj_personaje` FOREIGN KEY (`id_personaje`) REFERENCES `personaje` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene las habilidades desarrolladas por el personaje durante su desarrollo';

CREATE TABLE IF NOT EXISTS `idioma` (
  `id` smallint(5) unsigned NOT NULL DEFAULT 0,
  `nombre` varchar(20) NOT NULL,
  `descripción` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `idioma_raza` (
  `id_raza` tinyint(3) unsigned NOT NULL,
  `id_idioma` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_raza`,`id_idioma`),
  KEY `idiomas_idioma` (`id_idioma`),
  CONSTRAINT `idiomas_idioma` FOREIGN KEY (`id_idioma`) REFERENCES `idioma` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `idiomas_raza` FOREIGN KEY (`id_raza`) REFERENCES `raza` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Relaciones entre los idiomas y las razas (indica qué razas saben hablar qué idiomas de manera predeterminada)';

CREATE TABLE IF NOT EXISTS `incursion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `historia` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `incursion_personaje` (
  `id_incursion` int(10) unsigned NOT NULL,
  `id_personaje` int(10) unsigned NOT NULL,
  `sobrevive` enum('true','false') NOT NULL DEFAULT 'true',
  `derrotados` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_incursion`,`id_personaje`),
  KEY `incursiones_personaje` (`id_personaje`),
  CONSTRAINT `incursiones_incursion` FOREIGN KEY (`id_incursion`) REFERENCES `incursion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `incursiones_personaje` FOREIGN KEY (`id_personaje`) REFERENCES `personaje` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene la relación entre los personajes que participaron en cada incursión.\r\nAdemás contiene información sobre el desempeño del personaje durante la incursión.';

CREATE TABLE IF NOT EXISTS `inventario` (
  `id_personaje` int(10) unsigned NOT NULL,
  `id_objeto` int(10) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_personaje`,`id_objeto`),
  KEY `inventario_objeto` (`id_objeto`),
  CONSTRAINT `inventario_objeto` FOREIGN KEY (`id_objeto`) REFERENCES `objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `inventario_personaje` FOREIGN KEY (`id_personaje`) REFERENCES `personaje` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `jugador` (
  `id` mediumint(9) unsigned NOT NULL DEFAULT 0,
  `nombre` varchar(50) NOT NULL,
  `correo` varchar(75) NOT NULL,
  `hash` varchar(60) NOT NULL,
  `puntos` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='tabla de usuarios';

CREATE TABLE IF NOT EXISTS `objeto` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `tipo` enum('arma','armadura','consumible','paquete','base') NOT NULL,
  `descripcion` longtext NOT NULL,
  `precio` decimal(9,4) unsigned NOT NULL DEFAULT 0.0000,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `paquete` (
  `id` int(10) unsigned NOT NULL,
  `obj1` int(10) unsigned NOT NULL,
  `obj2` int(10) unsigned NOT NULL,
  `ambos` enum('true','false') NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`),
  UNIQUE KEY `obj1_obj2` (`obj1`,`obj2`),
  KEY `objeto_paquete_objeto2` (`obj2`),
  CONSTRAINT `objeto_paquete` FOREIGN KEY (`id`) REFERENCES `objeto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `objeto_paquete_objeto1` FOREIGN KEY (`obj1`) REFERENCES `objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `objeto_paquete_objeto2` FOREIGN KEY (`obj2`) REFERENCES `objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `pasiva` (
  `id` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `pasiva_raza` (
  `id_raza` tinyint(3) unsigned NOT NULL,
  `id_pasiva` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id_raza`,`id_pasiva`),
  KEY `pasivas_pasiva` (`id_pasiva`),
  CONSTRAINT `pasivas_pasiva` FOREIGN KEY (`id_pasiva`) REFERENCES `pasiva` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `pasivas_raza` FOREIGN KEY (`id_raza`) REFERENCES `raza` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene la relación entre las pasivas y las razas.\r\nCada raza tiene ninguna, una o varias pasivas.';

CREATE TABLE IF NOT EXISTS `personaje` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `propietario` mediumint(9) unsigned NOT NULL,
  `raza` tinyint(3) unsigned NOT NULL,
  `clase` tinyint(3) unsigned NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `historia` varchar(500) DEFAULT NULL,
  `experiencia` int(11) NOT NULL DEFAULT 0,
  `dinero` decimal(20,6) NOT NULL DEFAULT 0.000000,
  `puntos_habilidad` smallint(6) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `personaje_clase` (`clase`),
  KEY `personaje_raza` (`raza`),
  KEY `pesonaje_propietario` (`propietario`),
  CONSTRAINT `personaje_clase` FOREIGN KEY (`clase`) REFERENCES `clase` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `personaje_raza` FOREIGN KEY (`raza`) REFERENCES `raza` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `pesonaje_propietario` FOREIGN KEY (`propietario`) REFERENCES `jugador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla con la información de cada personaje.';

CREATE TABLE IF NOT EXISTS `raza` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `nombre` varchar(30) NOT NULL,
  `caracteristicas` text NOT NULL,
  `historia` text NOT NULL,
  `velocidad` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `raza` (`nombre`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DELIMITER //
CREATE FUNCTION `crearArma`(`nombre` VARCHAR(50),
	`tipo` ENUM('arma','armadura','consumible','paquete','base'),
	`descripcion` LONGTEXT,
	`precio` DECIMAL(9,4),
	`material` VARCHAR(50),
	`modificador` TINYINT(3),
	`doble` ENUM('true', 'false'),
	`combate` ENUM('fisico', 'magico'),
	`rango` TINYINT(3)
) RETURNS int(11)
    DETERMINISTIC
    COMMENT 'Función para crear un Arma. Entrada requerida: nombre VARCHAR(50), tipo ENUM(''arma'',''armadura'',''consumible'',''paquete'',''base''), descripcion LONGTEXT, precio DECIMAL(9,4), material VARCHAR(50), modificador TINYINT(3), doble ENUM(''true'', ''false''), combate ENUM(''fisico'', ''magico''), rango TINYINT(3)'
BEGIN
    DECLARE armaID INT;
    SET armaID = crearObjeto(nombre, tipo, descripcion, precio);
    INSERT INTO arma (id, material, modificador, doble, combate, rango) VALUES (armaID, material, modificador, doble, combate, rango);
    RETURN armaID;
END//
DELIMITER ;

DELIMITER //
CREATE FUNCTION `crearArmadura`(`nombre` VARCHAR(50),
	`tipo` ENUM('arma','armadura','consumible','paquete','base'),
	`descripcion` LONGTEXT,
	`precio` DECIMAL(9,4),
	`material` VARCHAR(50),
	`modificador` TINYINT(3),
	`coporal` ENUM('Cabeza','Cuerpo','Piernas','Pies','Manos','Dedos','Cola','Cuernos','Cara','Otro')
) RETURNS int(11)
    DETERMINISTIC
    COMMENT 'Función para crear una armadura. Entrada requerida: nombre VARCHAR(50), tipo ENUM(''arma'',''armadura'',''consumible'',''paquete'',''base''), descripcion LONGTEXT, precio DECIMAL(9,4), material VARCHAR(50), modificador TINYINT(3), coporal ENUM(''Cabeza'',''Cuerpo'',''Piernas'',''Pies'',''Manos'',''Dedos'',''Cola'',''Cuernos'',''Cara'',''Otro'')'
BEGIN
    DECLARE armaduraID INT;
    SET armaduraID = crearObjeto(nombre, tipo, descripcion, precio);
    INSERT INTO armadura (id, material, modificador, corporal) VALUES (armaduraID, material, modificador, corporal);
    RETURN armaduraID;
END//
DELIMITER ;

DELIMITER //
CREATE FUNCTION `crearConsumible`(`nombre` VARCHAR(50),
	`tipo` ENUM('arma','armadura','consumible','paquete','base'),
	`descripcion` LONGTEXT,
	`precio` DECIMAL(9,4),
	`usos` SMALLINT(5)
) RETURNS int(11)
    DETERMINISTIC
    COMMENT 'Función para crear un consumible. Entrada requerida: nombre VARCHAR(50), tipo ENUM(''arma'',''armadura'',''consumible'',''paquete'',''base''), descripcion LONGTEXT, precio DECIMAL(9,4), usos SMALLINT(5)'
BEGIN
    DECLARE consumibleID INT;
    SET consumibleID = crearObjeto(nombre, tipo, descripcion, precio);
    INSERT INTO consumible (id, usos) VALUES (consumibleID, usos);
    RETURN consumibleID;
END//
DELIMITER ;

DELIMITER //
CREATE FUNCTION `crearObjeto`(`nombre` VARCHAR(50),
	`tipo` ENUM('arma','armadura','consumible','paquete','base'),
	`descripcion` LONGTEXT,
	`precio` DECIMAL(9,4)
) RETURNS int(11)
    DETERMINISTIC
    COMMENT 'Función AUXILIAR para crear un objeto. Entrada requerida: nombre VARCHAR(50), tipo ENUM(''arma'',''armadura'',''consumible'',''paquete'',''base''), descripcion LONGTEXT, precio DECIMAL(9,4).'
BEGIN
    DECLARE objetoID INT;
    INSERT INTO objeto (nombre, tipo, descripcion, precio) VALUES (nombre, tipo, descripcion, precio);
    SET objetoID = LAST_INSERT_ID();
    RETURN objetoID;
END//
DELIMITER ;

DELIMITER //
CREATE FUNCTION `crearObjetoBase`(`nombre` VARCHAR(50),
	`tipo` ENUM('arma','armadura','consumible','paquete','base'),
	`descripcion` LONGTEXT,
	`precio` DECIMAL(9,4),
	`basico` ENUM('true', 'false'),
	`uso` TEXT
) RETURNS int(11)
    DETERMINISTIC
    COMMENT 'Función para crear un objeto base. Entrada requerida: nombre VARCHAR(50), tipo ENUM(''arma'',''armadura'',''consumible'',''paquete'',''base''), descripcion LONGTEXT, precio DECIMAL(9,4), basico ENUM(''true'', ''false''), uso TEXT'
BEGIN
    DECLARE baseID INT;
    SET baseID = crearObjeto(nombre, tipo, descripcion, precio);
    INSERT INTO base (id, basico, uso) VALUES (baseID, basico, uso);
    RETURN baseID;
END//
DELIMITER ;

DELIMITER //
CREATE FUNCTION `crearPaquete`(`id_obj1` INT(10),
	`id_obj2` INT(10),
	`ambos` ENUM('true', 'false')
) RETURNS int(11)
    DETERMINISTIC
    COMMENT 'Función para crear un paquete. Entrada requerida: id_obj1 INT(10), id_obj2 INT(10), ambos ENUM(''true'', ''false'')'
BEGIN
	DECLARE existe1 INT DEFAULT 0;
   DECLARE existe2 INT DEFAULT 0;
   DECLARE existePaquete INT DEFAULT 0;
	SELECT COUNT(*) INTO existe1 FROM objeto WHERE objeto.id = id_obj1;
   SELECT COUNT(*) INTO existe2 FROM objeto WHERE objeto.id = id_obj2;
   SELECT COUNT(*) INTO existePaquete FROM paquete WHERE obj1 = id_obj1 AND obj2 = id_obj2;
	IF(existe1 > 0 AND existe2 > 0 AND existePaquete = 0) THEN
		INSERT INTO paquete (obj1, obj2, ambos) VALUES (id_obj1, id_obj2, ambos);
		RETURN LAST_INSERT_ID();
	ELSE
		RETURN -1;
	END IF;
END//
DELIMITER ;