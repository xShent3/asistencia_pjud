-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-03-2025 a las 15:18:27
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `asistencia_pjud`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dias_usuario`
--

CREATE TABLE `dias_usuario` (
  `id_dia` int(11) NOT NULL,
  `dia` datetime NOT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `tiempo_excedido` time DEFAULT NULL,
  `tiempo_salida` time DEFAULT NULL,
  `auth` tinyint(1) DEFAULT NULL,
  `mod_por` varchar(100) NOT NULL,
  `teletrabajo` tinyint(1) NOT NULL,
  `RUT` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `dias_usuario`
--

INSERT INTO `dias_usuario` (`id_dia`, `dia`, `hora_inicio`, `hora_fin`, `tiempo_excedido`, `tiempo_salida`, `auth`, `mod_por`, `teletrabajo`, `RUT`) VALUES
(23, '2025-02-06 00:00:00', '09:20:30', '12:29:12', '00:50:30', '00:00:00', 0, '', 0, 17120897),
(27, '2025-02-06 00:00:00', '12:28:16', '17:00:00', '03:28:16', '00:00:00', 0, '', 0, 19233198),
(31, '2025-02-10 00:00:00', '11:28:36', '11:29:34', '02:58:36', '00:00:00', 0, '', 0, 10782237),
(32, '2025-02-11 00:00:00', '10:57:18', '10:58:17', '02:57:18', '00:00:00', 0, '', 0, 16528030),
(33, '2025-02-11 00:00:00', '10:58:57', '15:00:00', '03:58:57', '00:00:00', 0, '', 0, 2819839),
(41, '2025-02-11 00:00:00', '16:50:33', '17:00:00', '08:50:33', '00:00:00', 0, '', 0, 1),
(42, '2025-02-12 00:00:00', '08:05:56', '08:06:49', '01:05:56', '00:00:00', 0, '', 0, 1111111),
(47, '2025-02-12 00:00:00', '08:31:59', '08:32:28', '01:31:59', '00:00:00', 0, '', 0, 2222222),
(54, '2025-02-17 00:00:00', '12:59:41', '15:00:00', '05:59:41', '00:00:00', 0, '', 0, 1111111),
(55, '2025-02-19 00:00:00', '09:05:28', '17:30:00', '00:35:28', '00:00:00', 0, '', 0, 10782237),
(56, '2025-02-19 00:00:00', '09:06:25', '15:00:00', '02:06:25', '00:00:00', 0, '', 0, 2819839),
(62, '2025-02-03 00:00:00', '06:03:36', '15:00:00', '00:00:00', '00:00:00', 0, '', 0, 1111111),
(63, '2025-02-03 00:00:00', '06:03:53', '15:00:00', '00:00:00', '00:00:00', 0, '', 0, 2819839),
(64, '2025-02-03 00:00:00', '06:04:01', '17:30:00', '00:00:00', '00:00:00', 0, '', 0, 10782237),
(65, '2025-02-04 00:00:00', '06:04:57', '15:00:00', '00:00:00', '00:00:00', 0, '', 0, 1111111),
(66, '2025-02-04 00:00:00', '06:05:03', '15:00:00', '00:00:00', '00:00:00', 0, '', 0, 2819839),
(67, '2025-02-04 00:00:00', '06:05:07', '17:30:00', '00:00:00', '00:00:00', 0, '', 0, 10782237),
(68, '2025-02-05 00:00:00', '06:06:04', '15:00:00', '00:00:00', '00:00:00', 0, '', 0, 1111111),
(69, '2025-02-05 00:00:00', '06:06:09', '15:00:00', '00:00:00', '00:00:00', 0, '', 0, 2819839),
(70, '2025-02-05 00:00:00', '06:06:18', '17:30:00', '00:00:00', '00:00:00', 0, '', 0, 10782237),
(78, '2025-02-06 00:00:00', '06:10:19', '15:00:00', '00:00:00', '00:00:00', 0, '', 0, 2819839),
(79, '2025-02-06 00:00:00', '06:10:27', '17:30:00', '00:00:00', '00:00:00', 0, '', 0, 10782237),
(80, '2025-02-06 00:00:00', '06:11:48', '15:00:00', '00:00:00', '00:00:00', 0, '', 0, 1111111),
(90, '2025-02-07 00:00:00', '06:18:14', '15:00:00', '00:00:00', '00:00:00', 0, '', 0, 1111111),
(91, '2025-02-07 00:00:00', '06:18:17', '15:00:00', '00:00:00', '00:00:00', 0, '', 0, 2819839),
(92, '2025-02-07 00:00:00', '06:18:21', '17:30:00', '00:00:00', '00:00:00', 0, '', 0, 10782237),
(93, '2025-02-20 00:00:00', '16:07:29', '15:00:00', '09:07:29', '00:00:00', 0, '', 0, 1111111),
(94, '2025-02-21 00:00:00', '15:34:38', '17:30:00', '07:04:38', '00:00:00', 0, '', 0, 17120897),
(95, '2025-01-06 00:00:00', '08:25:30', '16:35:12', '01:25:30', '00:00:00', 0, '', 0, 2222222),
(96, '2025-01-07 00:00:00', '08:32:45', '16:28:20', '01:32:45', '00:00:00', 0, '', 0, 2222222),
(97, '2025-01-08 00:00:00', '08:40:10', '16:45:33', '01:40:10', '00:00:00', 0, '', 0, 2222222),
(98, '2025-01-15 00:00:00', '08:28:55', '16:32:08', '01:28:55', '00:00:00', 0, '', 0, 2222222),
(99, '2025-02-03 00:00:00', '08:33:20', '16:29:45', '01:33:20', '00:00:00', 0, '', 0, 2222222),
(100, '2025-02-10 00:00:00', '08:27:15', '16:40:30', '01:27:15', '00:00:00', 0, '', 0, 2222222),
(101, '2025-03-03 00:00:00', '08:35:40', '16:28:50', '01:35:40', '00:00:00', 0, '', 0, 2222222),
(102, '2025-03-17 00:00:00', '07:00:00', '16:38:00', '00:00:00', '00:00:00', 0, 'Camilo Ignacio Gomez', 0, 2222222),
(103, '2025-01-09 00:00:00', '07:55:20', '17:05:40', '00:00:00', '00:00:00', 0, '', 0, 16528030),
(104, '2025-01-14 00:00:00', '08:03:45', '16:58:10', '00:03:45', '00:00:00', 0, '', 0, 16528030),
(105, '2025-01-20 00:00:00', '08:10:15', '17:12:30', '00:10:15', '00:00:00', 0, '', 0, 16528030),
(106, '2025-02-05 00:00:00', '07:58:35', '17:02:50', '00:00:00', '00:00:00', 0, '', 0, 16528030),
(107, '2025-02-18 00:00:00', '08:05:55', '16:59:25', '00:05:55', '00:00:00', 0, '', 0, 16528030),
(108, '2025-03-04 00:00:00', '08:02:10', '17:08:45', '00:02:10', '00:00:00', 0, '', 0, 16528030),
(109, '2025-03-12 00:00:00', '07:57:30', '17:04:20', '00:00:00', '00:00:00', 0, '', 0, 16528030),
(110, '2025-03-25 00:00:00', '08:08:40', '17:00:15', '00:00:15', NULL, 0, '', 0, 16528030),
(111, '2025-01-08 00:00:00', '07:53:15', '17:10:30', '00:00:00', '00:00:00', 0, '', 0, 2),
(112, '2025-01-16 00:00:00', '08:04:50', '16:57:25', '00:04:50', '00:00:00', 0, '', 0, 2),
(113, '2025-01-24 00:00:00', '08:01:35', '17:05:45', '00:01:35', '00:00:00', 0, '', 0, 2),
(114, '2025-02-07 00:00:00', '07:58:20', '17:03:10', '00:00:00', '00:00:00', 0, '', 0, 2),
(115, '2025-02-14 00:00:00', '08:00:00', '16:55:00', '00:00:00', '00:00:00', 0, 'Alvaro Diaz', 0, 2),
(116, '2025-02-28 00:00:00', '08:00:00', '17:08:00', '00:00:00', '00:00:00', 0, 'Alvaro Diaz', 0, 2),
(117, '2025-03-10 00:00:00', '07:55:45', '17:04:50', '00:00:00', '00:00:00', 0, '', 0, 2),
(118, '2025-03-21 00:00:00', '08:00:00', '16:59:00', '00:00:00', '00:00:00', 0, 'Alvaro Diaz', 0, 2),
(119, '2025-03-03 00:00:00', '15:01:44', '17:00:00', '07:01:44', '00:00:00', 0, '', 0, 16528030),
(120, '2025-03-04 00:00:00', '09:47:02', '17:37:49', '02:47:02', '00:00:00', 0, '', 0, 2222222),
(121, '2025-03-05 00:00:00', '06:38:50', '16:39:34', '00:00:00', '00:00:00', 0, '', 0, 2222222),
(122, '2025-03-07 00:00:00', '09:39:00', '17:40:00', '01:39:00', '00:00:00', 0, 'Alvaro Diaz', 0, 2),
(123, '2025-03-07 00:00:00', '08:41:56', '16:43:14', '00:00:00', '00:00:00', 0, '', 0, 18887622),
(124, '2025-03-07 00:00:00', '16:42:47', '16:43:03', '08:42:47', '00:00:00', 0, '', 0, 16528030),
(125, '2025-03-13 00:00:00', '09:30:42', '15:00:00', '02:30:42', '00:00:00', 0, '', 0, 2222222),
(126, '2025-03-17 00:00:00', '10:01:51', '10:02:30', '02:01:51', '00:00:00', 1, '', 0, 16528030),
(127, '2025-03-17 00:00:00', '08:00:00', '12:05:00', '00:00:00', '00:00:00', 1, '1', 0, 2),
(128, '2025-03-17 00:00:00', '12:05:42', '17:00:00', '04:05:42', '00:00:00', 0, '', 0, 1),
(129, '2025-03-17 00:00:00', '13:05:47', '13:05:56', '06:05:47', '00:00:00', 1, '', 0, 2819839),
(130, '2025-03-24 00:00:00', '08:06:00', '15:05:00', '00:06:00', '00:00:00', 0, 'Alvaro Diaz', 0, 2),
(131, '2025-03-20 00:00:00', '09:33:47', '15:00:00', '02:33:47', '00:00:00', 0, '', 0, 2222222),
(132, '2025-03-20 00:00:00', '08:18:00', '17:00:00', '00:18:00', '00:00:00', 1, 'Alvaro Diaz', 0, 16528030),
(133, '2025-03-20 00:00:00', '08:00:00', '15:11:00', '00:00:00', '00:00:00', 0, 'Camilo Ignacio Gomez', 0, 19465778),
(134, '2025-03-20 00:00:00', '15:26:50', '17:00:00', '07:26:50', '00:00:00', 1, '', 0, 2),
(135, '2025-03-20 00:00:00', '08:30:00', '17:00:00', '00:30:00', '00:00:00', 1, 'Camilo Ignacio Gomez', 0, 12015755),
(136, '2025-03-21 00:00:00', '00:25:11', '00:29:22', '00:00:00', '00:00:00', 0, '', 1, 19465778);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id_horario` int(11) NOT NULL,
  `nombre_horario` varchar(50) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_termino` time NOT NULL,
  `hora_inicio_u` time DEFAULT NULL,
  `hora_termino_u` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`id_horario`, `nombre_horario`, `hora_inicio`, `hora_termino`, `hora_inicio_u`, `hora_termino_u`) VALUES
(1, 'horario A CAPJ', '08:00:00', '17:00:00', '08:00:00', '16:00:00'),
(2, 'horario B CAPJ', '09:00:00', '18:00:00', '09:00:00', '17:00:00'),
(3, 'horario C CAPJ', '07:00:00', '16:00:00', '07:00:00', '15:00:00'),
(4, 'horario D CAPJ', '07:30:00', '16:30:00', '07:30:00', '15:30:00'),
(5, 'horario E CAPJ', '08:30:00', '17:30:00', '08:30:00', '16:30:00'),
(6, 'horario F PJUD', '07:00:00', '15:00:00', '09:00:00', '14:00:00'),
(7, 'horario G PJUD', '07:30:00', '15:30:00', '09:00:00', '14:00:00'),
(8, 'horario H PJUD', '08:00:00', '16:00:00', '09:00:00', '14:00:00'),
(9, 'horario I PJUD', '08:30:00', '16:30:00', '09:00:00', '14:00:00'),
(10, 'horario J PJUD', '09:00:00', '17:00:00', '09:00:00', '14:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_adm`
--

