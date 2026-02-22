-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-02-2026 a las 17:25:58
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
-- Base de datos: `gestionhoteles`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitacion`
--

CREATE TABLE `habitacion` (
  `id_habitacion` int(11) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `numero_puerta` varchar(10) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `precio_noche` decimal(10,2) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `habitacion`
--

INSERT INTO `habitacion` (`id_habitacion`, `id_hotel`, `numero_puerta`, `tipo`, `precio_noche`, `capacidad`, `descripcion`) VALUES
(22, 22, '101', 'Individual', 79.00, 1, 'Individual práctica: cama cómoda, escritorio pequeño y buena luz.'),
(23, 22, '102', 'Individual', 82.00, 1, 'Ideal para trabajo: silenciosa y con mesa amplia.'),
(24, 22, '103', 'Individual', 85.00, 1, 'Compacta pero muy cómoda para una noche o dos.'),
(25, 22, '201', 'Doble', 109.00, 2, 'Doble luminosa, buena ducha y espacio para maleta grande.'),
(26, 22, '202', 'Doble', 112.00, 2, 'Doble tranquila orientada a patio interior.'),
(27, 22, '203', 'Doble', 116.00, 2, 'Doble con zona de estar pequeña para descansar.'),
(28, 22, '204', 'Doble', 119.00, 2, 'Doble con colchón firme y aislamiento correcto.'),
(29, 22, '205', 'Doble', 123.00, 2, 'Doble cómoda, perfecta para fin de semana en Madrid.'),
(30, 22, '301', 'Familiar', 149.00, 3, 'Familiar (3 pax): espacio extra y armario amplio.'),
(31, 22, '302', 'Familiar', 155.00, 3, 'Familiar con rincón de estar y buena ventilación.'),
(32, 22, '401', 'Suite', 189.00, 2, 'Suite con salón pequeño: ideal para celebrar o descansar con calma.'),
(33, 22, 'A1', 'Apartamento', 169.00, 4, 'Apartamento con cocina equipada y zona de comedor. Muy cómodo para estancias largas.'),
(34, 23, '101', 'Individual', 72.00, 1, 'Individual tranquila, ideal para escapada corta.'),
(35, 23, '102', 'Individual', 75.00, 1, 'Individual con escritorio y buena luz natural.'),
(36, 23, '103', 'Individual', 78.00, 1, 'Individual cómoda, perfecta para descansar tras caminar todo el día.'),
(37, 23, '201', 'Doble', 99.00, 2, 'Doble amplia, aire potente y cama muy cómoda.'),
(38, 23, '202', 'Doble', 102.00, 2, 'Doble orientada a zona interior, menos ruido.'),
(39, 23, '203', 'Doble', 105.00, 2, 'Doble con espacio extra para equipaje.'),
(40, 23, '204', 'Doble', 109.00, 2, 'Doble ideal para parejas, ambiente acogedor.'),
(41, 23, '205', 'Doble', 112.00, 2, 'Doble luminosa, buena ducha y toallas grandes.'),
(42, 23, '301', 'Familiar', 139.00, 3, 'Familiar (3 pax) con armario grande y buena distribución.'),
(43, 23, '302', 'Familiar', 145.00, 3, 'Familiar cómoda: perfecta para 2 adultos + 1 niño.'),
(44, 23, '401', 'Suite', 179.00, 2, 'Suite con zona de estar y sensación de “hotel de verdad”.'),
(45, 23, 'A1', 'Apartamento', 159.00, 4, 'Apartamento con cocina y mesa grande. Muy práctico para varios días.'),
(46, 24, '101', 'Individual', 68.00, 1, 'Individual acogedora, perfecta para viajero solo.'),
(47, 24, '102', 'Individual', 70.00, 1, 'Individual con buena temperatura y colchón cómodo.'),
(48, 24, '103', 'Individual', 72.00, 1, 'Individual tranquila, ideal para descansar tras el día.'),
(49, 24, '201', 'Doble', 92.00, 2, 'Doble con ambiente cálido y buena ducha.'),
(50, 24, '202', 'Doble', 95.00, 2, 'Doble interior: cero líos de ruido por la noche.'),
(51, 24, '203', 'Doble', 98.00, 2, 'Doble con espacio extra para maletas.'),
(52, 24, '204', 'Doble', 101.00, 2, 'Doble muy cómoda para escapada romántica.'),
(53, 24, '205', 'Doble', 105.00, 2, 'Doble con buen aislamiento y descanso top.'),
(54, 24, '301', 'Familiar', 129.00, 3, 'Familiar (3 pax) con distribución práctica.'),
(55, 24, '302', 'Familiar', 135.00, 3, 'Familiar pensada para estancias de fin de semana.'),
(56, 24, '401', 'Suite', 165.00, 2, 'Suite con rincón de estar: se nota el extra de confort.'),
(57, 24, 'A1', 'Apartamento', 149.00, 4, 'Apartamento con cocina y zona de estar. Ideal si vienes varios días.'),
(58, 25, '101', 'Individual', 85.00, 1, 'Individual cómoda, ideal si vienes por trabajo.'),
(59, 25, '102', 'Individual', 88.00, 1, 'Individual luminosa, buena mesa para portátil.'),
(60, 25, '103', 'Individual', 92.00, 1, 'Individual tranquila, perfecta para dormir bien.'),
(61, 25, '201', 'Doble', 129.00, 2, 'Doble amplia, ducha potente y cama firme.'),
(62, 25, '202', 'Doble', 133.00, 2, 'Doble interior: menos ruido, más descanso.'),
(63, 25, '203', 'Doble', 138.00, 2, 'Doble con espacio extra para equipaje.'),
(64, 25, '204', 'Doble', 142.00, 2, 'Doble ideal para escapada corta en pareja.'),
(65, 25, '205', 'Doble', 147.00, 2, 'Doble cómoda, perfecta para 2-3 noches.'),
(66, 25, '301', 'Familiar', 179.00, 3, 'Familiar (3 pax) con buena distribución.'),
(67, 25, '302', 'Familiar', 185.00, 3, 'Familiar con armario amplio y zona de estar pequeña.'),
(68, 25, '401', 'Suite', 229.00, 2, 'Suite con salón pequeño: se nota el extra.'),
(69, 25, 'A1', 'Apartamento', 209.00, 4, 'Apartamento con cocina y mesa grande, muy útil para estancias largas.'),
(70, 26, '101', 'Individual', 74.00, 1, 'Individual práctica y silenciosa.'),
(71, 26, '102', 'Individual', 76.00, 1, 'Individual con buena luz y escritorio.'),
(72, 26, '103', 'Individual', 79.00, 1, 'Individual cómoda para viaje exprés.'),
(73, 26, '201', 'Doble', 105.00, 2, 'Doble funcional, todo correcto y muy limpia.'),
(74, 26, '202', 'Doble', 108.00, 2, 'Doble interior: descanso más tranquilo.'),
(75, 26, '203', 'Doble', 112.00, 2, 'Doble con espacio para maleta grande.'),
(76, 26, '204', 'Doble', 116.00, 2, 'Doble agradable para fin de semana.'),
(77, 26, '205', 'Doble', 119.00, 2, 'Doble con cama firme y buena ducha.'),
(78, 26, '301', 'Familiar', 149.00, 3, 'Familiar (3 pax) cómoda y bien distribuida.'),
(79, 26, '302', 'Familiar', 155.00, 3, 'Familiar ideal para 2 adultos + 1 niño.'),
(80, 26, '401', 'Suite', 189.00, 2, 'Suite con salón pequeño para descansar con calma.'),
(81, 26, 'A1', 'Apartamento', 169.00, 4, 'Apartamento con cocina equipada: muy útil para varios días.'),
(82, 27, '101', 'Individual', 70.00, 1, 'Individual cómoda, perfecta para descanso tras playa.'),
(83, 27, '102', 'Individual', 73.00, 1, 'Individual con buena ventilación y luz.'),
(84, 27, '103', 'Individual', 76.00, 1, 'Individual silenciosa y práctica.'),
(85, 27, '201', 'Doble', 99.00, 2, 'Doble con ambiente fresco y cama cómoda.'),
(86, 27, '202', 'Doble', 103.00, 2, 'Doble interior: menos ruido por la noche.'),
(87, 27, '203', 'Doble', 107.00, 2, 'Doble con espacio extra para equipaje.'),
(88, 27, '204', 'Doble', 112.00, 2, 'Doble ideal para escapada de fin de semana.'),
(89, 27, '205', 'Doble', 116.00, 2, 'Doble con ducha amplia y buenas toallas.'),
(90, 27, '301', 'Familiar', 139.00, 3, 'Familiar (3 pax) cómoda, perfecta para vacaciones cortas.'),
(91, 27, '302', 'Familiar', 145.00, 3, 'Familiar con armario grande y buena distribución.'),
(92, 27, '401', 'Suite', 175.00, 2, 'Suite con zona de estar, ideal para desconectar.'),
(93, 27, 'A1', 'Apartamento', 159.00, 4, 'Apartamento con cocina y mesa de comedor. Muy práctico para familia.'),
(94, 28, '101', 'Individual', 69.00, 1, 'Individual fresquita, perfecta en días de calor.'),
(95, 28, '102', 'Individual', 72.00, 1, 'Individual con buena cama y descanso correcto.'),
(96, 28, '103', 'Individual', 75.00, 1, 'Individual cómoda, ideal para una noche o dos.'),
(97, 28, '201', 'Doble', 98.00, 2, 'Doble con ambiente cálido y ducha cómoda.'),
(98, 28, '202', 'Doble', 102.00, 2, 'Doble interior: más silenciosa por la noche.'),
(99, 28, '203', 'Doble', 106.00, 2, 'Doble con espacio extra y buen armario.'),
(100, 28, '204', 'Doble', 110.00, 2, 'Doble ideal para escapada romántica.'),
(101, 28, '205', 'Doble', 114.00, 2, 'Doble muy cómoda, buena climatización.'),
(102, 28, '301', 'Familiar', 135.00, 3, 'Familiar (3 pax) con distribución práctica.'),
(103, 28, '302', 'Familiar', 142.00, 3, 'Familiar ideal para venir con niño.'),
(104, 28, '401', 'Suite', 169.00, 2, 'Suite con zona de estar y sensación boutique.'),
(105, 28, 'A1', 'Apartamento', 149.00, 4, 'Apartamento con cocina básica y comedor pequeño. Muy útil para varios días.'),
(106, 29, '101', 'Individual', 64.00, 1, 'Individual simple y cómoda.'),
(107, 29, '102', 'Individual', 66.00, 1, 'Individual con escritorio, ideal para trabajo.'),
(108, 29, '103', 'Individual', 68.00, 1, 'Individual tranquila, descanso asegurado.'),
(109, 29, '201', 'Doble', 89.00, 2, 'Doble funcional, todo correcto y limpio.'),
(110, 29, '202', 'Doble', 92.00, 2, 'Doble interior: menos ruido por la noche.'),
(111, 29, '203', 'Doble', 95.00, 2, 'Doble con armario amplio y buena ducha.'),
(112, 29, '204', 'Doble', 99.00, 2, 'Doble cómoda, perfecta para fin de semana.'),
(113, 29, '205', 'Doble', 102.00, 2, 'Doble con cama firme y aire potente.'),
(114, 29, '301', 'Familiar', 125.00, 3, 'Familiar (3 pax) práctica y bien distribuida.'),
(115, 29, '302', 'Familiar', 132.00, 3, 'Familiar ideal para 2 adultos + 1 niño.'),
(116, 29, '401', 'Suite', 159.00, 2, 'Suite con zona de estar para descansar mejor.'),
(117, 29, 'A1', 'Apartamento', 139.00, 4, 'Apartamento con cocina equipada, perfecto para varios días.'),
(118, 30, '101', 'Individual', 78.00, 1, 'Individual luminosa, perfecta para descansar.'),
(119, 30, '102', 'Individual', 81.00, 1, 'Individual con escritorio y buena luz.'),
(120, 30, '103', 'Individual', 84.00, 1, 'Individual tranquila, ideal para viaje corto.'),
(121, 30, '201', 'Doble', 114.00, 2, 'Doble con ambiente moderno y ducha amplia.'),
(122, 30, '202', 'Doble', 118.00, 2, 'Doble interior: más silenciosa por la noche.'),
(123, 30, '203', 'Doble', 122.00, 2, 'Doble cómoda, cama firme y buen armario.'),
(124, 30, '204', 'Doble', 126.00, 2, 'Doble ideal para escapada en pareja.'),
(125, 30, '205', 'Doble', 129.00, 2, 'Doble con zona extra para equipaje.'),
(126, 30, '301', 'Familiar', 159.00, 3, 'Familiar (3 pax) perfecta para vacaciones cortas.'),
(127, 30, '302', 'Familiar', 165.00, 3, 'Familiar con buena distribución y espacio extra.'),
(128, 30, '401', 'Suite', 205.00, 2, 'Suite con salón pequeño: para ir con calma.'),
(129, 30, 'A1', 'Apartamento', 185.00, 4, 'Apartamento con cocina y comedor. Muy práctico si vienes varios días.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hotel`
--

