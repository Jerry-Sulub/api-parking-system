-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 29-11-2022 a las 03:57:30
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `parking`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fee`
--

CREATE TABLE `fee` (
  `id_fee` int(11) NOT NULL,
  `plate_car` varchar(9) NOT NULL,
  `place` varchar(3) NOT NULL,
  `date_up` date NOT NULL,
  `entry_time` time NOT NULL,
  `departure_time` time DEFAULT '00:00:00',
  `elapset_time` time DEFAULT '00:00:00',
  `price` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `fee`
--

INSERT INTO `fee` (`id_fee`, `plate_car`, `place`, `date_up`, `entry_time`, `departure_time`, `elapset_time`, `price`) VALUES
(7, 'XLQ-231-8', 'A4', '2022-10-11', '20:30:20', '00:00:00', '00:00:00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `place`
--

CREATE TABLE `place` (
  `id_place` varchar(3) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `place`
--

INSERT INTO `place` (`id_place`, `status`) VALUES
('A1', 0),
('A10', 0),
('A2', 0),
('A3', 0),
('A4', 1),
('A5', 0),
('A6', 0),
('A7', 0),
('A8', 0),
('A9', 0),
('B1', 0),
('B10', 0),
('B2', 0),
('B3', 0),
('B4', 0),
('B5', 0),
('B6', 0),
('B7', 0),
('B8', 0),
('B9', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `fee`
--
ALTER TABLE `fee`
  ADD PRIMARY KEY (`id_fee`),
  ADD KEY `place` (`place`);

--
-- Indices de la tabla `place`
--
ALTER TABLE `place`
  ADD PRIMARY KEY (`id_place`),
  ADD UNIQUE KEY `id_place` (`id_place`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `fee`
--
ALTER TABLE `fee`
  MODIFY `id_fee` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `fee`
--
ALTER TABLE `fee`
  ADD CONSTRAINT `fee_ibfk_1` FOREIGN KEY (`place`) REFERENCES `place` (`id_place`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
