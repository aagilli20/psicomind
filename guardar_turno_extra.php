<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");

// inicializamos variables para el manejo de errores
$hay_error = false;
$error_msg = "";

// verificamos que el acceso provenga del botón guardar
if(isset($_REQUEST['guardar'])){
	// leemos los campos del formulario y los almacenamos en un array
	$form = array();
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	// verificamos que haya seleccionado un horario para el turno
	if($form["select_hora_ini"] == 0){
		// la hora de inicio es un dato obligatorio
		$hay_error = true;
		$error_msg = $error_msg."- Fatan datos obligatorios - Hora en la que comienza el turno</br>";
	}
	
	if($form["select_minuto_ini"] == -1){
		// el minuto de inicio es un dato obligatorio
		$hay_error = true;
		$error_msg = $error_msg."- Fatan datos obligatorios - Minuto en el que comienza</br>";
	}
	if(! $hay_error){
		// no hay error
		// preproceso del formulario
		// adaptación del horario para almacenarlo en la db
		$horario = "";
		if($form["select_hora_ini"] < 10) $horario = "0".$form["select_hora_ini"].":";
		else $horario = $form["select_hora_ini"].":";
		if($form["select_minuto_ini"] < 10) $horario = $horario."0".$form["select_minuto_ini"];
		else $horario = $horario.$form["select_minuto_ini"];
		$matricula = $form["id_profesional"];
		$id_paciente = $form["id_paciente"];
		// fecha
		$fecha = fecha_mysql($form["dia_elegido"]);
		$id_turno = $form["id_turno"];
		// guardamos el turno otorgado en la base
		$sql = "INSERT INTO turno_otorgado(id_turno_otorgado,fecha_turno,horario,id_paciente,id_profesional,id_turno) 
		VALUES ('NULL','$fecha','$horario','$id_paciente','$matricula','$id_turno');";
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// ejecutamos el INSERT y verificamos el resultado
		if($conexion->Execute($sql)){
			// notificar que el dia no laboral fue creado
			$tpl->set_var('mensaje', "El turno fue registrado exitosamente");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "index.php");
			$tpl->set_var('mensaje_std', "Volver al inicio");
			// parseamos la plantilla
			$tpl->parse('info');
			// forzamos la detención del script
			die();
		}else{
			// notificar error
			$tpl->set_var('mensaje', "Error turno no se ha podido guardar, intentelo nuevamente");
			$tpl->set_var('html_adicional', "");
			$id_paciente2 = base64_encode($form["id_paciente"]);
			$matricula2 = base64_encode($form["id_profesional"]);
			$tpl->set_var('enlace_std', "consignar_turno.php?id1=".$id_paciente2."&id2=".$matricula2);
			$tpl->set_var('mensaje_std', "Volver a Intentarlo");
			// parseamos la plantilla
			$tpl->parse('info');
			// forzamos la detención del script
			die();
		}
	} else {
		// no seleccionó un horario para el turno
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');	
		// notificar error
			$tpl->set_var('mensaje', "Antes de confirmar debe seleccionar el horario para el turno");
			$tpl->set_var('html_adicional', "");
			$id_paciente2 = base64_encode($form["id_paciente"]);
			$matricula2 = base64_encode($form["id_profesional"]);
			$tpl->set_var('enlace_std', "consignar_turno.php?id1=".$id_paciente2."&id2=".$matricula2);
			$tpl->set_var('mensaje_std', "Volver a Intentarlo");
			// parseamos la plantilla
			$tpl->parse('info');
			// forzamos la detención del script
			die();
	}
}else{
	// cargar de nuevo guardar turno extra con el mensaje de error correspondiente
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('turno_extra','turno_extra.tpl');
	// seteamos las variables
	$tpl->set_var('id_paciente2', base64_encode($id_paciente));
	$tpl->set_var('id_profesional2', base64_encode($matricula));
	$tpl->set_var('id_paciente', $id_paciente);
	$tpl->set_var('id_profesional', $matricula);
	$tpl->set_var('error', $error_msg);
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
	// cargamos el menu principal
	$tpl->set_var('menu', getMenu());
	// parseamos la planitlla
	$tpl->parse('turno_extra');
}

?>