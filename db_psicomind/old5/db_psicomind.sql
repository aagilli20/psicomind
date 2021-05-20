-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 25-02-2014 a las 02:37:21
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `db_psicomind`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alerta`
--

CREATE TABLE IF NOT EXISTS `alerta` (
  `id_alerta_paciente` int(11) NOT NULL,
  `mensaje_alerta` varchar(100) NOT NULL,
  `fecha_desde` date NOT NULL,
  `paciente_id_paciente` int(11) NOT NULL,
  `usuario_id_usuario` varchar(20) NOT NULL,
  PRIMARY KEY (`id_alerta_paciente`),
  KEY `fk_alerta_paciente1_idx` (`paciente_id_paciente`),
  KEY `fk_alerta_usuario1_idx` (`usuario_id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE IF NOT EXISTS `configuracion` (
  `tiempo_turno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`tiempo_turno`) VALUES
(30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dia`
--

CREATE TABLE IF NOT EXISTS `dia` (
  `id_dia` int(11) NOT NULL,
  `dia` varchar(12) NOT NULL,
  `valor_dia` varchar(4) NOT NULL,
  PRIMARY KEY (`id_dia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `dia`
--

INSERT INTO `dia` (`id_dia`, `dia`, `valor_dia`) VALUES
(1, 'Lunes', 'L'),
(2, 'Martes', 'Ma'),
(3, 'Miércoles', 'Mi'),
(4, 'Jueves', 'J'),
(5, 'Viernes', 'V'),
(6, 'Sábado', 'S');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dia_no_laboral`
--

CREATE TABLE IF NOT EXISTS `dia_no_laboral` (
  `id_dia_no_laboral` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `motivo` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id_dia_no_laboral`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `dia_no_laboral`
--

INSERT INTO `dia_no_laboral` (`id_dia_no_laboral`, `fecha`, `motivo`) VALUES
(5, '2014-03-03', 'Feriado carnaval'),
(8, '2014-03-04', 'Feriado carnaval');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidad`
--

CREATE TABLE IF NOT EXISTS `especialidad` (
  `id_especialidad` smallint(6) NOT NULL AUTO_INCREMENT,
  `especialidad` varchar(45) NOT NULL,
  `obs_especialidad` varchar(8000) DEFAULT NULL,
  PRIMARY KEY (`id_especialidad`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `especialidad`
--

INSERT INTO `especialidad` (`id_especialidad`, `especialidad`, `obs_especialidad`) VALUES
(1, 'Psicologo Clínico', 'Evalúan, diagnostican y tratan a los clientes que sufren de trastornos psicológicos. Suelen trabajar en hospitales y clínicas de salud mental'),
(2, 'Psicólogo Forense', 'Aplican la psicología a los campos de la investigación penal y la ley. Trabajan en resolver las peleas de custodia infantil, investigan las solicitudes de seguros y presuntos abusos domésticos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ficha_historia_clinica`
--

CREATE TABLE IF NOT EXISTS `ficha_historia_clinica` (
  `id_ficha` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha_ficha` date NOT NULL,
  `resultado_ficha` text NOT NULL,
  `url_audio_ficha` varchar(100) DEFAULT NULL,
  `id_usuario` varchar(20) NOT NULL,
  `id_historia_clinica` int(11) NOT NULL,
  PRIMARY KEY (`id_ficha`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_historia_clinica` (`id_historia_clinica`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_farmacologico`
--

CREATE TABLE IF NOT EXISTS `historial_farmacologico` (
  `id_aplicacion` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_aplicacion` date NOT NULL,
  `cantidad` int(11) NOT NULL,
  `observacion` varchar(300) DEFAULT NULL,
  `id_historia_clinica` int(11) NOT NULL,
  `id_usuario` varchar(20) NOT NULL,
  `id_medicamento` int(11) NOT NULL,
  PRIMARY KEY (`id_aplicacion`),
  KEY `id_historia_clinica` (`id_historia_clinica`,`id_usuario`),
  KEY `id_medicamento` (`id_medicamento`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historia_clinica`
--

CREATE TABLE IF NOT EXISTS `historia_clinica` (
  `id_historia_clinica` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_desde` date NOT NULL,
  `observacion` text,
  `id_paciente` int(11) NOT NULL,
  PRIMARY KEY (`id_historia_clinica`),
  KEY `id_paciente` (`id_paciente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario_atencion`
--

CREATE TABLE IF NOT EXISTS `horario_atencion` (
  `id_horario_atencion` int(11) NOT NULL AUTO_INCREMENT,
  `hora_inicio` varchar(8) NOT NULL,
  `hora_fin` varchar(8) NOT NULL,
  `turno_id_turno` int(11) NOT NULL,
  PRIMARY KEY (`id_horario_atencion`,`turno_id_turno`),
  KEY `fk_horario_atencion_turno1_idx` (`turno_id_turno`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Volcado de datos para la tabla `horario_atencion`
--

INSERT INTO `horario_atencion` (`id_horario_atencion`, `hora_inicio`, `hora_fin`, `turno_id_turno`) VALUES
(17, '8:0', '12:30', 1),
(18, '14:30', '20:0', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario_atencion_profesional`
--

CREATE TABLE IF NOT EXISTS `horario_atencion_profesional` (
  `matricula_profesional` varchar(14) NOT NULL,
  `id_horario_atencion` int(11) NOT NULL,
  `id_dia` int(11) NOT NULL,
  PRIMARY KEY (`matricula_profesional`,`id_horario_atencion`,`id_dia`),
  KEY `id_horario_atencion` (`id_horario_atencion`),
  KEY `id_dia` (`id_dia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `horario_atencion_profesional`
--

INSERT INTO `horario_atencion_profesional` (`matricula_profesional`, `id_horario_atencion`, `id_dia`) VALUES
('AIA23556', 17, 2),
('AIA23556', 18, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicamento`
--

CREATE TABLE IF NOT EXISTS `medicamento` (
  `id_medicamento` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `droga` varchar(200) NOT NULL,
  `forma_farmaceutica` varchar(100) NOT NULL,
  `concentracion` varchar(14) NOT NULL,
  PRIMARY KEY (`id_medicamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `minusvalia`
--

CREATE TABLE IF NOT EXISTS `minusvalia` (
  `id_minusvalia` int(11) NOT NULL AUTO_INCREMENT,
  `desc_minusvalia` varchar(100) NOT NULL,
  PRIMARY KEY (`id_minusvalia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `minusvalia`
--

INSERT INTO `minusvalia` (`id_minusvalia`, `desc_minusvalia`) VALUES
(1, 'Discapacidad motriz'),
(2, 'Discapacidad visual'),
(5, 'Disminuidos visuales'),
(6, 'Discapacidad auditiva'),
(8, 'Discapacidad mental');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nota_rapida`
--

CREATE TABLE IF NOT EXISTS `nota_rapida` (
  `id_nota_rapida` bigint(20) NOT NULL AUTO_INCREMENT,
  `nota` varchar(800) NOT NULL,
  `fecha_nota` date NOT NULL,
  `id_ficha` bigint(20) NOT NULL,
  `id_usuario` varchar(20) NOT NULL,
  PRIMARY KEY (`id_nota_rapida`),
  KEY `id_ficha` (`id_ficha`,`id_usuario`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE IF NOT EXISTS `paciente` (
  `id_paciente` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_desde` date NOT NULL,
  `activo_paciente` tinyint(1) NOT NULL,
  `persona_nro_documento` varchar(15) NOT NULL,
  `persona_sexo_id_sexo` smallint(6) NOT NULL,
  `persona_tipo_documento_id_tipo_documento` smallint(6) NOT NULL,
  PRIMARY KEY (`id_paciente`),
  KEY `fk_paciente_persona1_idx` (`persona_nro_documento`,`persona_sexo_id_sexo`,`persona_tipo_documento_id_tipo_documento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `paciente`
--

INSERT INTO `paciente` (`id_paciente`, `fecha_desde`, `activo_paciente`, `persona_nro_documento`, `persona_sexo_id_sexo`, `persona_tipo_documento_id_tipo_documento`) VALUES
(1, '2013-11-25', 1, '32101345', 2, 3),
(4, '2013-11-25', 1, '45666777', 2, 3),
(5, '2013-12-28', 1, '42896223', 2, 3),
(6, '2014-01-14', 1, '30532101', 2, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente_minusvalia`
--

CREATE TABLE IF NOT EXISTS `paciente_minusvalia` (
  `minusvalia_id_minusvalia` int(11) NOT NULL,
  `paciente_id_paciente` int(11) NOT NULL,
  KEY `fk_paciente_minusvalia_minusvalia1_idx` (`minusvalia_id_minusvalia`),
  KEY `fk_paciente_minusvalia_paciente1_idx` (`paciente_id_paciente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `paciente_minusvalia`
--

INSERT INTO `paciente_minusvalia` (`minusvalia_id_minusvalia`, `paciente_id_paciente`) VALUES
(2, 1),
(5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE IF NOT EXISTS `persona` (
  `nro_documento` varchar(15) NOT NULL,
  `nombre_persona` varchar(50) NOT NULL,
  `apellido_persona` varchar(40) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `domicilio_persona` varchar(60) DEFAULT NULL,
  `telefono_persona` varchar(20) DEFAULT NULL,
  `celular_persona` varchar(20) DEFAULT NULL,
  `url_foto_persona` varchar(100) DEFAULT NULL,
  `email_persona` varchar(50) DEFAULT NULL,
  `sexo_id_sexo` smallint(6) NOT NULL,
  `tipo_documento_id_tipo_documento` smallint(6) NOT NULL,
  PRIMARY KEY (`nro_documento`,`sexo_id_sexo`,`tipo_documento_id_tipo_documento`),
  KEY `fk_persona_sexo_idx` (`sexo_id_sexo`),
  KEY `fk_persona_tipo_documento1_idx` (`tipo_documento_id_tipo_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`nro_documento`, `nombre_persona`, `apellido_persona`, `fecha_nacimiento`, `domicilio_persona`, `telefono_persona`, `celular_persona`, `url_foto_persona`, `email_persona`, `sexo_id_sexo`, `tipo_documento_id_tipo_documento`) VALUES
('22556677', 'Wanda', 'Nara', '1993-10-09', 'Roma', '', '', NULL, '', 1, 5),
('23444999', 'Fernando', 'Fernandez', '1975-11-02', '', '', '3425222111', NULL, '', 2, 3),
('26666777', 'Gillermo Ernesto', 'Ordíz', '1979-12-29', '', '', '', NULL, '', 2, 3),
('27631472', 'Justo', 'Lowy', '0000-00-00', 'San Martin 3100', '0342-4552211', '', NULL, '', 2, 3),
('27838876', 'Evelina', 'Saita', '1981-04-27', 'San Martín 3150 2do piso', '0342-4831612', '0342-15548000', NULL, 'evelain@gmail.com', 1, 3),
('29641705', 'Elisandro', 'Saita', '1982-07-22', 'Pja', '', '', NULL, '', 2, 3),
('30532101', 'Andrés', 'Gilli', '1983-12-29', 'La Rioja 3764', '0342-4831612', '0342-154621793', './person/305321012/foto.JPG', 'aagilli20@yahoo.com.ar', 2, 3),
('32101345', 'Rodrigo', 'García', '1985-07-05', '', '0342-4509988', '', NULL, 'rodri_garcia@hotmail.com', 2, 3),
('35666777', 'Leonardo', 'Gilli', '1998-09-16', '', '', '', './person/356667772/foto.jpg', 'leogilli@hotmail.com', 2, 3),
('42896223', 'Julio', 'Ricardo', '1990-08-12', 'Saavedra 3440', '', '0342-153444999', NULL, 'jricardo@gmail.com', 2, 3),
('45666777', 'Walter', 'Perez', '1999-11-02', '', '', '', NULL, 'wperez@yahoo.com.ar', 2, 3),
('6888999', 'Analía', 'Rodriguez', '1946-10-10', '', '', '', NULL, '', 1, 2),
('99000888', 'Lucía', 'Araoz', '1990-02-08', 'No tengo ni idea', '', '', './person/990008881/foto.jpg', '', 1, 5),
('BR1113334', 'Lopez', 'Jorgelina', '1980-02-01', '', '0342-4831612', '', NULL, '', 1, 4),
('CH29000999', 'Milagros', 'Jover', '1990-07-05', '', '', '', NULL, 'milijover@gmail.com', 1, 4),
('root', 'root', 'root', '2013-10-14', NULL, NULL, NULL, NULL, NULL, 2, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesional`
--

CREATE TABLE IF NOT EXISTS `profesional` (
  `matricula` varchar(14) NOT NULL,
  `domicilio_profesional` varchar(60) NOT NULL,
  `telefono_profesional` varchar(20) DEFAULT NULL,
  `celular_profesional` varchar(20) DEFAULT NULL,
  `email_profesional` varchar(50) DEFAULT NULL,
  `activo_profesional` tinyint(1) NOT NULL,
  `fecha_desde` date NOT NULL,
  `persona_nro_documento` varchar(15) NOT NULL,
  `persona_sexo_id_sexo` smallint(6) NOT NULL,
  `persona_tipo_documento_id_tipo_documento` smallint(6) NOT NULL,
  PRIMARY KEY (`matricula`),
  KEY `fk_profesional_persona1_idx` (`persona_nro_documento`,`persona_sexo_id_sexo`,`persona_tipo_documento_id_tipo_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `profesional`
--

INSERT INTO `profesional` (`matricula`, `domicilio_profesional`, `telefono_profesional`, `celular_profesional`, `email_profesional`, `activo_profesional`, `fecha_desde`, `persona_nro_documento`, `persona_sexo_id_sexo`, `persona_tipo_documento_id_tipo_documento`) VALUES
('AIA23556', 'Iturraspe 1600', '', '', '', 1, '2013-11-23', '26666777', 2, 3),
('AIA33445', 'San Martín 3150', '0342-4831612', '3424621793', 'aagilli20@gmail.com', 1, '2013-11-23', '30532101', 2, 3),
('ISI29993', 'San Martín 3150', '', '3425222111', 'laeve_sf@hotmail.com', 1, '2013-12-01', '27838876', 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesional_dia_no_laboral`
--

CREATE TABLE IF NOT EXISTS `profesional_dia_no_laboral` (
  `id_dia_no_laboral` bigint(20) NOT NULL,
  `matricula` varchar(14) NOT NULL,
  PRIMARY KEY (`id_dia_no_laboral`,`matricula`),
  KEY `matricula` (`matricula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `profesional_dia_no_laboral`
--

INSERT INTO `profesional_dia_no_laboral` (`id_dia_no_laboral`, `matricula`) VALUES
(5, 'AIA23556'),
(8, 'AIA23556'),
(5, 'AIA33445'),
(8, 'AIA33445'),
(5, 'ISI29993'),
(8, 'ISI29993');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesional_especialidad`
--

CREATE TABLE IF NOT EXISTS `profesional_especialidad` (
  `profesional_matricula` varchar(14) NOT NULL,
  `especialidad_id_especialidad` smallint(6) NOT NULL,
  KEY `fk_profesional_especialidad_profesional1_idx` (`profesional_matricula`),
  KEY `fk_profesional_especialidad_especialidad1_idx` (`especialidad_id_especialidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `profesional_especialidad`
--

INSERT INTO `profesional_especialidad` (`profesional_matricula`, `especialidad_id_especialidad`) VALUES
('ISI29993', 1),
('AIA23556', 2),
('AIA33445', 1),
('AIA33445', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sexo`
--

CREATE TABLE IF NOT EXISTS `sexo` (
  `id_sexo` smallint(6) NOT NULL,
  `sexo` varchar(10) NOT NULL,
  PRIMARY KEY (`id_sexo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `sexo`
--

INSERT INTO `sexo` (`id_sexo`, `sexo`) VALUES
(1, 'Femenino'),
(2, 'Masculino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE IF NOT EXISTS `tipo_documento` (
  `id_tipo_documento` smallint(6) NOT NULL,
  `tipo_documento` varchar(50) NOT NULL,
  `valor_tipo_documento` varchar(20) NOT NULL,
  PRIMARY KEY (`id_tipo_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`id_tipo_documento`, `tipo_documento`, `valor_tipo_documento`) VALUES
(1, 'Libreta de Enrolamiento', 'LE'),
(2, 'Libreta Cívica', 'LC'),
(3, 'Documento Nacional de Identidad', 'DNI'),
(4, 'Pasaporte', 'PA'),
(5, 'Documento Único', 'DU'),
(6, 'Cédula Extranjera', 'CE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

CREATE TABLE IF NOT EXISTS `turno` (
  `id_turno` int(11) NOT NULL,
  `turno` varchar(12) NOT NULL,
  `valor_turno` varchar(2) NOT NULL,
  PRIMARY KEY (`id_turno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `turno`
--

INSERT INTO `turno` (`id_turno`, `turno`, `valor_turno`) VALUES
(1, 'Mañana', 'M'),
(2, 'Tarde', 'T');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` varchar(20) NOT NULL,
  `password` varchar(120) NOT NULL,
  `activo_usuario` tinyint(1) NOT NULL,
  `persona_nro_documento` varchar(15) NOT NULL,
  `sexo_id_sexo` smallint(6) NOT NULL,
  `tipo_documento_id_tipo_documento` smallint(6) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_usuario_persona1_idx` (`persona_nro_documento`,`sexo_id_sexo`,`tipo_documento_id_tipo_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `password`, `activo_usuario`, `persona_nro_documento`, `sexo_id_sexo`, `tipo_documento_id_tipo_documento`) VALUES
('agilli', 'c74a689eaf6e4b801ad29bf6b45ce05a2323b62b', 1, '30532101', 2, 3),
('gordiz', '933c45443286f552464595077676e020667582e8', 1, '26666777', 2, 3),
('root', 'dc76e9f0c0006e8f919e0c515c66dbba3982f785', 1, 'root', 2, 3);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alerta`
--
ALTER TABLE `alerta`
  ADD CONSTRAINT `fk_alerta_paciente1` FOREIGN KEY (`paciente_id_paciente`) REFERENCES `paciente` (`id_paciente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_alerta_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ficha_historia_clinica`
--
ALTER TABLE `ficha_historia_clinica`
  ADD CONSTRAINT `ficha_historia_clinica_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `ficha_historia_clinica_ibfk_3` FOREIGN KEY (`id_historia_clinica`) REFERENCES `historia_clinica` (`id_historia_clinica`);

--
-- Filtros para la tabla `historial_farmacologico`
--
ALTER TABLE `historial_farmacologico`
  ADD CONSTRAINT `historial_farmacologico_ibfk_1` FOREIGN KEY (`id_historia_clinica`) REFERENCES `historia_clinica` (`id_historia_clinica`),
  ADD CONSTRAINT `historial_farmacologico_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `historial_farmacologico_ibfk_3` FOREIGN KEY (`id_medicamento`) REFERENCES `medicamento` (`id_medicamento`);

--
-- Filtros para la tabla `historia_clinica`
--
ALTER TABLE `historia_clinica`
  ADD CONSTRAINT `historia_clinica_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`);

--
-- Filtros para la tabla `horario_atencion`
--
ALTER TABLE `horario_atencion`
  ADD CONSTRAINT `fk_horario_atencion_turno1` FOREIGN KEY (`turno_id_turno`) REFERENCES `turno` (`id_turno`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `horario_atencion_profesional`
--
ALTER TABLE `horario_atencion_profesional`
  ADD CONSTRAINT `horario_atencion_profesional_ibfk_1` FOREIGN KEY (`matricula_profesional`) REFERENCES `profesional` (`matricula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `horario_atencion_profesional_ibfk_2` FOREIGN KEY (`id_horario_atencion`) REFERENCES `horario_atencion` (`id_horario_atencion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `horario_atencion_profesional_ibfk_3` FOREIGN KEY (`id_dia`) REFERENCES `dia` (`id_dia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `nota_rapida`
--
ALTER TABLE `nota_rapida`
  ADD CONSTRAINT `nota_rapida_ibfk_1` FOREIGN KEY (`id_ficha`) REFERENCES `ficha_historia_clinica` (`id_ficha`),
  ADD CONSTRAINT `nota_rapida_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `fk_paciente_persona1` FOREIGN KEY (`persona_nro_documento`, `persona_sexo_id_sexo`, `persona_tipo_documento_id_tipo_documento`) REFERENCES `persona` (`nro_documento`, `sexo_id_sexo`, `tipo_documento_id_tipo_documento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `paciente_minusvalia`
--
ALTER TABLE `paciente_minusvalia`
  ADD CONSTRAINT `fk_paciente_minusvalia_minusvalia1` FOREIGN KEY (`minusvalia_id_minusvalia`) REFERENCES `minusvalia` (`id_minusvalia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_paciente_minusvalia_paciente1` FOREIGN KEY (`paciente_id_paciente`) REFERENCES `paciente` (`id_paciente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `fk_persona_sexo` FOREIGN KEY (`sexo_id_sexo`) REFERENCES `sexo` (`id_sexo`),
  ADD CONSTRAINT `fk_persona_tipo_documento1` FOREIGN KEY (`tipo_documento_id_tipo_documento`) REFERENCES `tipo_documento` (`id_tipo_documento`);

--
-- Filtros para la tabla `profesional`
--
ALTER TABLE `profesional`
  ADD CONSTRAINT `fk_profesional_persona1` FOREIGN KEY (`persona_nro_documento`, `persona_sexo_id_sexo`, `persona_tipo_documento_id_tipo_documento`) REFERENCES `persona` (`nro_documento`, `sexo_id_sexo`, `tipo_documento_id_tipo_documento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `profesional_dia_no_laboral`
--
ALTER TABLE `profesional_dia_no_laboral`
  ADD CONSTRAINT `profesional_dia_no_laboral_ibfk_2` FOREIGN KEY (`matricula`) REFERENCES `profesional` (`matricula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `profesional_dia_no_laboral_ibfk_1` FOREIGN KEY (`id_dia_no_laboral`) REFERENCES `dia_no_laboral` (`id_dia_no_laboral`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `profesional_especialidad`
--
ALTER TABLE `profesional_especialidad`
  ADD CONSTRAINT `fk_profesional_especialidad_especialidad1` FOREIGN KEY (`especialidad_id_especialidad`) REFERENCES `especialidad` (`id_especialidad`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_profesional_especialidad_profesional1` FOREIGN KEY (`profesional_matricula`) REFERENCES `profesional` (`matricula`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_persona1` FOREIGN KEY (`persona_nro_documento`, `sexo_id_sexo`, `tipo_documento_id_tipo_documento`) REFERENCES `persona` (`nro_documento`, `sexo_id_sexo`, `tipo_documento_id_tipo_documento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
