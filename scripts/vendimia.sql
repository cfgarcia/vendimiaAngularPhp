-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-08-2016 a las 09:05:13
-- Versión del servidor: 10.1.13-MariaDB
-- Versión de PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `test`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_articulos`
--

CREATE TABLE `cat_articulos` (
  `id_articulo` int(11) NOT NULL,
  `des_articulo` varchar(100) NOT NULL,
  `modelo_articulo` varchar(50) DEFAULT NULL,
  `precio_articulo` decimal(10,2) NOT NULL,
  `exist_articulo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_articulos`
--

INSERT INTO `cat_articulos` (`id_articulo`, `des_articulo`, `modelo_articulo`, `precio_articulo`, `exist_articulo`) VALUES
(9, '1231', 'ddd', '111.10', 11111),
(10, 'des10', 'ddd', '1321.11', 32020);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_clientes`
--

CREATE TABLE `cat_clientes` (
  `id_cliente` int(10) UNSIGNED NOT NULL,
  `nom_cliente` varchar(50) NOT NULL,
  `apellido_paterno` varchar(50) NOT NULL,
  `apellido_materno` varchar(50) DEFAULT NULL,
  `rfc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_clientes`
--

INSERT INTO `cat_clientes` (`id_cliente`, `nom_cliente`, `apellido_paterno`, `apellido_materno`, `rfc`) VALUES
(1, '1', '1p', '1m', '1r'),
(2, '2', '2p', '2m', '2adsd'),
(3, '33333', '333p3', '3333m', '333r'),
(4, '4444444', '44444Â´p', '44444m', '4444r'),
(5, '5', '5p', '5pm', '5r'),
(6, '6', '6p6', '6m', '6r'),
(7, '7', '7p', '7m', '7r'),
(8, '8', '8p', '8m', '8r'),
(9, 'nueve', 'nuevep', 'nuevem', 'nuever'),
(10, 'vvvvvv', 'vvvv', 'vvvv', 'dasdasd'),
(11, 'yyyyy', 'yyy', 'yyy', 'dfgrwer'),
(12, 'nnn', 'nnn', 'nnn', 'fsfsdfsd'),
(13, 'mmmm', 'mmm', 'mmm', 'nmfgfg'),
(14, '9', '9p', '9m', '9r'),
(15, '15', '15p', '15m', '15r'),
(16, '16', '16p', '16m', '16r'),
(17, '17n', '17p', '17m', '17r'),
(18, '18', '18p', '18m', '18r'),
(19, '19p', '19pp', '19m', '19r'),
(20, '20pppppe', '20pppp', '20mmmm', '20rrr'),
(21, '21', '21p', '21m', '21r'),
(22, '22', '22p', '22m', '22r'),
(23, '23', '23p', '23m', '23r'),
(24, '24', '24n', '24m', '24r'),
(25, '15', '15p', '15mm', '15r'),
(26, '26', '26p', '26m', '26r'),
(27, '27', '27p', '27m', '27r'),
(28, '28', '28p', '28m', '28r'),
(29, 'christian', 'garcia', 'galindo', 'aew1312'),
(30, '300', '30p3', '30m', '30r'),
(31, 'asda', 'asdas', NULL, 'cdwer432');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_configuracion`
--

CREATE TABLE `cat_configuracion` (
  `id_conf` int(11) NOT NULL,
  `tasa_fin` decimal(5,2) DEFAULT NULL,
  `por_enganche` decimal(10,2) DEFAULT NULL,
  `plazo_max` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_configuracion`
--

INSERT INTO `cat_configuracion` (`id_conf`, `tasa_fin`, `por_enganche`, `plazo_max`) VALUES
(5, '12.00', '1.00', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_tiendas`
--

CREATE TABLE `cat_tiendas` (
  `idu_tienda` int(11) NOT NULL,
  `nom_tienda` varchar(50) NOT NULL,
  `opc_estatus` int(11) NOT NULL,
  `fec_actuliazada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_tiendas`
--

INSERT INTO `cat_tiendas` (`idu_tienda`, `nom_tienda`, `opc_estatus`, `fec_actuliazada`) VALUES
(1, 'prueba', 1, '2016-08-19 04:37:58'),
(2, 'prueba2', 1, '2016-08-19 04:41:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_ventas`
--

CREATE TABLE `cat_ventas` (
  `id_venta` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `total_venta` decimal(10,2) NOT NULL,
  `fec_registrada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estatus` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cat_ventas`
--

INSERT INTO `cat_ventas` (`id_venta`, `id_cliente`, `total_venta`, `fec_registrada`, `estatus`) VALUES
(1, 1, '12.50', '2016-08-20 18:23:27', 1),
(2, 3, '11111.00', '2016-08-20 18:58:30', 1),
(3, 29, '8042.58', '2016-08-23 06:00:31', 1),
(4, 29, '6650.60', '2016-08-23 06:25:24', 1),
(5, 31, '221686.49', '2016-08-23 06:29:51', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cat_articulos`
--
ALTER TABLE `cat_articulos`
  ADD PRIMARY KEY (`id_articulo`);

--
-- Indices de la tabla `cat_clientes`
--
ALTER TABLE `cat_clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `cat_configuracion`
--
ALTER TABLE `cat_configuracion`
  ADD PRIMARY KEY (`id_conf`);

--
-- Indices de la tabla `cat_tiendas`
--
ALTER TABLE `cat_tiendas`
  ADD PRIMARY KEY (`idu_tienda`);

--
-- Indices de la tabla `cat_ventas`
--
ALTER TABLE `cat_ventas`
  ADD PRIMARY KEY (`id_venta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cat_articulos`
--
ALTER TABLE `cat_articulos`
  MODIFY `id_articulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `cat_clientes`
--
ALTER TABLE `cat_clientes`
  MODIFY `id_cliente` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT de la tabla `cat_configuracion`
--
ALTER TABLE `cat_configuracion`
  MODIFY `id_conf` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `cat_tiendas`
--
ALTER TABLE `cat_tiendas`
  MODIFY `idu_tienda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `cat_ventas`
--
ALTER TABLE `cat_ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
