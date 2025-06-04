CREATE DATABASE IF NOT EXISTS `dndmanager`;
USE `dndmanager`;

SET foreign_key_checks = 0;

CREATE TABLE IF NOT EXISTS `admins` (
  `id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `admin_jugador` FOREIGN KEY (`id`) REFERENCES `jugador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `arma` (
  `id` int(10) unsigned NOT NULL,
  `material` varchar(50) NOT NULL,
  `modificador` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `doble` tinyint(1) unsigned NOT NULL DEFAULT 0,
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
  `basico` tinyint(1) unsigned NOT NULL DEFAULT 0,
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
  `dado_golpe` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `equipo_inicial` int(10) unsigned NOT NULL,
  `hp_atr` tinyint(3) unsigned NOT NULL,
  `hp_mod` tinyint(2) unsigned NOT NULL DEFAULT 1,
  `def_atr` tinyint(3) unsigned NOT NULL,
  `def_mod` tinyint(2) unsigned NOT NULL DEFAULT 1,
  `golpe_atr` tinyint(3) unsigned NOT NULL,
  `golpe_mod` tinyint(2) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `equipo_ini_clase` (`equipo_inicial`),
  KEY `hp_atr` (`hp_atr`),
  KEY `def_atr` (`def_atr`),
  KEY `golpe_atr` (`golpe_atr`),
  CONSTRAINT `def_atr` FOREIGN KEY (`def_atr`) REFERENCES `atributo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `equipo_ini_clase` FOREIGN KEY (`equipo_inicial`) REFERENCES `objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `golpe_atr` FOREIGN KEY (`golpe_atr`) REFERENCES `atributo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `hp_atr` FOREIGN KEY (`hp_atr`) REFERENCES `atributo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `clase_habilidad` (
  `id_clase` tinyint(3) unsigned NOT NULL,
  `id_habilidad` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_clase`,`id_habilidad`),
  KEY `habilidades_class_habilidad` (`id_habilidad`),
  CONSTRAINT `habilidades_class_clase` FOREIGN KEY (`id_clase`) REFERENCES `clase` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `habilidades_class_habilidad` FOREIGN KEY (`id_habilidad`) REFERENCES `habilidad` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene la relación entre las clases y sus habilidades predeterminadas.';

CREATE TABLE IF NOT EXISTS `confirmation` (
  `id_usuario` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `conf_code` char(32) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `conf_code` FOREIGN KEY (`id_usuario`) REFERENCES `jugador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `constantes` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `valor` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `consumible` (
  `id` int(10) unsigned NOT NULL,
  `usos` smallint(5) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  CONSTRAINT `objeto_consumible` FOREIGN KEY (`id`) REFERENCES `objeto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `efecto` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `cantidad` mediumint(8) unsigned DEFAULT NULL,
  `duracion` tinyint(3) unsigned DEFAULT NULL,
  `tipo` enum('damage','status','debuf','buff','other','none') NOT NULL DEFAULT 'none',
  PRIMARY KEY (`id`),
  UNIQUE KEY `efecto` (`nombre`,`cantidad`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `efecto_habilidad` (
  `id_efecto` int(10) unsigned NOT NULL,
  `id_habilidad` smallint(5) unsigned NOT NULL,
  `modificador` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_efecto`,`id_habilidad`),
  KEY `efectos_hab_habilidad` (`id_habilidad`),
  CONSTRAINT `efectos_hab_efecto` FOREIGN KEY (`id_efecto`) REFERENCES `efecto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `efectos_hab_habilidad` FOREIGN KEY (`id_habilidad`) REFERENCES `habilidad` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Relaciona las habilidades con uno o varios efectos';

CREATE TABLE IF NOT EXISTS `efecto_objeto` (
  `id_efecto` int(10) unsigned NOT NULL,
  `id_objeto` int(10) unsigned NOT NULL,
  `modificador` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_efecto`,`id_objeto`),
  KEY `efectos_obj_objeto` (`id_objeto`),
  CONSTRAINT `efectos_obj_efecto` FOREIGN KEY (`id_efecto`) REFERENCES `efecto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `efectos_obj_objeto` FOREIGN KEY (`id_objeto`) REFERENCES `objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene la relación entre los objetos los efectos que estos pueden provocar';

CREATE TABLE IF NOT EXISTS `efecto_pasiva` (
  `id_efecto` int(10) unsigned NOT NULL,
  `id_pasiva` mediumint(8) unsigned NOT NULL,
  `modificador` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_efecto`,`id_pasiva`),
  KEY `FK_efecto_pasiva_dndmanager.pasiva` (`id_pasiva`),
  CONSTRAINT `FK_efecto_pasiva_dndmanager.pasiva` FOREIGN KEY (`id_pasiva`) REFERENCES `pasiva` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `efectos_ps_efecto` FOREIGN KEY (`id_efecto`) REFERENCES `efecto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene la relación entre los efectos que aporta cada pasiva';

CREATE TABLE IF NOT EXISTS `evento` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `habilidad` (
  `id` smallint(5) unsigned NOT NULL DEFAULT 0,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `tipo` enum('fisico','magico','estado','campo','aux','otro') NOT NULL,
  `nivel` tinyint(3) unsigned NOT NULL DEFAULT 0,
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
  `descripcion` varchar(500) DEFAULT NULL,
  `id_pasiva` mediumint(7) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`),
  UNIQUE KEY `id_pasiva` (`id_pasiva`),
  CONSTRAINT `idioma_pasiva` FOREIGN KEY (`id_pasiva`) REFERENCES `pasiva` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `idioma_raza` (
  `id_raza` tinyint(3) unsigned NOT NULL,
  `id_idioma` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_raza`,`id_idioma`),
  KEY `idiomas_idioma` (`id_idioma`),
  CONSTRAINT `FK_idioma_raza_dndmanager.raza` FOREIGN KEY (`id_raza`) REFERENCES `raza` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `idiomas_idioma` FOREIGN KEY (`id_idioma`) REFERENCES `idioma` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Relaciones entre los idiomas y las razas (indica qué razas saben hablar qué idiomas de manera predeterminada)';

CREATE TABLE IF NOT EXISTS `imagen_clase` (
  `id_clase` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `img_data` longblob NOT NULL,
  PRIMARY KEY (`id_clase`),
  CONSTRAINT `imagen_clase` FOREIGN KEY (`id_clase`) REFERENCES `clase` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `imagen_objeto` (
  `id_objeto` int(10) unsigned NOT NULL,
  `img_data` longblob NOT NULL,
  PRIMARY KEY (`id_objeto`),
  CONSTRAINT `FK_imagen_objeto_prop_objeto` FOREIGN KEY (`id_objeto`) REFERENCES `prop_objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `imagen_perfil` (
  `id_jugador` mediumint(8) unsigned NOT NULL,
  `img_data` longblob NOT NULL,
  PRIMARY KEY (`id_jugador`) USING BTREE,
  CONSTRAINT `FK_img_perfil` FOREIGN KEY (`id_jugador`) REFERENCES `jugador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `imagen_personaje` (
  `id_personaje` int(10) unsigned NOT NULL,
  `img_data` longblob NOT NULL,
  PRIMARY KEY (`id_personaje`),
  CONSTRAINT `imagen_personaje` FOREIGN KEY (`id_personaje`) REFERENCES `personaje` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `imagen_raza` (
  `id_raza` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `img_data` longblob NOT NULL,
  PRIMARY KEY (`id_raza`),
  CONSTRAINT `imagen_raza` FOREIGN KEY (`id_raza`) REFERENCES `raza` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `incursion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `historia` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `incursion_personaje` (
  `id_incursion` int(10) unsigned NOT NULL,
  `id_personaje` int(10) unsigned NOT NULL,
  `sobrevive` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `salud` int(11) unsigned NOT NULL DEFAULT 0,
  `finalizada` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_incursion`,`id_personaje`),
  KEY `incursiones_personaje` (`id_personaje`),
  CONSTRAINT `incursiones_incursion` FOREIGN KEY (`id_incursion`) REFERENCES `incursion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `incursiones_personaje` FOREIGN KEY (`id_personaje`) REFERENCES `personaje` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene la relación entre los personajes que participaron en cada incursión.\r\nAdemás contiene información sobre el desempeño del personaje durante la incursión.';

CREATE TABLE IF NOT EXISTS `inventario` (
  `id_personaje` int(10) unsigned NOT NULL,
  `id_objeto` int(10) unsigned NOT NULL,
  `cantidad` mediumint(9) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_personaje`,`id_objeto`),
  KEY `inventario_objeto` (`id_objeto`),
  CONSTRAINT `inventario_objeto` FOREIGN KEY (`id_objeto`) REFERENCES `objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `inventario_personaje` FOREIGN KEY (`id_personaje`) REFERENCES `personaje` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `jugador` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `correo` varchar(75) NOT NULL,
  `hash` char(60) NOT NULL DEFAULT '',
  `puntos` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `notificaciones` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='tabla de usuarios';

CREATE TABLE IF NOT EXISTS `objeto` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(75) NOT NULL,
  `tipo` enum('arma','armadura','consumible','paquete','agrupacion','base') NOT NULL,
  `descripcion` mediumtext NOT NULL,
  `precio` decimal(9,4) unsigned NOT NULL DEFAULT 0.0000,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `paquete` (
  `id` int(10) unsigned NOT NULL DEFAULT 0,
  `obj1` int(10) unsigned NOT NULL,
  `obj2` int(10) unsigned NOT NULL,
  `ambos` tinyint(1) unsigned NOT NULL DEFAULT 0,
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
  `descripcion` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `pasiva_raza` (
  `id_raza` tinyint(3) unsigned NOT NULL,
  `id_pasiva` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id_raza`,`id_pasiva`),
  KEY `FK_pasiva_raza_dndmanager.pasiva` (`id_pasiva`),
  CONSTRAINT `FK_pasiva_raza_dndmanager.pasiva` FOREIGN KEY (`id_pasiva`) REFERENCES `pasiva` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_pasiva_raza_dndmanager.raza` FOREIGN KEY (`id_raza`) REFERENCES `raza` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contiene la relación entre las pasivas y las razas.\r\nCada raza tiene ninguna, una o varias pasivas.';

CREATE TABLE IF NOT EXISTS `personaje` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `propietario` mediumint(9) unsigned NOT NULL,
  `raza` tinyint(3) unsigned NOT NULL,
  `clase` tinyint(3) unsigned NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `historia` mediumtext DEFAULT NULL,
  `experiencia` int(10) unsigned NOT NULL DEFAULT 0,
  `dinero` decimal(20,6) unsigned NOT NULL DEFAULT 0.000000,
  `puntos_habilidad` smallint(5) unsigned NOT NULL DEFAULT 0,
  `estado` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `personaje_clase` (`clase`),
  KEY `personaje_raza` (`raza`),
  KEY `pesonaje_propietario` (`propietario`),
  CONSTRAINT `personaje_clase` FOREIGN KEY (`clase`) REFERENCES `clase` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `personaje_raza` FOREIGN KEY (`raza`) REFERENCES `raza` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `pesonaje_propietario` FOREIGN KEY (`propietario`) REFERENCES `jugador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla con la información de cada personaje.';

CREATE TABLE IF NOT EXISTS `propuestas` (
  `id_jugador` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `id_prop` int(10) unsigned NOT NULL,
  `tipo` enum('clase','raza','objeto','habilidad','efecto','pasiva','idioma','atributo') NOT NULL,
  `aceptado` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `fecha` date NOT NULL DEFAULT curdate(),
  PRIMARY KEY (`id_jugador`,`id_prop`,`tipo`) USING BTREE,
  CONSTRAINT `jugador_propuesta` FOREIGN KEY (`id_jugador`) REFERENCES `jugador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `prop_arma` (
  `id` int(10) unsigned NOT NULL,
  `material` varchar(50) NOT NULL,
  `modificador` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `doble` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `combate` enum('fisico','magico') NOT NULL,
  `rango` tinyint(3) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  CONSTRAINT `FK_arma_prop_objeto_prop` FOREIGN KEY (`id`) REFERENCES `prop_objeto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='Contiene información de las armas disponibles';

CREATE TABLE IF NOT EXISTS `prop_armadura` (
  `id` int(10) unsigned NOT NULL,
  `material` varchar(50) NOT NULL,
  `modificador` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `corporal` enum('Cabeza','Cuerpo','Piernas','Pies','Manos','Dedos','Cola','Cuernos','Cara','Otro') NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  CONSTRAINT `FK_armadura_prop_objeto_prop` FOREIGN KEY (`id`) REFERENCES `prop_objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='Contiene información de las armaduras disponibles';

CREATE TABLE IF NOT EXISTS `prop_atributo` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `nombre` varchar(15) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `nombre` (`nombre`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `prop_atributo_raza` (
  `id_atributo` tinyint(3) unsigned NOT NULL,
  `id_raza` tinyint(3) unsigned NOT NULL,
  `cantidad` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_atributo`,`id_raza`) USING BTREE,
  KEY `atributos_rz_raza` (`id_raza`) USING BTREE,
  CONSTRAINT `FK_prop_atributo_raza_atributo` FOREIGN KEY (`id_atributo`) REFERENCES `atributo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_prop_atributo_raza_prop_raza` FOREIGN KEY (`id_raza`) REFERENCES `prop_raza` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='Contiene los atributos predeterminados de una raza';

CREATE TABLE IF NOT EXISTS `prop_base` (
  `id` int(10) unsigned NOT NULL,
  `basico` tinyint(1) NOT NULL DEFAULT 0,
  `uso` text NOT NULL DEFAULT 'Desconocido',
  PRIMARY KEY (`id`) USING BTREE,
  CONSTRAINT `FK_base_prop_objeto_prop` FOREIGN KEY (`id`) REFERENCES `prop_objeto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='Objetos de tipo ''base'' cuyos usos pueden ser variados entre materiales o básicos y objetos especiales';

CREATE TABLE IF NOT EXISTS `prop_base_evento` (
  `id_objeto` int(10) unsigned NOT NULL,
  `id_evento` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_objeto`,`id_evento`) USING BTREE,
  KEY `eventos_evento` (`id_evento`) USING BTREE,
  CONSTRAINT `FK_prop_base_evento_prop_base` FOREIGN KEY (`id_objeto`) REFERENCES `prop_base` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_prop_base_evento_prop_evento` FOREIGN KEY (`id_evento`) REFERENCES `prop_evento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='Contiene la relación entre los objetos ''base'' y los eventos en los que participan.';

CREATE TABLE IF NOT EXISTS `prop_clase` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `dado_golpe` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `equipo_inicial` int(10) unsigned NOT NULL,
  `hp_atr` tinyint(3) unsigned NOT NULL,
  `hp_mod` tinyint(2) unsigned NOT NULL DEFAULT 1,
  `def_atr` tinyint(3) unsigned NOT NULL,
  `def_mod` tinyint(2) unsigned NOT NULL DEFAULT 1,
  `golpe_atr` tinyint(3) unsigned NOT NULL,
  `golpe_mod` tinyint(2) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `equipo_ini_clase` (`equipo_inicial`) USING BTREE,
  KEY `hp_atr` (`hp_atr`) USING BTREE,
  KEY `def_atr` (`def_atr`) USING BTREE,
  KEY `golpe_atr` (`golpe_atr`) USING BTREE,
  CONSTRAINT `prop_def_atr` FOREIGN KEY (`def_atr`) REFERENCES `atributo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `prop_equipo_ini_clase` FOREIGN KEY (`equipo_inicial`) REFERENCES `objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `prop_golpe_atr` FOREIGN KEY (`golpe_atr`) REFERENCES `atributo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `prop_hp_atr` FOREIGN KEY (`hp_atr`) REFERENCES `atributo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `prop_clase_habilidad` (
  `id_clase` tinyint(3) unsigned NOT NULL,
  `id_habilidad` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_clase`,`id_habilidad`) USING BTREE,
  KEY `habilidades_class_habilidad` (`id_habilidad`) USING BTREE,
  CONSTRAINT `FK_prop_clase_habilidad_prop_clase` FOREIGN KEY (`id_clase`) REFERENCES `prop_clase` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `prop_clase_habilidad_ibfk_2` FOREIGN KEY (`id_habilidad`) REFERENCES `habilidad` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='Contiene la relación entre las clases y sus habilidades predeterminadas.';

CREATE TABLE IF NOT EXISTS `prop_consumible` (
  `id` int(10) unsigned NOT NULL,
  `usos` smallint(5) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  CONSTRAINT `FK_prop_consumible_prop_objeto` FOREIGN KEY (`id`) REFERENCES `prop_objeto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `prop_efecto` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `cantidad` mediumint(8) unsigned DEFAULT NULL,
  `duracion` tinyint(4) unsigned DEFAULT NULL,
  `tipo` enum('damage','status','debuf','buff','other','none') NOT NULL DEFAULT 'none',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `nombre` (`nombre`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `prop_efecto_habilidad` (
  `id_efecto` int(10) unsigned NOT NULL,
  `id_habilidad` smallint(5) unsigned NOT NULL DEFAULT 0,
  `modificador` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_efecto`,`id_habilidad`) USING BTREE,
  KEY `efectos_hab_habilidad` (`id_habilidad`) USING BTREE,
  CONSTRAINT `FK_prop_efecto_habilidad_prop_habilidad` FOREIGN KEY (`id_habilidad`) REFERENCES `prop_habilidad` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `prop_efecto_habilidad_ibfk_1` FOREIGN KEY (`id_efecto`) REFERENCES `efecto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='Relaciona las habilidades con uno o varios efectos';

CREATE TABLE IF NOT EXISTS `prop_efecto_objeto` (
  `id_efecto` int(10) unsigned NOT NULL,
  `id_objeto` int(10) unsigned NOT NULL,
  `modificador` tinyint(4) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_efecto`,`id_objeto`) USING BTREE,
  KEY `efectos_obj_objeto` (`id_objeto`) USING BTREE,
  CONSTRAINT `FK_prop_efecto_objeto_prop_objeto` FOREIGN KEY (`id_objeto`) REFERENCES `prop_objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `prop_efecto_objeto_ibfk_1` FOREIGN KEY (`id_efecto`) REFERENCES `efecto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='Contiene la relación entre los objetos los efectos que estos pueden provocar';

CREATE TABLE IF NOT EXISTS `prop_efecto_pasiva` (
  `id_efecto` int(10) unsigned NOT NULL,
  `id_pasiva` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `modificador` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_efecto`,`id_pasiva`) USING BTREE,
  KEY `efectos_ps_pasiva` (`id_pasiva`) USING BTREE,
  CONSTRAINT `FK_prop_efecto_pasiva_prop_pasiva` FOREIGN KEY (`id_pasiva`) REFERENCES `prop_pasiva` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `prop_efecto_pasiva_ibfk_1` FOREIGN KEY (`id_efecto`) REFERENCES `efecto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='Contiene la relación entre los efectos que aporta cada pasiva';

CREATE TABLE IF NOT EXISTS `prop_evento` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `nombre` (`nombre`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `prop_habilidad` (
  `id` smallint(5) unsigned NOT NULL DEFAULT 0,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `tipo` enum('fisico','magico','estado','campo','aux','otro') NOT NULL,
  `nivel` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `nombre` (`nombre`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `prop_idioma` (
  `id` smallint(5) unsigned NOT NULL DEFAULT 0,
  `nombre` varchar(20) NOT NULL,
  `descripcion` varchar(500) DEFAULT NULL,
  `id_pasiva` mediumint(7) unsigned NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `nombre` (`nombre`) USING BTREE,
  UNIQUE KEY `id_pasiva` (`id_pasiva`),
  CONSTRAINT `prop_idioma_pasiva` FOREIGN KEY (`id_pasiva`) REFERENCES `prop_pasiva` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `prop_idioma_raza` (
  `id_raza` tinyint(3) unsigned NOT NULL,
  `id_idioma` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_raza`,`id_idioma`) USING BTREE,
  KEY `idiomas_idioma` (`id_idioma`) USING BTREE,
  CONSTRAINT `FK_prop_idioma_raza_prop_raza` FOREIGN KEY (`id_raza`) REFERENCES `prop_raza` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `prop_idioma_raza_ibfk_1` FOREIGN KEY (`id_idioma`) REFERENCES `idioma` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='Relaciones entre los idiomas y las razas (indica qué razas saben hablar qué idiomas de manera predeterminada)';

CREATE TABLE IF NOT EXISTS `prop_imagen_clase` (
  `id_clase` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `img_data` longblob NOT NULL,
  PRIMARY KEY (`id_clase`) USING BTREE,
  CONSTRAINT `FK_prop_imagen_clase_prop_clase` FOREIGN KEY (`id_clase`) REFERENCES `prop_clase` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `prop_imagen_objeto` (
  `id_objeto` int(10) unsigned NOT NULL,
  `img_data` longblob NOT NULL,
  PRIMARY KEY (`id_objeto`) USING BTREE,
  CONSTRAINT `prop_imagen_objeto_ibfk_1` FOREIGN KEY (`id_objeto`) REFERENCES `objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `prop_imagen_raza` (
  `id_raza` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `img_data` longblob NOT NULL,
  PRIMARY KEY (`id_raza`) USING BTREE,
  CONSTRAINT `FK_prop_imagen_raza_prop_raza` FOREIGN KEY (`id_raza`) REFERENCES `prop_raza` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `prop_objeto` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(75) NOT NULL,
  `tipo` enum('arma','armadura','consumible','paquete','agrupacion','base') NOT NULL,
  `descripcion` mediumtext NOT NULL,
  `precio` decimal(9,4) unsigned NOT NULL DEFAULT 0.0000,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `prop_paquete` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `obj1` int(10) unsigned NOT NULL,
  `obj2` int(10) unsigned NOT NULL,
  `ambos` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `obj1_obj2` (`obj1`,`obj2`) USING BTREE,
  KEY `objeto_paquete_objeto2` (`obj2`) USING BTREE,
  CONSTRAINT `prop_paquete_ibfk_2` FOREIGN KEY (`obj1`) REFERENCES `objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `prop_paquete_ibfk_3` FOREIGN KEY (`obj2`) REFERENCES `objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `prop_pasiva` (
  `id` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `nombre` (`nombre`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `prop_pasiva_raza` (
  `id_raza` tinyint(3) unsigned NOT NULL,
  `id_pasiva` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id_raza`,`id_pasiva`) USING BTREE,
  KEY `FK_pasiva_raza_prop_pasiva` (`id_pasiva`) USING BTREE,
  CONSTRAINT `FK_prop_pasiva_raza_pasiva` FOREIGN KEY (`id_pasiva`) REFERENCES `pasiva` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `prop_pasiva_raza_ibfk_2` FOREIGN KEY (`id_raza`) REFERENCES `prop_raza` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='Contiene la relación entre las pasivas y las razas.\r\nCada raza tiene ninguna, una o varias pasivas.';

CREATE TABLE IF NOT EXISTS `prop_raza` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `caracteristicas` text NOT NULL,
  `historia` mediumtext NOT NULL,
  `velocidad` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `raza` (`nombre`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `raza` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `nombre` varchar(30) NOT NULL,
  `caracteristicas` text NOT NULL,
  `historia` mediumtext NOT NULL,
  `velocidad` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `raza` (`nombre`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `inventario_delete_zero` AFTER UPDATE ON `inventario` FOR EACH ROW BEGIN
 	DELETE FROM inventario WHERE cantidad = 0;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `updateAcepted` AFTER UPDATE ON `propuestas` FOR EACH ROW BEGIN

	IF OLD.id_prop <> NEW.id_prop THEN
      UPDATE propuestas
      SET aceptado = 1
      WHERE id_jugador = OLD.id_jugador
         AND id_prop = OLD.id_prop
         AND tipo = OLD.tipo
         AND aceptado = 0;
   END IF;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

SET foreign_key_checks = 1;