CREATE TABLE `hotel` (
  `id_hotel` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hotel`
--

INSERT INTO `hotel` (`id_hotel`, `id_usuario`, `nombre`, `direccion`, `ciudad`, `descripcion`, `imagen`) VALUES
(22, 4, 'Hotel Atocha & Brunch', 'C/ Atocha 118', 'Madrid', 'Hotel urbano y cómodo, pensado para moverte andando y descansar de verdad. Buen desayuno, habitaciones silenciosas y check-in rápido.', '698f4acf36566_Hotel Atocha & Brunch.jpg'),
(23, 4, 'Turia Riverside Hotel', 'Av. del Puerto 54', 'Valencia', 'A un paso del río y bien conectado con el centro. Hotel tranquilo, buena climatización y personal muy resolutivo.', '698f4b11ded8f_Turia Riverside Hotel.jpg'),
(24, 4, 'Carmen de la Alhambra Stay', 'C/ Calderería Nueva 9', 'Granada', 'En pleno ambiente granadino, pero con habitaciones calmadas. Ideal para tapear, callejear y volver a dormir sin ruido.', '698f4b8fd8be9_Carmen de la Alhambra Stay.jpg'),
(25, 6, 'Marina & Skyline', 'C/ Marina 88', 'Barcelona', 'Moderno y funcional, perfecto para combinar playa y ciudad. Habitaciones luminosas, buena ducha y recepción rápida.', '698f4bd309bf9_Marina & Skyline.jpg'),
(26, 6, 'Bilbao Gran Vía Urban', 'C/ Hurtado de Amézaga 12', 'Bilbao', 'Hotel urbano sin complicaciones: limpio, práctico y bien situado. Ideal para moverte andando y comer bien cerca.', '698f4c1e91080_Bilbao Gran Vía Urban.jpg'),
(27, 6, 'Málaga Costa Relax', 'Paseo Marítimo 21', 'Málaga', 'Cerca del paseo y con ambiente tranquilo. Buena climatización, camas cómodas y ese punto de hotel de vacaciones.', '698f4cdd58bd6_Málaga Costa Relax.jpg'),
(28, 7, 'Azahar Sevilla Boutique', 'C/ San Vicente 18', 'Sevilla', 'Boutique con patio interior y ambiente muy agradable. Perfecto para escapada: sales, comes bien y vuelves a dormir con calma.', '698f4de776980_Azahar Sevilla Boutique.jpg'),
(29, 7, 'Ebro Zaragoza Center', 'C/ Alfonso I 30', 'Zaragoza', 'Céntrico y práctico. Buen punto para moverte andando, y habitaciones pensadas para descansar sin dramas.', '698f4e2e5f834_Ebro Zaragoza Center.jpg'),
(30, 7, 'Palma Paseo Modern', 'Av. Jaume III 11', 'Palma', 'Moderno, cómodo y bien situado. Buen descanso, habitaciones bien mantenidas y un punto “vacaciones” sin excesos.', '698f4e70d1dfb_Palma Paseo Modern.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hotel_servicio`
--

CREATE TABLE `hotel_servicio` (
  `id_hotel` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hotel_servicio`
--

INSERT INTO `hotel_servicio` (`id_hotel`, `id_servicio`) VALUES
(22, 1),
(22, 3),
(22, 4),
(22, 5),
(22, 6),
(23, 1),
(23, 2),
(23, 3),
(23, 4),
(23, 5),
(24, 1),
(24, 3),
(24, 5),
(24, 7),
(25, 1),
(25, 3),
(25, 4),
(25, 6),
(26, 1),
(26, 3),
(26, 4),
(26, 5),
(27, 1),
(27, 2),
(27, 3),
(27, 5),
(27, 7),
(28, 1),
(28, 3),
(28, 5),
(28, 7),
(29, 1),
(29, 3),
(29, 4),
(29, 5),
(30, 1),
(30, 2),
(30, 3),
(30, 5),
(30, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resena`
--

CREATE TABLE `resena` (
  `id_resena` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `puntuacion` int(11) DEFAULT NULL CHECK (`puntuacion` between 1 and 5),
  `comentario` text DEFAULT NULL,
  `fecha` date DEFAULT curdate(),
  `respuesta_owner` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_habitacion` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `precio_total` decimal(10,2) DEFAULT NULL,
  `estado` enum('pendiente','confirmada','cancelada') DEFAULT 'confirmada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `id_servicio` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`id_servicio`, `nombre`) VALUES
(1, 'Wifi'),
(2, 'Piscina'),
(3, 'Aire Acondicionado'),
(4, 'Parking'),
(5, 'Desayuno Incluido'),
(6, 'Gimnasio'),
(7, 'Mascotas Permitidas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('cliente','admin','dueño') DEFAULT 'cliente',
  `estado` varchar(20) DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `email`, `password`, `rol`, `estado`) VALUES
(1, 'Administrador', 'admin@admin.com', '$2y$10$9K909GGAiW2Cf3A7WT.oRujSa7pkBr0Tr5exVDHNdTCYMqSISoX.m', 'admin', 'activo'),
(4, 'dario', 'dario@dario.com', '$2y$10$5F/r1P9pj.2PMiBe.KHPA.GJzm2eAYPQcDLgg3jg0YJwjnxvQ/Kbi', 'dueño', 'activo'),
(5, 'luigi', 'luigi@luigi.com', '$2y$10$3EV1RZr36/F07LahBQOxoudfftk2SDHyxDZedKecJrTuOjhBXVBHW', 'cliente', 'activo'),
(6, 'Maksym', 'Maksym@maksym.com', '$2y$10$7qg6WJjz.Kv3FZFPRFod8.a4NPEoGGhKAnH4Xd0eHV3VRmhAF8XIy', 'dueño', 'activo'),
(7, 'Javier', 'javier@javier', '$2y$10$ev9ePfcT0VcLtnF0Go/CSeCn.iX8AFJztaX9732OUAf.YlfYYfGsm', 'dueño', 'activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `habitacion`
--
ALTER TABLE `habitacion`
  ADD PRIMARY KEY (`id_habitacion`),
  ADD KEY `id_hotel` (`id_hotel`);

--
-- Indices de la tabla `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`id_hotel`),
  ADD KEY `fk_hotel_dueno` (`id_usuario`);

--
-- Indices de la tabla `hotel_servicio`
--
ALTER TABLE `hotel_servicio`
  ADD PRIMARY KEY (`id_hotel`,`id_servicio`),
  ADD KEY `id_servicio` (`id_servicio`);

--
-- Indices de la tabla `resena`
--
ALTER TABLE `resena`
  ADD PRIMARY KEY (`id_resena`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_hotel` (`id_hotel`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_habitacion` (`id_habitacion`);

--
-- Indices de la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`id_servicio`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `habitacion`
--
ALTER TABLE `habitacion`
  MODIFY `id_habitacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT de la tabla `hotel`
--
ALTER TABLE `hotel`
  MODIFY `id_hotel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `resena`
--
ALTER TABLE `resena`
  MODIFY `id_resena` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `habitacion`
--
ALTER TABLE `habitacion`
  ADD CONSTRAINT `habitacion_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hotel` (`id_hotel`) ON DELETE CASCADE;

--
-- Filtros para la tabla `hotel`
--
ALTER TABLE `hotel`
  ADD CONSTRAINT `fk_hotel_dueno` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `hotel_servicio`
--
ALTER TABLE `hotel_servicio`
  ADD CONSTRAINT `hotel_servicio_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hotel` (`id_hotel`),
  ADD CONSTRAINT `hotel_servicio_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`);

--
-- Filtros para la tabla `resena`
--
ALTER TABLE `resena`
  ADD CONSTRAINT `resena_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `resena_ibfk_2` FOREIGN KEY (`id_hotel`) REFERENCES `hotel` (`id_hotel`);

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `reserva_ibfk_2` FOREIGN KEY (`id_habitacion`) REFERENCES `habitacion` (`id_habitacion`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
