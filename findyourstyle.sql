-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-10-2024 a las 19:45:23
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
-- Base de datos: `findyourstyle`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `locals`
--

CREATE TABLE `locals` (
  `id` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `location` text NOT NULL,
  `owner_id` bigint(20) NOT NULL,
  `main_photo` text DEFAULT NULL,
  `opening_time` time NOT NULL,
  `closing_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `locals`
--

INSERT INTO `locals` (`id`, `name`, `location`, `owner_id`, `main_photo`, `opening_time`, `closing_time`) VALUES
(72, 'Estetica Rodri', 'Merlo', 20, '527515f0a30d2cd6994f30f603ea01b9.jpg', '10:00:00', '18:00:00'),
(73, 'BarbeRom', 'La plata', 21, '4544148.jpeg', '15:30:00', '21:00:00'),
(74, 'FedeStylos', 'Rodriguez', 22, 'dreux-france-07-23-2023-260nw-2343682425ñ.png', '15:30:00', '22:30:00'),
(75, 'Esteticas Vir', 'Ituzaingo', 23, '8b469ef03c1de86dc3dc7391c3bb47d7.jpg', '09:00:00', '18:00:00'),
(76, 'EsteBarber', 'Moron', 24, 'front-view-barber-shop-modern-600nw-723232468.png', '10:00:00', '18:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `local_images`
--

CREATE TABLE `local_images` (
  `id` bigint(20) NOT NULL,
  `local_id` bigint(20) NOT NULL,
  `image_url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `local_requests`
--

CREATE TABLE `local_requests` (
  `id` bigint(20) NOT NULL,
  `owner_id` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `location` text NOT NULL,
  `schedule` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `main_photo` varchar(255) DEFAULT NULL,
  `opening_time` time NOT NULL,
  `closing_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `local_requests`
--

INSERT INTO `local_requests` (`id`, `owner_id`, `name`, `location`, `schedule`, `status`, `main_photo`, `opening_time`, `closing_time`) VALUES
(46, 20, 'Estetica Rodri', 'Merlo', '', 'approved', '527515f0a30d2cd6994f30f603ea01b9.jpg', '10:00:00', '18:00:00'),
(47, 21, 'BarbeRom', 'La plata', '', 'approved', '4544148.jpeg', '15:30:00', '21:00:00'),
(48, 22, 'FedeStylos', 'Rodriguez', '', 'approved', 'dreux-france-07-23-2023-260nw-2343682425ñ.png', '15:30:00', '22:30:00'),
(49, 23, 'Esteticas Vir', 'Ituzaingo', '', 'approved', '8b469ef03c1de86dc3dc7391c3bb47d7.jpg', '09:00:00', '18:00:00'),
(50, 24, 'EsteBarber', 'Moron', '', 'approved', 'front-view-barber-shop-modern-600nw-723232468.png', '10:00:00', '18:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservations`
--

CREATE TABLE `reservations` (
  `id` bigint(20) NOT NULL,
  `local_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `service_id` bigint(20) NOT NULL,
  `reservation_time` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservations`
--

INSERT INTO `reservations` (`id`, `local_id`, `user_id`, `service_id`, `reservation_time`, `created_at`) VALUES
(26, 72, 26, 18, '2024-10-16 16:30:00', '2024-10-10 16:43:40'),
(28, 76, 26, 20, '2024-10-16 16:40:00', '2024-10-10 16:45:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) NOT NULL,
  `local_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `services`
--

CREATE TABLE `services` (
  `id` bigint(20) NOT NULL,
  `local_id` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `services`
--

INSERT INTO `services` (`id`, `local_id`, `name`, `price`) VALUES
(18, 72, 'Uñas SemiPermanente', 30000.00),
(19, 72, 'Decoloracion + color', 35000.00),
(20, 76, 'Pelo + Barba', 15000.00),
(21, 76, 'Barba', 13500.00),
(22, 76, 'Pelo', 13500.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `surname` text NOT NULL,
  `location` text NOT NULL,
  `is_local_owner` tinyint(1) DEFAULT 0,
  `phone_number` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `location`, `is_local_owner`, `phone_number`, `email`, `password`, `is_admin`) VALUES
(1, 'ADMINISTRADOR', 'User', 'NULL', 0, NULL, 'admin1@gmail.com', '$2y$10$bYFe4Uwu3jC31ufmKcZSf.BdxV5/qGuRaVk5CpAYtwTQRua75U1uO', 1),
(20, 'Cliente Rodriguez', 'Sato', 'Ituzaingo', 1, NULL, 'rodriguez@gmail.com', '$2y$10$T.86u/.vCQg/FD7XGW/oxOY.Q.H5Vcrkzkqt3Ks5sJTmpF9tkIQiK', 0),
(21, 'Cliente Roman', 'Neto', 'La boca', 1, NULL, 'roman@gmail.com', '$2y$10$3DEecs47pVsDKg388qkiXO5YrTlq77sqPoDtogG/FjH4pNLYF2YT2', 0),
(22, 'Cliente Federico', 'Ruiz', 'Merlo', 1, NULL, 'federico@gmail.com', '$2y$10$tgv7gEpCRNBv1iADyUp/pei/A2xYDwsgz3bCitTeEHKFL0QkpxoPS', 0),
(23, 'Cliente Virginia', 'Quilmes', 'Moron', 1, NULL, 'virginia@gmail.com', '$2y$10$ARuEKj88ppVpTqgMfTsc.eFt1GJCMnVKARolqgr86wof987X0tMMi', 0),
(24, 'Cliente Estefano', 'Blanco', 'Caballito', 1, NULL, 'estefano@gmail.com', '$2y$10$/cwwWjnMALwoYTotIvNZfudAWspi4FPvvh3oCeIYR1TLVgtfMkkga', 0),
(25, 'Cliente Gimena', 'Banzas', 'Moreno', 0, NULL, 'Gimena@gmail.com', '$2y$10$x4J615fC50q1kD/xwOpeEOAaWXSGGrsVH3/265MeAqdi5u.vNBpW6', 0),
(26, 'Cliente chinito', 'Lujan', 'Ituzaingo', 0, NULL, 'chinito@gmail.com', '$2y$10$9ARbudk.wzDeVdUEE/q2POy1DqSmbkcAtS9iz3oMnSM4Z2X4ALSUy', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `locals`
--
ALTER TABLE `locals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indices de la tabla `local_images`
--
ALTER TABLE `local_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `local_id` (`local_id`);

--
-- Indices de la tabla `local_requests`
--
ALTER TABLE `local_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indices de la tabla `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `local_id` (`local_id`,`reservation_time`);

--
-- Indices de la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `local_id` (`local_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `local_id` (`local_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `locals`
--
ALTER TABLE `locals`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de la tabla `local_images`
--
ALTER TABLE `local_images`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `local_requests`
--
ALTER TABLE `local_requests`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `locals`
--
ALTER TABLE `locals`
  ADD CONSTRAINT `locals_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `local_images`
--
ALTER TABLE `local_images`
  ADD CONSTRAINT `local_images_ibfk_1` FOREIGN KEY (`local_id`) REFERENCES `locals` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `local_requests`
--
ALTER TABLE `local_requests`
  ADD CONSTRAINT `local_requests_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`local_id`) REFERENCES `locals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`local_id`) REFERENCES `locals` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