CREATE TABLE `roles_adm` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles_adm`
--

INSERT INTO `roles_adm` (`id_rol`, `nombre_rol`) VALUES
(1, 'Administrador Total'),
(2, 'Administrador Tribunal'),
(3, 'Subrogante'),
(4, 'Usuario Normal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tribunales`
--

CREATE TABLE `tribunales` (
  `id_tribunal` int(11) NOT NULL,
  `nombre_tribunal` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tribunales`
--

INSERT INTO `tribunales` (`id_tribunal`, `nombre_tribunal`) VALUES
(1, 'Corte de Apelaciones de Valdivia'),
(2, 'Tribunal de Juicio Oral En Lo Penal de Valdivia'),
(3, '1º Juzgado Civil de Valdivia'),
(4, '2º Juzgado Civil de Valdivia'),
(5, '1º Juzgado del Crimen de Valdivia'),
(6, '2º Juzgado del Crimen de Valdivia'),
(7, 'Unidad Apoyo Tecnico Administrativo de Valdivia'),
(8, 'Juzgado de Familia de Valdivia'),
(9, 'Juzgado de Letras del Trabajo de Valdivia'),
(10, 'Juzgado de Garantia de Valdivia'),
(11, 'Juzgado de Letras de Mariquina'),
(12, 'Juzgado de Garantia de Mariquina'),
(13, 'Juzgado de Letras de Los Lagos'),
(14, 'Juzgado de Garantia de Los Lagos'),
(15, 'Juzgado de Letras Y Garantia de Panguipulli'),
(16, 'Juzgado de Letras Y Garantia de La Union'),
(17, 'Juzgado de Letras Y Garantia de Paillaco'),
(18, 'Juzgado de Letras Y Garantia de Rio Bueno'),
(19, 'Tribunal de Juicio Oral En Lo Penal de Osorno'),
(20, '1º Juzgado de Letras de Osorno'),
(21, '2º Juzgado de Letras de Osorno'),
(22, '3º Juzgado de Letras de Osorno'),
(23, '4º Juzgado de Letras de Osorno'),
(24, 'Juzgado de Familia de Osorno'),
(25, 'Juzgado de Letras del Trabajo de Osorno'),
(26, 'Juzgado de Garantia de Osorno'),
(27, 'Juzgado de Letras de Rio Negro'),
(28, 'Juzgado de Garantia de Rio Negro'),
(29, 'Corporación Administrativa del Poder Judicial Valdivia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `RUT` int(11) NOT NULL,
  `DV` varchar(1) NOT NULL,
  `nombre_completo` varchar(80) NOT NULL,
  `correo` varchar(60) NOT NULL,
  `cargo` varchar(30) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL COMMENT 'Campo booleano: 0=FALSE, 1=TRUE',
  `contraseña` varchar(80) NOT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `id_tribunal` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_horario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`RUT`, `DV`, `nombre_completo`, `correo`, `cargo`, `estado`, `contraseña`, `creado_por`, `id_tribunal`, `id_rol`, `id_horario`) VALUES
(1, '', 'Alvaro Perez', 'alavarom@poderjudicial.cl', NULL, 1, '$2y$10$MV7Ic8rnkwB7q2WVtHCQMOVQu1R4TI5/8e5Qj7VkiVvKUseGqG6R6', NULL, 29, 1, 1),
(2, '', 'Claudio Perez', 'claudio@poderjudicial.cl', NULL, 1, '$2y$10$/O72xjtQZBugay7fkD1r5O2WE2MhKODpFD.PP0BEg1qRpN/Pk3qkC', NULL, 29, 1, 1),
(1111111, '4', 'Gustavo Muñoz', 'gmunoz@gmail.com', NULL, 1, '$2y$10$z93Zi8d8MZygo02C4aIwg.BLeYAlNs5BUk2IN08tkHRx7Yn8vVfA.', 1, 10, 2, 6),
(2222222, '8', 'Susana Horias', 'shorias@poderjudicial.cl', NULL, 1, '$2y$10$qd1qUZR2aFV7FGu37kqPOereUbG7dg1cyBGm3pkqfv9smdc7/BT5u', 1111111, 10, 4, 6),
(2819839, '6', 'Adam Escobara', 'adames14@gmail.com', NULL, 1, '$2y$10$ebXEk83enBgaIP0SnQo2PePkHEp.MYnCIS1GsD/NqXYprUua11MMy', 16528030, 10, 4, 6),
(8888888, 'K', 'Nicolas Soto', 'nsoto@poderjudicial.cl', NULL, 1, '$2y$10$m/2efrZBe8YwEwNNVI2KEOuXiFEIMoAZrrpwp9fFV0gq9B8GpS8j2', 18887622, 10, 4, 5),
(10782237, '2', 'Gabriel Olivares', 'gabriel1@gmail.com', NULL, 1, '$2y$10$zZUIirbCuMxhwTtn1ZKjTO7vhqvh.mY7X8rb9YXXyAh8V7c9dZLPS', 16528030, 10, 3, 5),
(12015755, '8', 'Sandra Perez', 'sandra@poderjudicial.cl', NULL, 1, '$2y$10$EWcYbRmgWLdElBE85lRDpu9ig8.TZQznCCGJryysMcjT3OhbrR.MO', 18887622, 29, 3, 1),
(16207200, '5', 'valeria perez', 'valel@poderjudicial.cl', NULL, 1, '$2y$10$39bkIpumbL/0P3itZGSIA.RXRV/VY1UunZXenXhe9YPZ4dQykcKKe', 1, 3, 2, 8),
(16528030, 'K', 'juan perez', 'juan@poderjudicial.cl', NULL, 1, '$2y$10$IliAXzWF912vTgHvBF1Ofugk7/VDEDybPvIxy/HGUKU9otLuL1Ye.', 1, 29, 2, 1),
(17120897, '1', 'Felipex Wild', 'felipeW@gmail.com', NULL, 0, '$2y$10$Ah.WW7gZm8EDGCosqXp7dORh65Y7dSCRlwnNsaUJfmvX9TiW2yxQW', 1, 24, 4, 5),
(18887622, '6', 'Camilo Perez', 'camilo@poderjudicial.cl', NULL, 1, '$2y$10$wI3tMD4Fca1PpHnZ3mymyOk6U6ygGUnDZuEbpGz1mLnm1eqbFi0JO', 1, 29, 2, 9),
(19233198, '6', 'juan santino', 'adames12@gmail.com', NULL, 1, '$2y$10$KA9.jGQaZ9iT45rR0VFPregqyALpLevnMaY0y5rLoRB.IfD0JO5pW', 1, 17, 2, 10),
(19465778, '1', 'Marcelo Perez', 'marcelo@poderjudicial.cl', NULL, 1, '$2y$10$IHHlvuusTMZ.AjR23.CPne5BB5kh0Nm0.nQG4w5E6vxQrNlEmQRMu', 18887622, 29, 4, 1),
(20253812, '6', 'Francisco Lopez', 'francisco@poderjudicial.cl', NULL, 1, '$2y$10$FloXdS.Q1AxuD/aJ2fKCOuzzDDC9fx9D6TGTp2ez/qpRtOq4MYHGS', 16207200, 3, 4, 6),
(22222222, '2', 'Teluenco', 'teluenco@poderjudicial.cl', NULL, 1, '$2y$10$0Bmhs71yu4ZrnSc1IBmdl.OdK8QbxHgEIychl7NW3K5r8IfoIBu3e', 1, 27, 2, 9);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `dias_usuario`
--
ALTER TABLE `dias_usuario`
  ADD PRIMARY KEY (`id_dia`),
  ADD KEY `dias_usuario_ibfk_1` (`RUT`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id_horario`);

