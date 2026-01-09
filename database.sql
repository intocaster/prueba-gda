-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         9.1.0 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para mydb
CREATE DATABASE IF NOT EXISTS `mydb` /*!40100 DEFAULT CHARACTER SET utf8mb3 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `mydb`;

-- Volcando estructura para tabla mydb.communes
CREATE TABLE IF NOT EXISTS `communes` (
  `id_com` int NOT NULL AUTO_INCREMENT,
  `id_reg` int NOT NULL,
  `description` varchar(90) NOT NULL,
  `status` enum('A','I','trash') NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id_com`,`id_reg`),
  KEY `fk_region` (`id_reg`),
  CONSTRAINT `fk_region` FOREIGN KEY (`id_reg`) REFERENCES `regions` (`id_reg`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla mydb.communes: ~1 rows (aproximadamente)
INSERT INTO `communes` (`id_com`, `id_reg`, `description`, `status`) VALUES
	(1, 1, 'Caracas', 'A');

-- Volcando estructura para tabla mydb.customers
CREATE TABLE IF NOT EXISTS `customers` (
  `dni` varchar(45) NOT NULL,
  `id_reg` int NOT NULL,
  `id_com` int NOT NULL,
  `email` varchar(120) NOT NULL,
  `name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `date_reg` datetime NOT NULL,
  `status` enum('A','I','trash') NOT NULL DEFAULT 'A',
  PRIMARY KEY (`dni`,`id_reg`,`id_com`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla mydb.customers: ~0 rows (aproximadamente)
INSERT INTO `customers` (`dni`, `id_reg`, `id_com`, `email`, `name`, `last_name`, `address`, `date_reg`, `status`) VALUES
	('12345678-9', 1, 1, 'juan.perez@example.com', 'Juan', 'Pérez', 'Calle Falsa 123', '2026-01-09 21:44:44', 'trash'),
	('98765432-1', 1, 1, 'm.lopez@email.com', 'Maria', 'Lopez', NULL, '2026-01-09 21:55:47', 'trash');

-- Volcando estructura para tabla mydb.logs
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `type` enum('INPUT','OUTPUT') NOT NULL,
  `data` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla mydb.logs: ~0 rows (aproximadamente)
INSERT INTO `logs` (`id`, `ip_address`, `type`, `data`, `created_at`) VALUES
	(1, '::1', 'INPUT', '[]', '2026-01-09 21:41:01'),
	(2, '::1', 'OUTPUT', '{"success":true,"data":[]}', '2026-01-09 21:41:01'),
	(3, '::1', 'INPUT', '[]', '2026-01-09 21:41:22'),
	(4, '::1', 'OUTPUT', '{"success":true,"data":[]}', '2026-01-09 21:41:22'),
	(5, '::1', 'INPUT', '{"dni":"12345678-9","name":"Juan","last_name":"P\\u00e9rez","email":"juan.perez@example.com","address":"Calle Falsa 123","id_reg":1,"id_com":1}', '2026-01-09 21:44:44'),
	(6, '::1', 'OUTPUT', '{"success":true,"message":"Registrado"}', '2026-01-09 21:44:44'),
	(7, '::1', 'INPUT', '[]', '2026-01-09 21:44:57'),
	(8, '::1', 'OUTPUT', '{"success":true,"data":[{"name":"Juan","last_name":"P\\u00e9rez","dni":"12345678-9","email":"juan.perez@example.com","region":"Regi\\u00f3n Capital","comuna":"Caracas"}]}', '2026-01-09 21:44:57'),
	(9, '::1', 'INPUT', '{"search":"12345678-9"}', '2026-01-09 21:46:38'),
	(10, '::1', 'OUTPUT', '{"success":true,"data":{"name":"Juan","last_name":"P\\u00e9rez","dni":"12345678-9","email":"juan.perez@example.com","region":"Regi\\u00f3n Capital","comuna":"Caracas"}}', '2026-01-09 21:46:38'),
	(11, '::1', 'INPUT', '{"search":"123456789"}', '2026-01-09 21:46:41'),
	(12, '::1', 'OUTPUT', '{"success":false,"message":"No encontrado"}', '2026-01-09 21:46:41'),
	(13, '::1', 'INPUT', '{"search":"12345678-9"}', '2026-01-09 21:46:44'),
	(14, '::1', 'OUTPUT', '{"success":true,"data":{"name":"Juan","last_name":"P\\u00e9rez","dni":"12345678-9","email":"juan.perez@example.com","region":"Regi\\u00f3n Capital","comuna":"Caracas"}}', '2026-01-09 21:46:44'),
	(15, '::1', 'INPUT', '{"search":"12345678-9"}', '2026-01-09 21:47:17'),
	(16, '::1', 'OUTPUT', '{"success":true,"data":{"name":"Juan","last_name":"P\\u00e9rez","dni":"12345678-9","email":"juan.perez@example.com","region":"Regi\\u00f3n Capital","comuna":"Caracas"}}', '2026-01-09 21:47:17'),
	(17, '::1', 'INPUT', '{"search":"12345678-9"}', '2026-01-09 21:47:21'),
	(18, '::1', 'OUTPUT', '{"success":true,"data":{"name":"Juan","last_name":"P\\u00e9rez","dni":"12345678-9","email":"juan.perez@example.com","region":"Regi\\u00f3n Capital","comuna":"Caracas"}}', '2026-01-09 21:47:21'),
	(19, '::1', 'INPUT', '{"search":"12345678-9"}', '2026-01-09 21:47:31'),
	(20, '::1', 'OUTPUT', '{"success":true,"data":{"name":"Juan","last_name":"P\\u00e9rez","dni":"12345678-9","email":"juan.perez@example.com","region":"Regi\\u00f3n Capital","comuna":"Caracas"}}', '2026-01-09 21:47:31'),
	(21, '::1', 'INPUT', '[]', '2026-01-09 21:49:33'),
	(22, '::1', 'OUTPUT', '{"success":true,"message":"Cliente enviado a la papelera correctamente"}', '2026-01-09 21:49:33'),
	(23, '::1', 'INPUT', '{"search":"12345678-9"}', '2026-01-09 21:49:51'),
	(24, '::1', 'OUTPUT', '{"success":false,"message":"No encontrado"}', '2026-01-09 21:49:51'),
	(25, '::1', 'INPUT', '{"dni":"12345678-9","name":"Juan","last_name":"P\\u00e9rez","email":"juan.perez@example.com","address":"Calle Falsa 123","id_reg":1,"id_com":1}', '2026-01-09 21:50:12'),
	(26, '::1', 'OUTPUT', '{"success":false,"message":"El DNI o Email ya se encuentra registrado en el sistema."}', '2026-01-09 21:50:12'),
	(27, '::1', 'INPUT', '{"dni":"98765432-1","name":"Maria","last_name":"Lopez","email":"m.lopez@email.com","id_reg":1,"id_com":2}', '2026-01-09 21:54:57'),
	(28, '::1', 'OUTPUT', '{"success":false,"message":"La ubicaci\\u00f3n es inv\\u00e1lida: La comuna no pertenece a la regi\\u00f3n seleccionada."}', '2026-01-09 21:54:57'),
	(29, '::1', 'INPUT', '[]', '2026-01-09 21:55:23'),
	(30, '::1', 'OUTPUT', '{"success":false,"message":"Error de validaci\\u00f3n: El campo dni es requerido"}', '2026-01-09 21:55:23'),
	(31, '::1', 'INPUT', '[]', '2026-01-09 21:55:34'),
	(32, '::1', 'OUTPUT', '{"success":false,"message":"Error de validaci\\u00f3n: El campo dni es requerido"}', '2026-01-09 21:55:34'),
	(33, '::1', 'INPUT', '{"dni":"98765432-1","name":"Maria","last_name":"Lopez","email":"m.lopez@email.com","id_reg":1,"id_com":1}', '2026-01-09 21:55:47'),
	(34, '::1', 'OUTPUT', '{"success":true,"message":"Registrado"}', '2026-01-09 21:55:47'),
	(35, '::1', 'INPUT', '{"search":"98765432-1"}', '2026-01-09 21:55:56'),
	(36, '::1', 'OUTPUT', '{"success":true,"data":{"name":"Maria","last_name":"Lopez","dni":"98765432-1","email":"m.lopez@email.com","region":"Regi\\u00f3n Capital","comuna":"Caracas"}}', '2026-01-09 21:55:56'),
	(37, '::1', 'INPUT', '[]', '2026-01-09 21:56:13'),
	(38, '::1', 'OUTPUT', '{"success":true,"message":"Cliente enviado a la papelera correctamente"}', '2026-01-09 21:56:13'),
	(39, '::1', 'INPUT', '{"dni":"98765432-1","name":"Maria","last_name":"Lopez","email":"m.lopez@email.com","id_reg":1,"id_com":1}', '2026-01-09 21:59:53'),
	(40, '::1', 'OUTPUT', '{"success":false,"message":"El DNI o Email ya se encuentra registrado en el sistema."}', '2026-01-09 21:59:53'),
	(41, '::1', 'INPUT', '{"dni":"98765432-1","name":"Maria","last_name":"Lopez","email":"","id_reg":1,"id_com":1}', '2026-01-09 22:00:12'),
	(42, '::1', 'OUTPUT', '{"success":false,"message":"Error de validaci\\u00f3n: El campo email es requerido"}', '2026-01-09 22:00:12'),
	(43, '::1', 'INPUT', '[]', '2026-01-09 22:16:18'),
	(44, '::1', 'OUTPUT', '{"success":false,"message":"El cliente no existe o ya fue eliminado"}', '2026-01-09 22:16:18');

-- Volcando estructura para tabla mydb.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(120) NOT NULL,
  `token` varchar(60) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla mydb.personal_access_tokens: ~0 rows (aproximadamente)
INSERT INTO `personal_access_tokens` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
	(1, 'test@candidato.com', '5d6f44dad580a967ff6e5e8598dad16f8c536cbf', '2026-01-09 20:09:16', '2026-01-09 19:09:16'),
	(2, 'test@candidato.com', '8fdc6be4d1cf6aa2ce2a2b52c1ad883c049213ce', '2026-01-09 20:52:37', '2026-01-09 19:52:37'),
	(3, 'tu@email.com', '13992b725cbb873397eda5151c16a247b2f36254', '2026-01-09 22:40:27', '2026-01-09 21:40:27'),
	(4, 'tu@email.com', '736de67c20553670593061700d9e557f69d0cbf3', '2026-01-09 22:54:25', '2026-01-09 21:54:25');

-- Volcando estructura para tabla mydb.regions
CREATE TABLE IF NOT EXISTS `regions` (
  `id_reg` int NOT NULL AUTO_INCREMENT,
  `description` varchar(90) NOT NULL,
  `status` enum('A','I','trash') NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id_reg`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla mydb.regions: ~1 rows (aproximadamente)
INSERT INTO `regions` (`id_reg`, `description`, `status`) VALUES
	(1, 'Región Capital', 'A');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
