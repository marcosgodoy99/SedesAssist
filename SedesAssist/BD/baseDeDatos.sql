-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para sistema_toma_asistencias
CREATE DATABASE IF NOT EXISTS `sistema_toma_asistencias` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sistema_toma_asistencias`;

-- Volcando estructura para tabla sistema_toma_asistencias.alumnos
CREATE TABLE IF NOT EXISTS `alumnos` (
  `dni` int NOT NULL,
  `idAlumno` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `apellido` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fechaNacimiento` date NOT NULL,
  PRIMARY KEY (`idAlumno`),
  UNIQUE KEY `dni` (`dni`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_toma_asistencias.alumnos: ~27 rows (aproximadamente)
INSERT INTO `alumnos` (`dni`, `idAlumno`, `nombre`, `apellido`, `fechaNacimiento`) VALUES
	(43333099, 1, 'Agustin Ezequiel', 'Cabrera', '2001-03-03'),
	(42069298, 2, 'Marcos Damian', 'Godoy', '2000-09-07'),
	(43149316, 3, 'Franco Agustin', 'Chappe', '2000-01-19'),
	(42850626, 4, 'Lucas Gabriel', 'Barreiro', '2000-02-17'),
	(45847922, 5, 'Franco', 'Cabrera', '2002-07-17'),
	(43632750, 6, 'Roman', 'Coletti', '2001-06-30'),
	(40790201, 7, 'Daian Exequiel', 'Fernandez', '1998-11-06'),
	(44980999, 8, 'Nicolas Osvaldo', 'Fernandez', '2002-06-03'),
	(44623314, 9, 'Facundo Geronimo', 'Figun', '2003-05-07'),
	(45389325, 10, 'Lucas Jeremias', 'Fiorotto', '2004-08-15'),
	(45048325, 11, 'Felipe', 'Franco', '2004-07-10'),
	(43631803, 12, 'Bruno', 'Godoy', '2001-06-29'),
	(45385675, 13, 'Teo', 'Hildt', '2003-07-24'),
	(41872676, 14, 'Facundo Ariel', 'Janusa', '1999-04-08'),
	(45048950, 15, 'Facundo Martin', 'Jara', '2003-05-11'),
	(45387761, 16, 'Santiago Nicolas', 'Martinez Bender', '2003-09-07'),
	(45741185, 17, 'Pablo Federico', 'Martinez', '2004-06-06'),
	(44981059, 18, 'Federico José', 'Martinolich', '2002-10-13'),
	(42070085, 19, 'Maria Pia', 'Melgarejo', '2000-09-21'),
	(43631710, 20, 'Thiago Jeremias', 'Meseguer', '2001-05-10'),
	(44644523, 21, 'Ignacio Agustin', 'Piter', '2002-05-16'),
	(44282007, 22, 'Bianca Ariana', 'Quiroga', '2002-06-18'),
	(40018598, 23, 'Kevin Gustavo', 'Quiroga', '1998-09-20'),
	(38570361, 24, 'Marcos', 'Reynoso', '1996-03-13'),
	(39255959, 25, 'Franco Antonio', 'Robles', '1997-10-22'),
	(43414566, 26, 'Maximiliano', 'Weyler', '2001-03-10'),
	(28358603, 47, 'javier alejandro', 'parra', '2023-10-20');

-- Volcando estructura para tabla sistema_toma_asistencias.asistencias
CREATE TABLE IF NOT EXISTS `asistencias` (
  `idAlumno` int NOT NULL,
  `fechaAsistencia` timestamp NOT NULL,
  `idProfesor` int NOT NULL,
  UNIQUE KEY `Índice 3` (`idAlumno`,`fechaAsistencia`),
  KEY `idProfesor` (`idProfesor`),
  KEY `idAlumno` (`idAlumno`),
  CONSTRAINT `idAlumno` FOREIGN KEY (`idAlumno`) REFERENCES `alumnos` (`idAlumno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `idProfesor` FOREIGN KEY (`idProfesor`) REFERENCES `profesores` (`idProfesor`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_toma_asistencias.asistencias: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sistema_toma_asistencias.parametros
CREATE TABLE IF NOT EXISTS `parametros` (
  `clasesTotales` int NOT NULL DEFAULT '0',
  `porcentajePromocion` int NOT NULL DEFAULT '0',
  `porcentajeRegular` int NOT NULL DEFAULT '0',
  `IDP` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_toma_asistencias.parametros: ~1 rows (aproximadamente)
INSERT INTO `parametros` (`clasesTotales`, `porcentajePromocion`, `porcentajeRegular`, `IDP`) VALUES
	(0, 0, 0, 1);

-- Volcando estructura para tabla sistema_toma_asistencias.profesores
CREATE TABLE IF NOT EXISTS `profesores` (
  `nombre` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `dni` int NOT NULL,
  `idProfesor` int NOT NULL AUTO_INCREMENT,
  `apellido` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fechaNacimiento` date NOT NULL,
  PRIMARY KEY (`idProfesor`),
  UNIQUE KEY `dni` (`dni`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla sistema_toma_asistencias.profesores: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
