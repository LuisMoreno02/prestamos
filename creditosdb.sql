-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-06-2024 a las 03:48:34
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `creditosdb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idcliente`, `nombre`, `direccion`, `telefono`, `estado`) VALUES
(1, 'Pablo', 'Chinu', '3563353546', 'Activo'),
(3, 'Maria', 'Chinu', '4444444', 'Activo'),
(6, 'Jose', 'Monteria - Cr 7 #25 - 14', '9988772', 'Inactivo'),
(8, 'susa', 'Colombia', '222222', 'Activo'),
(9, 'Patri', 'Chambacú', '6666666', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `idpago` int(11) NOT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `idprestamo` int(11) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `cuota` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`idpago`, `idcliente`, `idprestamo`, `usuario`, `fecha`, `cuota`) VALUES
(1, 6, NULL, 'Juan', '2024-05-10', 10000.00),
(2, 6, NULL, 'Juan', '2024-05-10', 10000.00),
(7, 3, NULL, 'Juan', '2024-05-29', 4000.00),
(13, 9, NULL, 'Juan', '2024-06-06', 5000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `idprestamo` int(11) NOT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `fechaprestamo` date DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `interes` decimal(5,2) DEFAULT NULL,
  `saldo` decimal(10,2) DEFAULT NULL,
  `formapago` varchar(100) DEFAULT NULL,
  `fechapago` date DEFAULT NULL,
  `plazo` varchar(50) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`idprestamo`, `idcliente`, `usuario`, `fechaprestamo`, `monto`, `interes`, `saldo`, `formapago`, `fechapago`, `plazo`, `estado`) VALUES
(2, 3, 'Juan', '2024-05-04', 50000.00, 20.00, NULL, 'Quincenal', '2024-05-18', 'Semana', 'Activo'),
(4, 9, 'Juan', '2024-05-04', 100000.00, 20.00, 120000.00, 'Quincenal', '2024-05-30', 'Dia', 'Inactivo'),
(6, 6, 'Juan', '2024-05-07', 800000.00, 5.00, 840000.00, 'Semanal', '2024-05-22', 'Semana', 'Activo'),
(7, 8, 'Juan', '2024-06-06', 160000.00, 10.00, 168000.00, 'Diario', '2024-06-21', 'Quincena', 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `login` varchar(100) DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `rol` enum('Admin','Usuario') DEFAULT 'Usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuario`, `nombre`, `direccion`, `telefono`, `login`, `clave`, `estado`, `rol`) VALUES
(1, 'Juan', 'Chinu', '300742411', 'juan', '1234', 'Activo', 'Admin'),
(0, 'Luis Moreno ', 'sahagun', '3113871296', 'luis', '1234', 'Activo', 'Usuario');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
