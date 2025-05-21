INSERT INTO `atributo` (`id`, `nombre`, `descripcion`) VALUES
	(1, 'Fuerza', 'La capacidad física de un personaje para levantar, cargar y atacar con armas cuerpo a cuerpo.'),
	(2, 'Destreza', 'La habilidad para moverse con rapidez, agilidad y reflejos, así como la capacidad para usar armas a distancia.'),
	(3, 'Constitución', 'La salud física y resistencia de un personaje, que afecta a la cantidad de puntos de vida.'),
	(4, 'Inteligencia', 'La capacidad de razonamiento lógico, conocimiento y aprendizaje.'),
	(5, 'Sabiduría', 'La percepción y juicio, que incluye la intuición y el sentido común.'),
	(6, 'Carisma', 'La capacidad para influir en otros, la presencia personal y la persuasión.');

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

INSERT INTO `base` (`id`, `basico`, `uso`) VALUES
	(1, 1, '"Carente de uso"');

INSERT INTO `clase` (`id`, `nombre`, `descripcion`, `dado_golpe`, `equipo_inicial`, `hp_atr`, `hp_mod`, `def_atr`, `def_mod`, `golpe_atr`, `golpe_mod`) VALUES
	(1, 'Paquete', 'Carente de habilidades. Son seres cuyo desempeño brilla por su ausencia en cualquier ámbito.', 6, 1, 1, 1, 4, 1, 3, 1);

INSERT INTO `constantes` (`id`, `nombre`, `valor`) VALUES
	(1, 'max_atr_points', '3');

INSERT INTO `efecto` (`id`, `nombre`, `descripcion`, `cantidad`, `duracion`, `tipo`) VALUES
	(1, 'Armadura', 'Protege de los golpes físicos', 1, 3, 'buff'),
	(2, 'Daño', 'Causa daño al objetivo', 1, 0, 'damage');

INSERT INTO `incursion` (`id`, `nombre`, `historia`) VALUES
	(1, 'Ninguna', 'No está realizando ninguna incursión');

INSERT INTO `objeto` (`id`, `nombre`, `tipo`, `descripcion`, `precio`) VALUES
	(1, 'Nada', 'base', 'La ausencia de algo, la nada.', 0.0000);

INSERT INTO `raza` (`id`, `nombre`, `caracteristicas`, `historia`, `velocidad`) VALUES
	(1, 'Enano', 'Robustos y resistentes', 'Expertos mineros y herreros', 25),
	(2, 'Elfo', 'Gráciles e inteligentes', 'Vinculados a la naturaleza y la magia', 30),
	(3, 'Humano', 'Versátiles y ambiciosos', 'La raza más común y adaptable', 30),
	(4, 'Dracónido', 'Descendientes de dragones', 'Herederos de linajes dracónicos', 30),
	(5, 'Gnomo', 'Curiosos e ingeniosos', 'Amantes de la invención y la magia', 25),
	(6, 'Semiorco', 'Fuertes y resistentes', 'Descendientes de humanos y orcos', 30),
	(7, 'Tiflin', 'Marcados por linaje infernal', 'Descienden de pactos demoníacos', 30);