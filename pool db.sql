-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.7.19 - MySQL Community Server (GPL)
-- Операционная система:         Win64
-- HeidiSQL Версия:              9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Дамп структуры базы данных pool
CREATE DATABASE IF NOT EXISTS `pool` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `pool`;

-- Дамп структуры для таблица pool.blob_my_livedata
CREATE TABLE IF NOT EXISTS `blob_my_livedata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exercise` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `start_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_blob_my_livedata_exercise_my` (`exercise`),
  CONSTRAINT `FK_blob_my_livedata_exercise_my` FOREIGN KEY (`exercise`) REFERENCES `exercise_my` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы pool.blob_my_livedata: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `blob_my_livedata` DISABLE KEYS */;
/*!40000 ALTER TABLE `blob_my_livedata` ENABLE KEYS */;

-- Дамп структуры для таблица pool.bulks
CREATE TABLE IF NOT EXISTS `bulks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(100) NOT NULL,
  `typeline` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы pool.bulks: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `bulks` DISABLE KEYS */;
/*!40000 ALTER TABLE `bulks` ENABLE KEYS */;

-- Дамп структуры для таблица pool.exercise_my
CREATE TABLE IF NOT EXISTS `exercise_my` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bulk_id` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_exercise_my_bulk` (`bulk_id`),
  CONSTRAINT `FK_exercise_my_bulk` FOREIGN KEY (`bulk_id`) REFERENCES `bulks` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы pool.exercise_my: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `exercise_my` DISABLE KEYS */;
/*!40000 ALTER TABLE `exercise_my` ENABLE KEYS */;

-- Дамп структуры для таблица pool.exercise_pool
CREATE TABLE IF NOT EXISTS `exercise_pool` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bulk` int(11) DEFAULT NULL,
  `speed` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_exercise_pool_bulk` (`bulk`),
  CONSTRAINT `FK_exercise_pool_bulk` FOREIGN KEY (`bulk`) REFERENCES `bulks` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы pool.exercise_pool: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `exercise_pool` DISABLE KEYS */;
/*!40000 ALTER TABLE `exercise_pool` ENABLE KEYS */;

-- Дамп структуры для таблица pool.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы pool.users: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Alex', 'iliechev2003@gmail.com', '$2y$10$onpqYmGUGsDE/aJlRy4YKugYSMPWJAzTgHQVjsoP7XnYCAVbjOoDy', NULL, '2019-11-14 13:20:46', '2019-11-14 13:20:46');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
