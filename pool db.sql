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

-- Дамп структуры для таблица pool.bulks
CREATE TABLE IF NOT EXISTS `bulks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `type` varchar(300) NOT NULL,
  `line` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_bulks_files` (`file_id`),
  CONSTRAINT `FK_bulks_files` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы pool.bulks: ~1 rows (приблизительно)
/*!40000 ALTER TABLE `bulks` DISABLE KEYS */;
INSERT INTO `bulks` (`id`, `file_id`, `type`, `line`) VALUES
	(33, 84, 'com.samsung.health.exercise', 'time_offset,end_time,altitude_loss,max_altitude,start_time,count,altitude_gain,exercise_custom_type,duration,deviceuuid,max_heart_rate,max_rpm,mean_heart_rate,pkg_name,max_cadence,mean_caloricburn_rate,incline_distance,exercise_type,decline_distance,max_speed,mean_power,max_power,mean_rpm,calorie,mean_cadence,mean_speed,update_time,min_altitude,min_heart_rate,live_data,count_type,max_caloricburn_rate,custom,comment,additional,distance,location_data,datauuid,create_time');
/*!40000 ALTER TABLE `bulks` ENABLE KEYS */;

-- Дамп структуры для таблица pool.exercises
CREATE TABLE IF NOT EXISTS `exercises` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bulk_id` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `duration` float DEFAULT NULL,
  `distance` float DEFAULT NULL,
  `mean_speed` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_exercise_my_bulk` (`bulk_id`),
  CONSTRAINT `FK_exercise_my_bulk` FOREIGN KEY (`bulk_id`) REFERENCES `bulks` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10141 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы pool.exercises: ~8 rows (приблизительно)
/*!40000 ALTER TABLE `exercises` DISABLE KEYS */;
INSERT INTO `exercises` (`id`, `bulk_id`, `start_time`, `duration`, `distance`, `mean_speed`) VALUES
	(10133, 33, '2016-09-18 09:58:00', 960.13, 1464.19, 1.52499),
	(10134, 33, '2016-09-18 13:45:00', 968.602, 1429.68, 1.47603),
	(10135, 33, '2016-09-19 07:03:00', 906.382, 1507.98, 1.66374),
	(10136, 33, '2016-09-19 11:03:00', 946.377, 1139.98, 1.20458),
	(10137, 33, '2016-09-19 14:43:22', 949.397, 1355.45, 1.42769),
	(10138, 33, '2016-09-19 18:08:00', 843.428, 1105.41, 1.31061),
	(10139, 33, '2016-09-21 05:33:00', 686.304, 1250.48, 1.82206),
	(10140, 33, '2016-09-21 10:58:42', 921.417, 1441.51, 1.56445);
/*!40000 ALTER TABLE `exercises` ENABLE KEYS */;

-- Дамп структуры для таблица pool.files
CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(250) NOT NULL,
  `stor_name` varchar(250) NOT NULL,
  `ext` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы pool.files: ~9 rows (приблизительно)
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` (`id`, `filename`, `stor_name`, `ext`) VALUES
	(84, 'com.samsung.health.exercise.201911101612.csv', 'uploads/JrHICw8HkLHFQRC5K8O8FMYoQf6TxNh3DMfeQvIz.txt', 'txt'),
	(85, 'live_data.2.blob', 'uploads/Xh2qZTLqCWnY2h9Y4JJz7t6RPrUlwY3gDUqL96rU.gz', 'gz'),
	(86, 'live_data.3.blob', 'uploads/XtgFLzr4GSt0MRybOG44I1oVNpmCccfKKO291Sca.gz', 'gz'),
	(87, 'live_data.5.blob', 'uploads/kdTLBUnqKi5I7DDUsLmXlX653czBcCFmw7njWCUK.gz', 'gz'),
	(88, 'live_data.7.blob', 'uploads/74ntBFPQsnir8JwjVaaDSboOxGVMv3LvLb1AlOgC.gz', 'gz'),
	(89, 'live_data.9.blob', 'uploads/U73jkSDFdpB1bmQori4Pf6EufUiVDbny0DfThEcy.gz', 'gz'),
	(90, 'live_data.11.blob', 'uploads/Rda2Oq4YamJwFMwjxZBLmJhz9Hny9fcGMJiF16JE.gz', 'gz'),
	(91, 'live_data.13.blob', 'uploads/hy0gDA3pfC6OQlPLAmnrpcN8oCWWkts8DIBytCDQ.gz', 'gz'),
	(92, 'live_data.15.blob', 'uploads/Lo0lGMI7HJMJUnwXzvzSMEE1dBnDQhDI2lHdAjFL.gz', 'gz');
/*!40000 ALTER TABLE `files` ENABLE KEYS */;

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

-- Дамп данных таблицы pool.users: ~1 rows (приблизительно)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Alex', 'iliechev2003@gmail.com', '$2y$10$onpqYmGUGsDE/aJlRy4YKugYSMPWJAzTgHQVjsoP7XnYCAVbjOoDy', 'p2OwCppkWTrfbNSTRcYYKZo4DrykZZxUaA4aTVLU2kHD6leXtPBqCgztex0H', '2019-11-14 13:20:46', '2019-11-14 13:20:46');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
