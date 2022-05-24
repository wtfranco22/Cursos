-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 24-05-2022 a las 14:18:20
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `curso`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursa`
--

CREATE TABLE `cursa` (
  `idparticipante` varchar(25) NOT NULL,
  `legajo` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cursa`
--

INSERT INTO `cursa` (`idparticipante`, `legajo`) VALUES
('bd-111', 'Coc1'),
('bd-123', 'Coc1'),
('bd-39681637', 'ArtItal'),
('bd-39681637', 'Coc1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso`
--

CREATE TABLE `curso` (
  `legajo` varchar(25) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  `modalidad` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `curso`
--

INSERT INTO `curso` (`legajo`, `nombre`, `descripcion`, `modalidad`) VALUES
('ArtItal', 'Arte Italiano', 'Un modo distinto de acercarnos al arte y a la lengua italiana.', 'grupal'),
('Canto22', 'Canto para adultos', 'Ejercicios de respiración, relajacion, vocalización y repertorio', 'grupal'),
('Coc1', 'Cocina para eventos', 'Aprender a organizar una cocina dentro de un evento, sacando costos y armando menús.', 'individual'),
('ComMan', 'Community Manager', 'Herramientas para aprovechar las ventajas de las redes sociales en la difusión de un producto o servicio.', 'grupal'),
('HeDi1', 'herramientas digitales', 'El mundo digital hace que todo sea más rápido, fácil y seguro. Nos brinda un montón de atajos, nos acerca y nos conecta a miles de lugares y personas', 'individual'),
('Illus1', 'Illustrator', 'Adobe Illustrator es el programa líder de dibujo vectorial, generalmente usado para ilustraciones, diagramas, gráficos y logos.', 'grupal'),
('MicOff', 'Microsoft Office', 'Uso de los programas Word y Excel en comercios, emprendimientos, colegios y empresas.', 'individual'),
('Numer22', 'Numerologia', 'Técnica para el autodescubrimiento y la comprensión de otros por medio del “mensaje energético de los números”.', 'individual'),
('ResyTap', 'Restauracion y tapiceria', 'Restauración de muebles por completo, incorporando múltiples técnicas.', 'individual'),
('Tan2022', 'Tango', 'Tango de salón para baile social orientado al público en general. Individual y parejas.', 'grupal'),
('Yoga22', 'Yoga', 'Una hora de paz para tu cuerpo y mente.', 'individual');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE `genero` (
  `id` int(11) NOT NULL,
  `textId` varchar(2) NOT NULL,
  `value` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`id`, `textId`, `value`) VALUES
(0, 'M', 'MASCULINO'),
(1, 'F', 'FEMENINO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participante`
--

CREATE TABLE `participante` (
  `id` varchar(25) NOT NULL,
  `dni` int(11) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `apellido` varchar(25) NOT NULL,
  `fechanacimiento` varchar(25) DEFAULT NULL,
  `genero` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `participante`
--

INSERT INTO `participante` (`id`, `dni`, `nombre`, `apellido`, `fechanacimiento`, `genero`) VALUES
('bd-111', 111, 'aaa', 'zzz', '2001-01-01', 0),
('bd-123', 123, 'abc', 'xyz', '1996-06-22', 1),
('bd-39681637', 39681637, 'Franco', 'Rodriguez', '1996-06-22', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cursa`
--
ALTER TABLE `cursa`
  ADD PRIMARY KEY (`idparticipante`,`legajo`),
  ADD KEY `legajo` (`legajo`);

--
-- Indices de la tabla `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`legajo`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `participante`
--
ALTER TABLE `participante`
  ADD PRIMARY KEY (`id`),
  ADD KEY `genero` (`genero`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cursa`
--
ALTER TABLE `cursa`
  ADD CONSTRAINT `cursa_ibfk_1` FOREIGN KEY (`idparticipante`) REFERENCES `participante` (`id`),
  ADD CONSTRAINT `cursa_ibfk_2` FOREIGN KEY (`legajo`) REFERENCES `curso` (`legajo`);

--
-- Filtros para la tabla `participante`
--
ALTER TABLE `participante`
  ADD CONSTRAINT `participante_ibfk_1` FOREIGN KEY (`genero`) REFERENCES `genero` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;