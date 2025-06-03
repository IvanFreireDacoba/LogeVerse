CREATE DATABASE IF NOT EXISTS `dndmanager`
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='tabla de usuarios';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla con la información de cada personaje.';

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

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
  `coste` smallint(5) unsigned NOT NULL DEFAULT 0,
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `prop_pasiva_raza` (
  `id_raza` tinyint(3) unsigned NOT NULL,
  `id_pasiva` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id_raza`,`id_pasiva`) USING BTREE,
  KEY `FK_pasiva_raza_prop_pasiva` (`id_pasiva`) USING BTREE,
  CONSTRAINT `FK_prop_pasiva_raza_pasiva` FOREIGN KEY (`id_pasiva`) REFERENCES `pasiva` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `prop_pasiva_raza_ibfk_2` FOREIGN KEY (`id_raza`) REFERENCES `prop_raza` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='Contiene la relación entre las pasivas y las razas.\r\nCada raza tiene ninguna, una o varias pasivas.';

CREATE TABLE IF NOT EXISTS `prop_raza` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `nombre` varchar(30) NOT NULL,
  `caracteristicas` text NOT NULL,
  `historia` mediumtext NOT NULL,
  `velocidad` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `raza` (`nombre`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `raza` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `nombre` varchar(30) NOT NULL,
  `caracteristicas` text NOT NULL,
  `historia` mediumtext NOT NULL,
  `velocidad` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `raza` (`nombre`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DELIMITER //
CREATE PROCEDURE `aceptarArma`(
	IN `armaID` INT(10) UNSIGNED,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
	DECLARE nuevoID INT UNSIGNED;
   DECLARE n_material VARCHAR(50);
   DECLARE n_modificador TINYINT(3);
   DECLARE n_doble ENUM('true', 'false');
   DECLARE n_combate ENUM('fisico', 'magico');
   DECLARE n_rango TINYINT(3);
   DECLARE existe BOOLEAN DEFAULT FALSE;
   
   START TRANSACTION;
   
   CALL aceptarObjeto(armaID, nuevoID);
   IF nuevoID > 0 THEN
   	SELECT TRUE, material, modificador, doble, combate, rango
   	INTO existe, n_material, n_modificador, n_doble, n_combate, n_rango
   	FROM prop_arma
   	WHERE id = armaID;
   	IF existe THEN
   		INSERT INTO arma (id, material, modificador, doble, combate, rango)
   		VALUES (nuevoID, n_material, n_modificador, n_doble, n_combate, n_rango);
   		DELETE FROM prop_arma WHERE id = armaID;
   		DELETE FROM prop_objeto WHERE id = armaID;
   		SET p_resultado = nuevoID;
   		COMMIT;
   	ELSE
   		SET p_resultado = nuevoID;
   		ROLLBACK;
		END IF;
   ELSE
   	SET p_resultado = nuevoID;
   	ROLLBACK;
   END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `aceptarArmadura`(
	IN `armaduraID` INT(10) UNSIGNED,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
	DECLARE nuevoID INT UNSIGNED;
   DECLARE n_material VARCHAR(50);
   DECLARE n_modificador TINYINT(3);
   DECLARE n_corporal ENUM('Cabeza','Cuerpo','Piernas','Pies','Manos','Dedos','Cola','Cuernos','Cara','Otro');
   DECLARE existe BOOLEAN DEFAULT FALSE;
   
   START TRANSACTION;
   
   CALL aceptarObjeto(armaduraID, nuevoID);
   IF nuevoID > 0 THEN
   	SELECT TRUE, material, modificador, corporal
   	INTO existe, n_material, n_modificador, n_corporal
   	FROM prop_armadura
   	WHERE id = armaduraID;
   	IF existe THEN
   		INSERT INTO armadura (id, material, modificador, corporal)
   		VALUES (nuevoID, n_material, n_modificador, n_corporal);
   		DELETE FROM prop_armadura WHERE id = armaduraID;
   		DELETE FROM prop_objeto WHERE id = armaduraID;
   		SET p_resultado = nuevoID;
   		COMMIT;
   	ELSE
   		SET p_resultado = nuevoID;
   		ROLLBACK;
		END IF;
   ELSE
   	SET p_resultado = nuevoID;
   	ROLLBACK;
   END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `aceptarAtributo`(
	IN `atributoID` TINYINT(3) UNSIGNED,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
	DECLARE n_nombre VARCHAR(15);
   DECLARE n_descripcion TEXT;
   DECLARE nuevoID TINYINT(3) UNSIGNED DEFAULT 0;
   DECLARE existe BOOLEAN DEFAULT FALSE;
   SELECT TRUE, nombre, descripcion INTO existe, n_nombre, n_descripcion
   FROM prop_atributo WHERE id = atributoID;
   IF existe THEN
   	START TRANSACTION;
   	INSERT INTO atributo (nombre, descripcion) VALUES (n_nombre, n_descripcion);
   	SET nuevoID = LAST_INSERT_ID();
   	IF nuevoID > 0 THEN
   		DELETE FROM prop_atributo WHERE id = atributoID;
   		COMMIT;
   	ELSE
   		SET nuevoID = 0;
   		ROLLBACK;
   	END IF;
   END IF;
   SET p_resultado = nuevoID;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `aceptarClase`(
	IN `claseID` TINYINT(3) UNSIGNED,
	OUT `p_resultado` TINYINT UNSIGNED
)
BEGIN
	DECLARE nuevoID TINYINT UNSIGNED DEFAULT 0;
   DECLARE n_nombre VARCHAR(50);
   DECLARE n_descripcion TEXT;
   DECLARE n_dado_golpe TINYINT(10) UNSIGNED;
   DECLARE n_equipo_inicial INT UNSIGNED;
   DECLARE n_hp_atr TINYINT(3) UNSIGNED;
   DECLARE n_hp_mod TINYINT(2) UNSIGNED;
   DECLARE n_def_atr TINYINT(3) UNSIGNED;
   DECLARE n_def_mod TINYINT(2) UNSIGNED;
   DECLARE n_golpe_atr TINYINT(3) UNSIGNED;
   DECLARE n_golpe_mod TINYINT(2) UNSIGNED;
   DECLARE existe BOOLEAN DEFAULT FALSE;
   SELECT TRUE, nombre, descripcion, dado_golpe, equipo_inicial, hp_atr, hp_mod, def_atr, def_mod, golpe_atr, golpe_mod
   	INTO existe, n_nombre, n_descripcion, n_dado_golpe, n_equipo_inicial, n_hp_atr, n_hp_mod, n_def_atr, n_def_mod, n_golpe_atr, n_golpe_mod
   	FROM prop_clase
   	WHERE id = claseID;
   IF existe THEN
   	START TRANSACTION;
		INSERT INTO clase (nombre, descripcion, dado_golpe, equipo_inicial, hp_atr, hp_mod, def_atr, def_mod, golpe_atr, golpe_mod)
   	VALUES (n_nombre, n_descripcion, n_dado_golpe, n_equipo_inicial, n_hp_atr, n_hp_mod, n_def_atr, n_def_mod, n_golpe_atr, n_golpe_mod);
   	SET nuevoID = LAST_INSERT_ID();
		IF nuevoID > 0 THEN
			#check Habilidades
			INSERT INTO clase_habilidad (id_clase, id_habilidad)
	   	SELECT nuevoID, id_habilidad FROM prop_clase_habilidad WHERE id_clase = claseID;
	   	#check Imagen
			INSERT INTO imagen_clase (id_clase, img_data)
	   	SELECT nuevoID, img_data FROM prop_imagen_clase WHERE id_clase = claseID;
	   	#Borrar Habilidades e Imagenes
			DELETE FROM prop_clase_habilidad WHERE id_clase = claseID;
			DELETE FROM prop_imagen_clase WHERE id_clase = claseID;
			#Borrar Clase (último por FK)
	   	DELETE FROM prop_clase WHERE id = claseID;
			COMMIT;
	   ELSE
	   	SET nuevoID = 0;
	   	ROLLBACK;
	   END IF;
   END IF;
	SET p_resultado = nuevoID;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `aceptarConsumible`(
	IN `consumibleID` INT(10) UNSIGNED,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
	DECLARE nuevoID INT(10) UNSIGNED;
   DECLARE n_usos SMALLINT(5) UNSIGNED;
   DECLARE existe BOOLEAN DEFAULT FALSE;
   
   START TRANSACTION;
   CALL aceptarObjeto(consumibleID, nuevoID);
   IF nuevoID > 0 THEN
   	SELECT TRUE, usos
   	INTO existe, n_usos
		FROM prop_consumible
   	WHERE id = consumibleID;
   	IF existe THEN
   		INSERT INTO consumible (id, usos)
    		VALUES (nuevoID, n_usos);
   		DELETE FROM prop_consumible WHERE id = consumibleID;
   		DELETE FROM prop_objeto WHERE id = consumibleID;
   		SET p_resultado = nuevoID;
   		COMMIT;
   	ELSE
   		SET p_resultado = nuevoID;
   		ROLLBACK;
		END IF;
   ELSE
   	SET p_resultado = nuevoID;
   	ROLLBACK;
   END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `aceptarEfecto`(
	IN `efectoID` INT(10) UNSIGNED,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
	DECLARE n_nombre VARCHAR(50);
   DECLARE n_cantidad MEDIUMINT(8) UNSIGNED;
   DECLARE n_descripcion VARCHAR(250);
   DECLARE n_duracion TINYINT(4);
   DECLARE n_tipo ENUM('damage','status','debuf','buff','other','none') DEFAULT 'none';
   DECLARE nuevoID INT(10) UNSIGNED DEFAULT 0;
   DECLARE existe BOOLEAN DEFAULT FALSE;
   SELECT TRUE, nombre, descripcion, cantidad, duracion, tipo INTO existe, n_nombre, n_descripcion, n_cantidad, n_duracion, n_tipo
   FROM prop_efecto WHERE id = efectoID;
   IF existe THEN
   	START TRANSACTION;
   	INSERT INTO efecto (nombre, descripcion, cantidad, duracion, tipo) VALUES (n_nombre, n_descripcion, n_cantidad, n_duracion, n_tipo);
   	SET nuevoID = LAST_INSERT_ID();
   	IF nuevoID > 0 THEN
   		DELETE FROM prop_efecto WHERE id = efectoID;
   		COMMIT;
   	ELSE
   		SET nuevoID = 0;
   		ROLLBACK;
   	END IF;
   END IF;
   SET p_resultado = nuevoID;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `aceptarEvento`(
	IN `eventoID` INT(10) UNSIGNED,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
	DECLARE n_nombre VARCHAR(100);
   DECLARE n_descripcion TEXT;
   DECLARE nuevoID INT UNSIGNED DEFAULT 0;
   DECLARE existe BOOLEAN DEFAULT FALSE;
   SELECT TRUE, nombre, descripcion INTO existe, n_nombre, n_descripcion
   FROM prop_evento WHERE id = eventoID;
   IF existe THEN
   	START TRANSACTION;
   	INSERT INTO evento (nombre, descripcion) VALUES (n_nombre, n_descripcion);
   	SET nuevoID = LAST_INSERT_ID();
   	IF nuevoID > 0 THEN
			DELETE FROM prop_evento WHERE id = eventoID;
			COMMIT;
		ELSE
			SET nuevoID = 0;
			ROLLBACK;
		END IF;
	END IF;
   SET p_resultado = nuevoID;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `aceptarHabilidad`(
	IN `habilidadID` INT(10) UNSIGNED,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
	DECLARE n_nombre VARCHAR(20);
   DECLARE n_descripcion VARCHAR(500);
   DECLARE nuevoID SMALLINT UNSIGNED DEFAULT 0;
	DECLARE existe BOOLEAN DEFAULT FALSE;
   SELECT TRUE, nombre, descripcion INTO existe, n_nombre, n_descripcion
   FROM prop_habilidad WHERE id = habilidadID;
   IF existe THEN
   	START TRANSACTION;
   	INSERT INTO habilidad (nombre, descripcion) VALUES (n_nombre, n_descripcion);
   	SET nuevoID = LAST_INSERT_ID();
   	IF nuevoID > 0 THEN
			#check Efectos
			INSERT INTO efecto_habilidad (id_efecto, id_habilidad, modificador)
   		SELECT id_efecto, nuevoID, modificador FROM prop_efecto_habilidad WHERE id_habilidad = habilidadID;
   		DELETE FROM prop_efecto_habilidad WHERE id_habilidad = habilidadID;
   		DELETE FROM prop_habilidad WHERE id = habilidadID;
   		COMMIT;
   	ELSE
   		SET nuevoID = 0;
   		ROLLBACK;
		END IF;
	END IF;
   SET p_resultado = nuevoID;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `aceptarIdioma`(
	IN `idiomaID` SMALLINT(5) UNSIGNED,
	OUT `p_resultado` INT
)
BEGIN
	DECLARE n_nombre VARCHAR(20);
   DECLARE n_descripcion VARCHAR(500);
   DECLARE n_id_pas MEDIUMINT(7) UNSIGNED;
   DECLARE n_id_pas_new MEDIUMINT(7) UNSIGNED;
   DECLARE n_nombre_pas VARCHAR(50);
   DECLARE n_descripcion_pas MEDIUMTEXT;
   DECLARE nuevoID TINYINT(3) UNSIGNED DEFAULT 0;
   DECLARE existe BOOLEAN DEFAULT FALSE;
   SELECT TRUE, nombre, descripcion, id_pasiva INTO existe, n_nombre, n_descripcion, n_id_pas
   FROM idioma WHERE id = idiomaID;
   IF existe THEN
   	START TRANSACTION;
   	SELECT nombre, descripcion INTO n_nombre_pas, n_descripcion_pas
		FROM prop_pasiva WHERE id = n_id_pas;
   	INSERT INTO pasiva (nombre, descripcion) VALUES (n_nombre_pas, n_descripcion_pas);
   	SET n_id_pas_new = LAST_INSERT_ID();
   	INSERT INTO idioma (nombre, descripcion, id_pasiva) VALUES (n_nombre, n_descripcion, n_id_pas_new);
   	SET nuevoID = LAST_INSERT_ID();
   	IF nuevoID > 0 THEN
   		DELETE FROM prop_pasiva WHERE id = n_id_pas;
   		DELETE FROM prop_idioma WHERE id = idiomaID;
   		COMMIT;
   	ELSE
   		SET nuevoID = 0;
   		ROLLBACK;
   	END IF;
   END IF;
   SET p_resultado = nuevoID;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `aceptarObjeto`(
	IN `objetoID` INT(10) UNSIGNED,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
	DECLARE n_nombre VARCHAR(50);
   DECLARE n_tipo ENUM('arma','armadura','consumible','paquete','base');
   DECLARE n_descripcion LONGTEXT;
   DECLARE n_precio DECIMAL(9,4);
   DECLARE nuevoID INT UNSIGNED DEFAULT 0;
   DECLARE existe BOOLEAN DEFAULT FALSE;
   SELECT TRUE, nombre, tipo, descripcion, precio
   INTO existe, n_nombre, n_tipo, n_descripcion, n_precio
   FROM prop_objeto
   WHERE id = objetoID;
   IF existe THEN
   	CALL crearObjeto(n_nombre, n_tipo, n_descripcion, n_precio, nuevoID);
   	IF nuevoID > 0 THEN
	   	#Check Efectos
	   	INSERT INTO efecto_objeto (id_efecto, id_objeto, modificador)
	   	SELECT id_efecto, nuevoID, modificador FROM prop_efecto_objeto WHERE id_objeto = objetoID;
	   	DELETE FROM prop_efecto_objeto WHERE id_objeto = objetoID;
	   	#Check Imagen
	   	INSERT INTO imagen_objeto (id_objeto, img_data)
	   	SELECT nuevoID, img_data FROM prop_imagen_objeto WHERE id_objeto = objetoID;
	   	DELETE FROM prop_imagen_objeto WHERE id_objeto = objetoID;
	   END IF;
   END IF;
   SET p_resultado = nuevoID;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `aceptarObjetoBase`(
	IN `baseID` INT(10) UNSIGNED,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
	DECLARE nuevoID INT UNSIGNED;
   DECLARE n_basico ENUM('true', 'false');
   DECLARE n_uso TEXT;
   DECLARE existe BOOLEAN DEFAULT FALSE;
   
   START TRANSACTION;
   CALL aceptarObjeto(baseID, nuevoID);
   IF nuevoID > 0 THEN
   	SELECT TRUE, basico, uso
   	INTO existe, n_basico, n_uso
   	FROM prop_base
   	WHERE id = baseID;
   	IF existe THEN
   		INSERT INTO base (id, basico, uso)
   		VALUES (nuevoID, n_basico, n_uso);
   		#Check Eventos
   		INSERT INTO base_evento (id_evento, id_objeto)
   		SELECT id_evento, id_objeto FROM prop_base_evento WHERE id_objeto = baseID;
   		#Eliminar el objeto de la base de datos
   		DELETE FROM prop_base_evento WHERE id_objeto = baseID;
   		DELETE FROM prop_base WHERE id = baseID;
   		DELETE FROM prop_objeto WHERE id = baseID;
   		SET p_resultado = nuevoID;
   		COMMIT;
   	ELSE
   		SET p_resultado = nuevoID;
   		ROLLBACK;
		END IF;
   ELSE
   	SET p_resultado = nuevoID;
   	ROLLBACK;
   END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `aceptarPaquete`(
	IN `paqueteID` INT(10) UNSIGNED,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
	DECLARE nuevoID INT UNSIGNED DEFAULT 0;
   DECLARE n_obj1 INT;
   DECLARE n_obj2 INT;
   DECLARE n_ambos ENUM('true', 'false');
   DECLARE existe BOOLEAN DEFAULT FALSE;
   SELECT TRUE INTO existe FROM objeto WHERE id = n_obj1;
   IF existe THEN
   	SET existe = FALSE;
   	SELECT TRUE INTO existe FROM objeto WHERE id = n_obj2;
   	IF existe THEN
   		SET existe = FALSE;
		   SELECT TRUE, obj1, obj2, ambos
		   INTO existe, n_obj1, n_obj2, n_ambos
		   FROM prop_paquete
		   WHERE id = paqueteID;
		   IF existe THEN
		   	START TRANSACTION;
		   	CALL aceptarObjeto(paqueteID, nuevoID);
		   	IF nuevoID > 0 THEN
		   		INSERT INTO paquete (id, obj1, obj2, ambos)
		   		VALUES (nuevoID, n_obj1, n_obj2, n_ambos);
		   		DELETE FROM prop_paquete WHERE id = paqueteID;
		   		DELETE FROM prop_objeto WHERE id = paqueteID;
		   		SET p_resultado = nuevoID;
		   		COMMIT;
		   	ELSE
		   		SET p_resultado = nuevoID;
		   		ROLLBACK;
				END IF;
			ELSE
				SET p_resultado = nuevoID;
		   END IF;
		ELSE
				SET p_resultado = nuevoID;
		END IF;
	ELSE
				SET p_resultado = nuevoID;
   END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `aceptarPasiva`(
	IN `pasivaID` INT(10) UNSIGNED,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
	DECLARE n_nombre VARCHAR(50);
   DECLARE n_descripcion MEDIUMTEXT;
   DECLARE nuevoID MEDIUMINT UNSIGNED DEFAULT 0;
	DECLARE existe BOOLEAN DEFAULT FALSE;
   SELECT TRUE, nombre, descripcion INTO existe, n_nombre, n_descripcion
   FROM prop_pasiva WHERE id = pasivaID;
   IF existe THEN
   	START TRANSACTION;
   	INSERT INTO pasiva (nombre, descripcion) VALUES (n_nombre, n_descripcion);
   	SET nuevoID = LAST_INSERT_ID();
   	IF nuevoID > 0 THEN
			#check Efectos
			INSERT INTO efecto_pasiva (id_efecto, id_pasiva, modificador)
   		SELECT id_efecto, nuevoID, modificador FROM prop_efecto_pasiva WHERE id_pasiva = pasivaID;
   		DELETE FROM prop_efecto_pasiva WHERE id_pasiva = pasivaID;
   		#Borrar prop pasiva
   		DELETE FROM prop_pasiva WHERE id = pasivaID;
   		COMMIT;
   	ELSE
   		SET nuevoID = 0;
   		ROLLBACK;
		END IF;
	END IF;
   SET p_resultado = nuevoID;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `aceptarRaza`(
	IN `razaID` TINYINT(3) UNSIGNED,
	OUT `p_resultado` TINYINT UNSIGNED
)
BEGIN
	DECLARE nuevoID TINYINT UNSIGNED DEFAULT 0;
   DECLARE n_nombre VARCHAR(30);
   DECLARE n_caracteristicas TEXT;
   DECLARE n_historia MEDIUMTEXT;
   DECLARE n_velocidad TINYINT(3) UNSIGNED;
   DECLARE existe BOOLEAN DEFAULT FALSE;
   DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET p_resultado = 0;
   SELECT TRUE, nombre, caracteristicas, historia, velocidad
   	INTO existe, n_nombre, n_caracteristicas, n_historia, n_velocidad
   	FROM prop_raza
   	WHERE id = razaID;
   IF existe THEN
   	START TRANSACTION;
   	INSERT INTO raza (nombre, caracteristicas, historia, velocidad)
   	VALUES (n_nombre, n_caracteristicas, n_historia, n_velocidad);
   	SET nuevoID = LAST_INSERT_ID();
   	IF nuevoID > 0 THEN
   		#check Idiomas
			INSERT INTO idioma_raza (id_raza, id_idioma)
   		SELECT nuevoID, id_idioma FROM prop_idioma_raza WHERE id_raza = razaID;
			#check Atributos
			INSERT INTO atributo_raza (id_atributo, id_raza, cantidad)
   		SELECT nuevoID, id_raza, cantidad FROM prop_atributo_raza WHERE id_raza = razaID;
			#check Pasivas
			INSERT INTO pasiva_raza (id_raza, id_pasiva)
	   	SELECT nuevoID, id_pasiva FROM prop_pasiva_raza WHERE id_raza = razaID;
	   	#check Imagenes
			INSERT INTO imagen_raza (id_raza, img_data)
	   	SELECT nuevoID, img_data FROM prop_imagen_raza WHERE id_raza = razaID;
	   	#Borrar Idiomas, Atributos y Pasivas
	   	DELETE FROM prop_idioma_raza WHERE id_raza = razaID;
	   	DELETE FROM prop_atributo_raza WHERE id_raza = razaID;
	   	DELETE FROM prop_pasiva_raza WHERE id_raza = razaID;
	   	DELETE FROM prop_imagen_raza WHERE id_raza = razaID;
	   	#Borrar raza (último por FK)
	   	DELETE FROM prop_raza WHERE id = razaID;
	   	COMMIT;
   	ELSE
   		SET nuevoID = 0;
   		ROLLBACK;
   	END IF;
   END IF;
   SET p_resultado = nuevoID;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `crearArma`(
	IN `p_nombre` VARCHAR(50),
	IN `p_descripcion` LONGTEXT,
	IN `p_precio` DECIMAL(9,4) UNSIGNED,
	IN `p_material` VARCHAR(50),
	IN `p_modificador` TINYINT(3) UNSIGNED,
	IN `p_doble` BOOLEAN,
	IN `p_combate` ENUM('fisico', 'magico'),
	IN `p_rango` TINYINT(3) UNSIGNED,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
    DECLARE armaID INT UNSIGNED;
    CALL crearObjeto(p_nombre, "arma", p_descripcion, p_precio, armaID);
    IF armaID > 0 THEN
    	INSERT INTO arma (id, material, modificador, doble, combate, rango)
		VALUES (armaID, p_material, p_modificador, p_doble, p_combate, p_rango);
		SET p_resultado = LAST_INSERT_ID();
    ELSE
      SET p_resultado = 0;
   END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `crearArmadura`(
	IN `p_nombre` VARCHAR(50),
	IN `p_descripcion` LONGTEXT,
	IN `p_precio` DECIMAL(9,4) UNSIGNED,
	IN `p_material` VARCHAR(50),
	IN `p_modificador` TINYINT(3) UNSIGNED,
	IN `p_corporal` ENUM('Cabeza','Cuerpo','Piernas','Pies','Manos','Dedos','Cola','Cuernos','Cara','Otro'),
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
    DECLARE armaduraID INT UNSIGNED;
    CALL crearObjeto(p_nombre, "armadura", p_descripcion, p_precio, armaduraID);
    IF armaduraID > 0 THEN
    	INSERT INTO arma (id, material, modificador, corporal)
		VALUES (armaduraID, p_material, p_modificador, p_corporal);
		SET p_resultado = LAST_INSERT_ID();
    ELSE
      SET p_resultado = 0;
   END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `crearConsumible`(
	IN `p_nombre` VARCHAR(50),
	IN `p_descripcion` LONGTEXT,
	IN `p_precio` DECIMAL(9,4) UNSIGNED,
	IN `p_usos` SMALLINT(5) UNSIGNED,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
    DECLARE consumibleID INT UNSIGNED;
    CALL crearObjeto(p_nombre, "consumible", p_descripcion, p_precio, consumibleID);
    IF consumibleID > 0 THEN
    	INSERT INTO consumible (id, usos)
		VALUES (consumibleID, p_usos);
		SET p_resultado = LAST_INSERT_ID();
    ELSE
      SET p_resultado = 0;
   END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `crearObjeto`(
	IN `p_nombre` VARCHAR(255),
	IN `p_tipo` ENUM('arma','armadura','consumible','paquete','base'),
	IN `p_descripcion` TEXT,
	IN `p_precio` DECIMAL(9,4),
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
    DECLARE existe INT DEFAULT 0;
    SELECT COUNT(*) INTO existe FROM objeto WHERE nombre = p_nombre;
    IF existe = 0 THEN
        INSERT INTO objeto (nombre, tipo, descripcion, precio) 
        VALUES (p_nombre, p_tipo, p_descripcion, p_precio);
        SET p_resultado = LAST_INSERT_ID();
    ELSE
        SET p_resultado = 0;
    END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `crearObjetoBase`(
	IN `p_nombre` VARCHAR(50),
	IN `p_descripcion` LONGTEXT,
	IN `p_precio` DECIMAL(9,4) UNSIGNED,
	IN `p_basico` TINYINT(3) UNSIGNED,
	IN `p_uso` TEXT,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
    DECLARE baseID INT UNSIGNED;
    CALL crearObjeto(p_nombre, "base", p_descripcion, p_precio, baseID);
    IF baseID > 0 THEN
    	INSERT INTO base (id, uso)
		VALUES (baseID, p_uso);
		SET p_resultado = LAST_INSERT_ID();
    ELSE
      SET p_resultado = 0;
   END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `crearPaquete`(
	IN `p_nombre` VARCHAR(50),
	IN `p_descripcion` LONGTEXT,
	IN `p_precio` DECIMAL(9,4) UNSIGNED,
	IN `p_id_obj1` INT(10) UNSIGNED,
	IN `p_id_obj2` INT(10) UNSIGNED,
	IN `p_ambos` BOOLEAN,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
	DECLARE existeOb1 TINYINT DEFAULT 0;
	DECLARE existeOb2 TINYINT DEFAULT 0;
   DECLARE paqueteID INT UNSIGNED;
	START TRANSACTION;
	CALL crearObjeto(p_nombre, "paquete", p_descripcion, p_precio, paqueteID);
   IF paqueteID > 0 THEN
   	SELECT COUNT(*) INTO existeOb1 FROM objeto WHERE id = id_obj1;
   	IF existeOb1 = 1 THEN
   		SELECT COUNT(*) INTO existeOb2 FROM objeto WHERE id = id_obj2;
   		IF existeOb2 = 1 THEN
	   		INSERT INTO paquete (id, id_obj1, id_obj2, ambos)
				VALUES (paqueteID, p_id_obj1, p_id_obj2, p_ambos);
				SET p_resultado = LAST_INSERT_ID();
				COMMIT;
			ELSE
				SET p_resultado = 0;
				ROLLBACK;
			END IF;
		ELSE
			SET p_resultado = 0;
			ROLLBACK;
		END IF;
    ELSE
      SET p_resultado = 0;
      ROLLBACK;
   END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `crearPropuesta`(
	IN `p_id_jugador` MEDIUMINT(8) UNSIGNED,
	IN `p_id_prop` INT(10) UNSIGNED,
	IN `p_tipo` ENUM('clase', 'raza', 'objeto',
'habilidad', 'efecto', 'pasiva', 'idioma', 'atributo')
)
BEGIN
	DECLARE existe BOOLEAN DEFAULT FALSE;
	SELECT TRUE
	INTO 	existe
	FROM 	propuestas
	WHERE
			id_jugador = p_id_jugador
	AND	id_prop = p_id_prop
	AND	tipo = p_tipo
	AND	aceptado = 0
	LIMIT 1;
	
	IF NOT existe THEN
		INSERT INTO propuestas (id_jugador, id_prop, tipo, aceptado, fecha)
		VALUES (p_id_jugador, p_id_prop, p_tipo, 0, CURDATE());
	END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `proponerArma`(
	IN `p_nombre` VARCHAR(255),
	IN `p_descripcion` TEXT,
	IN `p_precio` DECIMAL(9,4),
	IN `p_material` VARCHAR(50),
	IN `p_modificador` TINYINT(3) UNSIGNED,
	IN `p_doble` BOOLEAN,
	IN `p_combate` ENUM('fisico', 'magico'),
	IN `p_rango` TINYINT(3) UNSIGNED,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
    DECLARE armaID INT UNSIGNED;
    CALL proponerObjeto(p_nombre, "arma", p_descripcion, p_precio, armaID);
    IF armaID > 0 THEN
    	INSERT INTO prop_arma (id, material, modificador, doble, combate, rango) VALUES (armaID, p_material, p_modificador, p_doble, p_combate, p_rango);
      SET p_resultado = armaID;
    ELSE
    	SET p_resultado = 0;
    END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `proponerArmadura`(
	IN `p_nombre` VARCHAR(255),
	IN `p_descripcion` TEXT,
	IN `p_precio` DECIMAL(9,4),
	IN `p_material` VARCHAR(50),
	IN `p_modificador` TINYINT(3) UNSIGNED,
	IN `p_corporal` ENUM('Cabeza','Cuerpo','Piernas','Pies','Manos','Dedos','Cola','Cuernos','Cara','Otro'),
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
    DECLARE armaduraID INT UNSIGNED;
    CALL proponerObjeto(p_nombre, "armadura", p_descripcion, p_precio, armaduraID);
    IF armaduraID > 0 THEN
    	INSERT INTO  prop_armadura (id, material, modificador, corporal) VALUES (armaduraID, p_material, p_modificador, p_corporal);
      SET p_resultado = armaduraID;
    ELSE
    	SET p_resultado = 0;
    END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `proponerClase`(
	IN `p_nombre` VARCHAR(50),
	IN `p_descripcion` TEXT,
	IN `dado_golpe` TINYINT(3) UNSIGNED,
	IN `p_equipo_inicial` INT(10) UNSIGNED,
	IN `p_hp_atr` TINYINT(3) UNSIGNED,
	IN `p_hp_mod` TINYINT(2) UNSIGNED,
	IN `p_def_atr` TINYINT(3) UNSIGNED,
	IN `p_def_mod` TINYINT(2) UNSIGNED,
	IN `p_golpe_atr` TINYINT(3) UNSIGNED,
	IN `p_golpe_mod` TINYINT(2) UNSIGNED,
	OUT `p_resultado` TINYINT(3) UNSIGNED
)
BEGIN
	 DECLARE existeClase TINYINT DEFAULT 1;
	 DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET p_resultado = 0;
	 
	 SELECT COUNT(*) INTO existeClase FROM raza WHERE nombre = p_nombre;
	 IF existeClase = 0 THEN
    	INSERT INTO prop_clase
			(nombre, descripcion, dado_golpe, equipo_inicial, hp_atr, hp_mod, def_atr, def_mod, golpe_atr, golpe_mod)
			VALUES
			(p_nombre, p_descripcion, dado_golpe, p_equipo_inicial, p_hp_atr, p_hp_mod, p_def_atr, p_def_mod, p_golpe_atr, p_golpe_mod);
    	SET p_resultado = LAST_INSERT_ID();
    ELSE
		SET p_resultado = 0;
	 END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `proponerConsumible`(
	IN `p_nombre` VARCHAR(255),
	IN `p_descripcion` TEXT,
	IN `p_precio` DECIMAL(9,4),
	IN `p_usos` SMALLINT(5) UNSIGNED,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
    DECLARE consumibleID INT UNSIGNED;
    CALL proponerObjeto(p_nombre, "consumible", p_descripcion, p_precio, consumibleID);
    IF consumibleID > 0 THEN
    	INSERT INTO prop_consumible (id, usos) VALUES (consumibleID, p_usos);
      SET p_resultado = consumibleID;
    ELSE
    	SET p_resultado = 0;
    END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `proponerIdioma`(
	IN `p_nombre` VARCHAR(20),
	IN `p_descripcion` VARCHAR(500),
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
    DECLARE idiomaID SMALLINT(5) UNSIGNED;
    DECLARE id_pas MEDIUMINT(7) UNSIGNED;
    START TRANSACTION;
    INSERT INTO prop_pasiva (nombre, descripcion) 
    VALUES (CONCAT("Mente Políglota: ", p_nombre), CONCAT("Dominas el ", p_nombre, ", eres capaz de leer y conversar en este idioma."));
    SET id_pas = LAST_INSERT_ID();
    IF id_pas IS NOT NULL THEN
        INSERT INTO prop_idioma (nombre, descripcion, id_pasiva) 
        VALUES (p_nombre, p_descripcion, id_pas);
        SET p_resultado = LAST_INSERT_ID();
        COMMIT;
    ELSE
        ROLLBACK;
        SET p_resultado = 0;
    END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `proponerObjeto`(
	IN `p_nombre` VARCHAR(255),
	IN `p_tipo` VARCHAR(50),
	IN `p_descripcion` TEXT,
	IN `p_precio` DECIMAL(9,4),
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
    DECLARE existe INT DEFAULT 0;
    SELECT COUNT(*) INTO existe FROM objeto WHERE nombre = p_nombre;
    IF existe = 0 THEN
    		SELECT COUNT(*) INTO existe FROM prop_objeto WHERE nombre = p_nombre;
    		IF existe = 0 THEN
        		INSERT INTO prop_objeto (nombre, tipo, descripcion, precio) 
        		VALUES (p_nombre, p_tipo, p_descripcion, p_precio);
        		SET p_resultado = LAST_INSERT_ID();
        	ELSE
        		SET p_resultado = 0;
        	END IF;
    ELSE
        SET p_resultado = 0;
    END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `proponerObjetoBase`(
	IN `p_nombre` VARCHAR(255),
	IN `p_descripcion` TEXT,
	IN `p_precio` DECIMAL(9,4),
	IN `p_basico` TEXT,
	IN `p_uso` BOOLEAN,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
    DECLARE baseID INT UNSIGNED;
    CALL proponerObjeto(p_nombre, "base", p_descripcion, p_precio, baseID);
    IF baseID > 0 THEN
    	INSERT INTO prop_base (id, basico, uso) VALUES (baseID, p_basico, p_uso);
      SET p_resultado = baseID;
    ELSE
    	SET p_resultado = 0;
    END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `proponerPaquete`(
	IN `p_nombre` VARCHAR(50),
	IN `p_descripcion` LONGTEXT,
	IN `p_precio` DECIMAL(9,4) UNSIGNED,
	IN `p_id_obj1` INT(10) UNSIGNED,
	IN `p_id_obj2` INT(10) UNSIGNED,
	IN `p_ambos` BOOLEAN,
	OUT `p_resultado` INT UNSIGNED
)
BEGIN
	DECLARE existeOb1 TINYINT DEFAULT 0;
	DECLARE existeOb2 TINYINT DEFAULT 0;
   DECLARE paqueteID INT UNSIGNED;
	START TRANSACTION;
	CALL proponerObjeto(p_nombre, "paquete", p_descripcion, p_precio, paqueteID);
   IF paqueteID > 0 THEN
   	SELECT COUNT(*) INTO existeOb1 FROM objeto WHERE id = id_obj1;
   	IF existeOb1 = 1 THEN
   		SELECT COUNT(*) INTO existeOb2 FROM objeto WHERE id = id_obj2;
   		IF existeOb2 = 1 THEN
	   		INSERT INTO prop_paquete (id, id_obj1, id_obj2, ambos)
				VALUES (paqueteID, p_id_obj1, p_id_obj2, p_ambos);
				SET p_resultado = LAST_INSERT_ID();
				COMMIT;
			ELSE
				SET p_resultado = 0;
				ROLLBACK;
			END IF;
		ELSE
			SET p_resultado = 0;
			ROLLBACK;
		END IF;
    ELSE
      SET p_resultado = 0;
      ROLLBACK;
   END IF;
END//
DELIMITER ;

DELIMITER //
CREATE PROCEDURE `proponerRaza`(
	IN `p_nombre` VARCHAR(30),
	IN `p_caracteristicas` TEXT,
	IN `p_historia`MEDIUMTEXT,
	IN `p_velocidad` TINYINT(3) UNSIGNED,
	OUT `p_resultado` TINYINT(3) UNSIGNED
)
BEGIN
	 DECLARE existeRaza TINYINT DEFAULT 1;
	 DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET p_resultado = 0;
	 
	 SELECT COUNT(*) INTO existeRaza FROM raza WHERE nombre = p_nombre;
	 IF existeRaza = 0 THEN
    	INSERT INTO prop_raza (nombre, caracteristicas, historia, velocidad) VALUES (p_nombre, p_caracteristicas, p_historia, p_velocidad);
    	SET p_resultado = LAST_INSERT_ID();
    ELSE
		SET p_resultado = 0;
	 END IF;
END//
DELIMITER ;

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