--
-- Indices de la tabla `roles_adm`
--
ALTER TABLE `roles_adm`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `tribunales`
--
ALTER TABLE `tribunales`
  ADD PRIMARY KEY (`id_tribunal`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`RUT`),
  ADD KEY `id_horario` (`id_horario`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_tribunal` (`id_tribunal`),
  ADD KEY `creado_por` (`creado_por`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `dias_usuario`
--
ALTER TABLE `dias_usuario`
  MODIFY `id_dia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT de la tabla `roles_adm`
--
ALTER TABLE `roles_adm`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tribunales`
--
ALTER TABLE `tribunales`
  MODIFY `id_tribunal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `dias_usuario`
--
ALTER TABLE `dias_usuario`
  ADD CONSTRAINT `dias_usuario_ibfk_1` FOREIGN KEY (`RUT`) REFERENCES `usuario` (`RUT`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_horario`) REFERENCES `horarios` (`id_horario`),
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles_adm` (`id_rol`),
  ADD CONSTRAINT `usuario_ibfk_3` FOREIGN KEY (`id_tribunal`) REFERENCES `tribunales` (`id_tribunal`),
  ADD CONSTRAINT `usuario_ibfk_4` FOREIGN KEY (`creado_por`) REFERENCES `usuario` (`RUT`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
