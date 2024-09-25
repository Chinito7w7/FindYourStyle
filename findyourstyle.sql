-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-09-2024 a las 21:13:25
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
(25, 'BarberCosmica', 'Ituzaingo', 16, 'boca-750x563.png', '07:30:00', '22:00:00');

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
(9, 20, 15, 8, '2024-09-24 16:30:00', '2024-09-24 23:29:03'),
(14, 24, 16, 10, '2024-09-25 02:32:00', '2024-09-25 01:29:02');

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
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(15, 'Lautaro', 'Lujan', 'Buenos Aires', 0, NULL, 'chinito@gmail.com', '$2y$10$XHG5nV3InmjJusYaJ16XaeZzjys4Op1QcUYTnwWutT.gMp75BStBW', 0),
(16, 'Roman', 'Riquelme', 'Ituzaingo', 0, NULL, 'riquelme123@gmail.com', '$2y$10$tQC.N0Cr3/7KC9Z8tKiLJOgn.LURbnqSZG55EVlJK9PRP6.zno/fe', 0),
(17, 'Nico', 'Cosmico', 'Ituzaingo', 0, NULL, 'nico123@gmail.com', '$2y$10$MSz3EKdC/jPTw3XAY9dYaur4r9H92z0ZuZ7lmbLFAUbYGwXlqkbFS', 0);

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `local_images`
--
ALTER TABLE `local_images`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
