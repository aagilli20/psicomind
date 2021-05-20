-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 05-11-2013 a las 15:37:12
-- Versión del servidor: 5.6.12-log
-- Versión de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `db_psicomind`
--
CREATE DATABASE IF NOT EXISTS `db_psicomind` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `db_psicomind`;

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
-- Estructura de tabla para la tabla `dia`
--

CREATE TABLE IF NOT EXISTS `dia` (
  `id_dia` int(11) NOT NULL,
  `dia` varchar(12) NOT NULL,
  `valor_dia` varchar(4) NOT NULL,
  PRIMARY KEY (`id_dia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidad`
--

CREATE TABLE IF NOT EXISTS `especialidad` (
  `id_especialidad` smallint(6) NOT NULL,
  `especialidad` varchar(45) NOT NULL,
  `obs_especialidad` varchar(8000) DEFAULT NULL,
  PRIMARY KEY (`id_especialidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario_atencion`
--

CREATE TABLE IF NOT EXISTS `horario_atencion` (
  `id_horario_atencion` int(11) NOT NULL,
  `hora_inicio` varchar(8) NOT NULL,
  `hora_fin` varchar(8) NOT NULL,
  `dia_id_dia` int(11) NOT NULL,
  `turno_id_turno` int(11) NOT NULL,
  `profesional_matricula` varchar(14) NOT NULL,
  PRIMARY KEY (`id_horario_atencion`,`dia_id_dia`,`turno_id_turno`),
  KEY `fk_horario_atencion_dia1_idx` (`dia_id_dia`),
  KEY `fk_horario_atencion_turno1_idx` (`turno_id_turno`),
  KEY `fk_horario_atencion_profesional1_idx` (`profesional_matricula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `minusvalia`
--

CREATE TABLE IF NOT EXISTS `minusvalia` (
  `id_minusvalia` int(11) NOT NULL,
  `desc_minusvalia` varchar(100) NOT NULL,
  PRIMARY KEY (`id_minusvalia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE IF NOT EXISTS `paciente` (
  `id_paciente` int(11) NOT NULL,
  `fecha_desde` date NOT NULL,
  `activo_paciente` tinyint(1) NOT NULL,
  `persona_nro_documento` varchar(15) NOT NULL,
  `persona_sexo_id_sexo` smallint(6) NOT NULL,
  `persona_tipo_documento_id_tipo_documento` smallint(6) NOT NULL,
  PRIMARY KEY (`id_paciente`),
  KEY `fk_paciente_persona1_idx` (`persona_nro_documento`,`persona_sexo_id_sexo`,`persona_tipo_documento_id_tipo_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
('27838876', 'Evelina', 'Saita', '1981-04-27', 'San Martín 3150 2do piso', '0342-4831612', '0342-15548000', NULL, 'evelain@gmail.com', 1, 3),
('30532101', 'Andrés', 'Gilli', '1983-12-29', 'La Rioja 3764', '0342-4831612', '0342-154621793', './person/305321012/foto.JPG', 'aagilli20@yahoo.com.ar', 2, 3),
('35666777', 'Leonardo', 'Gilli', '1999-10-10', '', '', '', './person/356667772/foto.jpg', '', 2, 3),
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
-- Filtros para la tabla `horario_atencion`
--
ALTER TABLE `horario_atencion`
  ADD CONSTRAINT `fk_horario_atencion_dia1` FOREIGN KEY (`dia_id_dia`) REFERENCES `dia` (`id_dia`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_horario_atencion_turno1` FOREIGN KEY (`turno_id_turno`) REFERENCES `turno` (`id_turno`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_horario_atencion_profesional1` FOREIGN KEY (`profesional_matricula`) REFERENCES `profesional` (`matricula`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
  ADD CONSTRAINT `fk_persona_tipo_documento1` FOREIGN KEY (`tipo_documento_id_tipo_documento`) REFERENCES `tipo_documento` (`id_tipo_documento`),
  ADD CONSTRAINT `fk_persona_sexo` FOREIGN KEY (`sexo_id_sexo`) REFERENCES `sexo` (`id_sexo`);

--
-- Filtros para la tabla `profesional`
--
ALTER TABLE `profesional`
  ADD CONSTRAINT `fk_profesional_persona1` FOREIGN KEY (`persona_nro_documento`, `persona_sexo_id_sexo`, `persona_tipo_documento_id_tipo_documento`) REFERENCES `persona` (`nro_documento`, `sexo_id_sexo`, `tipo_documento_id_tipo_documento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `profesional_especialidad`
--
ALTER TABLE `profesional_especialidad`
  ADD CONSTRAINT `fk_profesional_especialidad_profesional1` FOREIGN KEY (`profesional_matricula`) REFERENCES `profesional` (`matricula`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_profesional_especialidad_especialidad1` FOREIGN KEY (`especialidad_id_especialidad`) REFERENCES `especialidad` (`id_especialidad`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_persona1` FOREIGN KEY (`persona_nro_documento`, `sexo_id_sexo`, `tipo_documento_id_tipo_documento`) REFERENCES `persona` (`nro_documento`, `sexo_id_sexo`, `tipo_documento_id_tipo_documento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
