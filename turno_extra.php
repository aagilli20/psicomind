<?php  
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once("fechas.php");
require_once("validacion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// inicializamos variables para el manejo de errores
$hay_error = false;
$error_msg = "";

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('turno_extra','turno_extra.tpl');

// leemos las variables pasadas por GET
$id_paciente = base64_decode($_GET["id1"]);
$matricula = base64_decode($_GET["id2"]);
$fecha = $_GET["id3"];
$id_turno = $_GET["id4"];
// verificamos que esten todos los datos sin alteraciones
if(empty($_GET["id1"]) || empty($_GET["id2"]) || empty($_GET["id3"]) || empty($_GET["id4"])){
	// intento de acceso no autorizado
	header("location:info.php?id=7");
	die();
} else {
	// verificamos que las variables sean válidas
	// verificacion del id paciente
	$coincidencia = $conexion->GetRow("SELECT Count(*) FROM paciente WHERE id_paciente='$id_paciente'");
	if($coincidencia["Count(*)"] == 0) {
		// el id paciente ha sido modificado
		// intento de acceso no autorizado
		header("location:info.php?id=7");
		die();
	}
	// verificamos el numero de matricula
	$coincidencia = $conexion->GetRow("SELECT Count(*) FROM profesional WHERE matricula='$matricula'");
	if($coincidencia["Count(*)"] == 0) {
		// el numero de matricula ha sido modificado
		// intento de acceso no autorizado
		header("location:info.php?id=7");
		die();
	}
	// no hay errores, entonces parseamos la plantilla
	$tpl->set_var('id_paciente2', $_GET["id1"]);
	$tpl->set_var('id_profesional2', $_GET["id2"]);
	$tpl->set_var('id_paciente', $id_paciente);
	$tpl->set_var('id_profesional', $matricula);
	$tpl->set_var('error', "");
	$tpl->set_var('dia_turno', $fecha);
	$tpl->set_var('dia_turno2', $fecha);
	$tpl->set_var('id_turno', $id_turno);
	// consultamos el turno elegido
	$nombre_turno = $conexion->GetRow("SELECT turno FROM turno WHERE id_turno='$id_turno';");
	$tpl->set_var('turno_elegido', $nombre_turno["turno"]);
	// cargamos los combos para seleccionar el horario del turno
	// creamos un array para almacenar la lista de horas
	$lista_hora = array();
	array_push($lista_hora, array('id' => '0', 'valor' => "Hora", 'selected' => ''));
	// generamos el listado de horas
	for($i=6; $i<24; $i++){
		array_push($lista_hora, array('id' => $i, 'valor' => $i, 'selected' => ''));
	}
	// cargamos el array en el bloque_hora_ini de la plantilla turno_extra.php
	$tpl->set_loop('bloque_hora_ini', $lista_hora);
	// creamos un array para almacenar la lista de minutos
	$lista_minuto = array();
	// generamos el listado de minutos salteando de a 5 minutos
	array_push($lista_minuto, array('id' => '-1', 'valor' => "Minuto", 'selected' => ''));
	for($i=0; $i<60; $i=$i+5){
		array_push($lista_minuto, array('id' => $i, 'valor' => $i, 'selected' => ''));
	}
	// cargamos el array en el bloque_minuto_ini de la plantilla turno_extra.php
	$tpl->set_loop('bloque_minuto_ini', $lista_minuto);
	// consultamos el nombre y apellido del paciente seleccionado
	$sql = "SELECT p.nombre_persona, p.apellido_persona FROM persona p, paciente pa 
			WHERE p.nro_documento=pa.persona_nro_documento AND p.sexo_id_sexo=pa.persona_sexo_id_sexo
			AND p.tipo_documento_id_tipo_documento=pa.persona_tipo_documento_id_tipo_documento
			AND pa.id_paciente='$id_paciente';";
	$db_paciente = $conexion->GetRow($sql);
	// consultamos el nombre y apellido del profesional seleccionado
	$sql = "SELECT p.nombre_persona, p.apellido_persona FROM persona p, profesional pr 
	WHERE p.nro_documento=pr.persona_nro_documento AND p.sexo_id_sexo=pr.persona_sexo_id_sexo
	AND p.tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento
	AND pr.matricula='$matricula';";
	$db_profesional = $conexion->GetRow($sql);
	// seteamos las variables del template
	$tpl->set_var("nombre_paciente", $db_paciente["apellido_persona"].", ".$db_paciente["nombre_persona"]);
	$tpl->set_var("nombre_profesional", $db_profesional["apellido_persona"].", ".$db_profesional["nombre_persona"]);				
}

// cargamos el menu principal
$tpl->set_var('menu', getMenu());
// parseamos la planitlla
$tpl->parse('turno_extra');

?>