-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-10-2024 a las 05:21:29
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `futbol`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE `equipo` (
  `id_e` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `cantidad` int(20) DEFAULT NULL,
  `logotipo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipo`
--

INSERT INTO `equipo` (`id_e`, `nombre`, `cantidad`, `logotipo`) VALUES
(1, 'America', 11, 'img_profile/img_1728089243.png'),
(2, 'Cuz Azul', 11, 'img_profile/img_1728089351.png'),
(3, 'Chivas', 11, 'img_profile/img_1728089834.png'),
(4, 'Pumas', 11, 'img_profile/img_1728089886.png'),
(5, 'Monterrey', 11, 'img_profile/img_1728090073.png'),
(6, 'Cecy', 14, 'img_profile/img_1728090175.png'),
(8, 'HAHAHA', 14, 'img_profile/img_1728090553.png'),
(9, 'USA', 14, 'img_profile/img_1728090577.png'),
(10, 'Morat', 14, 'img_profile/img_1728090589.png'),
(15, 'jujujuj', 11, 'img_profile/img_1728146025.webp'),
(17, 'Ya es mañana', 14, 'img_profile/img_1728347523.webp');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugador`
--

CREATE TABLE `jugador` (
  `id_j` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `edad` int(99) DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `id_e` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `jugador`
--

INSERT INTO `jugador` (`id_j`, `nombre`, `edad`, `pais`, `foto`, `id_e`) VALUES
(1, 'Carlos', 16, 'Mexico', '', 10),
(2, 'Juan', 17, 'Chile', '', 1),
(3, 'Antonio', 20, 'Mexico', 'jugador/D_NQ_NP_744995-MLM75311255238_032024-O.webp', 3),
(4, 'Jesus', 17, 'Mexico', 'jugador/icp.jpg', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD PRIMARY KEY (`id_e`);

--
-- Indices de la tabla `jugador`
--
ALTER TABLE `jugador`
  ADD PRIMARY KEY (`id_j`),
  ADD KEY `id_e` (`id_e`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `equipo`
--
ALTER TABLE `equipo`
  MODIFY `id_e` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `jugador`
--
ALTER TABLE `jugador`
  MODIFY `id_j` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `jugador`
--
ALTER TABLE `jugador`
  ADD CONSTRAINT `jugador_ibfk_1` FOREIGN KEY (`id_e`) REFERENCES `equipo` (`id_e`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
