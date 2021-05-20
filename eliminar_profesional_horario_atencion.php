<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("menu.php");

// variables para el manejo de errores
$hay_error = false;
$msg_error = "";

// verificamos que se haya seleccionado un profesional, un día y un horario
if(isset($_GET['prof']) && isset($_GET['hora']) && isset($_GET['dia'])){
	// hay que decodificar el id
	$matricula = base64_decode($_GET['prof']);
	$id_horario_atencion = base64_decode($_GET['hora']);
	$id_dia = base64_decode($_GET['dia']);
	
	// verificar que no haya turnos pendientes en ese horario de atención
	// verificar que no haya turnos pendientes en ese horario de atención
	
	// si no se detectaron inconvenientes
	if(! $hay_error){
		// eliminamos el horario de atención
		$sql = "DELETE FROM horario_atencion_profesional WHERE matricula_profesional='$matricula' AND id_horario_atencion='$id_horario_atencion' AND id_dia='$id_dia';";
		if(! $conexion->Execute($sql)) {
			// se detectó un error al ejecutar la operación
			$hay_error = true;
			$msg_error = "Se produjo un error inesperado al eliminar el horario de atención del profesional, intentelo nuevamente".$conexion->ErrorMsg();		
		}
	}
	// actualizamos la pantalla la pantalla
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('profesional_horario_atencion', 'profesional_horario_atencion.tpl');
	// llenamos los campos de la plantilla
	$tpl->set_var('matricula', $matricula);
	// informamos si hubo error o se registró correctamente la asociación
	if($hay_error) $tpl->set_var('error', $msg_error); 
	else $tpl->set_var('error', "La asosiación entre el horario de atención y el profesional ha sido eliminada con éxito"); 
	// consultamos nombre y apellido
	$persona = $conexion->GetRow("SELECT p.nombre_persona,p.apellido_persona FROM persona p,profesional pr 
										WHERE p.sexo_id_sexo=pr.persona_sexo_id_sexo 
										AND tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento 
										AND nro_documento=pr.persona_nro_documento
										AND pr.matricula='$matricula'");
	$tpl->set_var('nombre', $persona['nombre_persona']);
	$tpl->set_var('apellido', $persona['apellido_persona']);
	
	// cargamos listado de horarios de atencion actuales
	// consultamos la cantidad de horarios de atención asociados al profesional
	$db_cant = $conexion->GetRow("SELECT Count(*) FROM horario_atencion_profesional WHERE matricula_profesional='$matricula';");
	// consultamos el listado de horarios asociados al profesional
	$sql = "SELECT * FROM horario_atencion_profesional WHERE matricula_profesional='$matricula';";
	$db_horarios_act = $conexion->Execute($sql);
	// creamos un array para almacenar la consulta
	$lista_horarios_act = array();
	// verificamos la existencia de resultados
	if($db_cant["Count(*)"] == 0){
		// no hay horarios de atención asociados al profesional
		array_push($lista_horarios_act, array('dia' => "El profesional no tiene horarios de atención asignados", 'turno' => "", 'hora_ini' => "",
		'hora_fin' => "", 'id_horario' => ""));
	}else{
		// llenamos el array con la consulta
		foreach($db_horarios_act as $horario){
			$td_id_horario_atencion = $horario["id_horario_atencion"];
			$td_id_dia = $horario["id_dia"];
			// obtenemos el nombre del día a partir de su identificador
			$td_dia = $conexion->GetRow("SELECT dia FROM dia WHERE id_dia='$td_id_dia';");
			// obtenemos el horario de atención a partir de su identificador
			$row_horario_atencion = $conexion->GetRow("SELECT * FROM horario_atencion WHERE id_horario_atencion='$td_id_horario_atencion';");
			$td_id_turno = $row_horario_atencion["turno_id_turno"];
			// obtenemos el nombre del turno a partir de su identificador
			$td_turno = $conexion->GetRow("SELECT turno FROM turno WHERE id_turno='$td_id_turno';");
			// codificamos un identificador de la fila para poder identificar sobre que fila se hizo un clic
			$td_id_horario = "prof=".base64_encode($matricula)."&hora=".base64_encode($td_id_horario_atencion)."&dia=".base64_encode($td_id_dia);
			array_push($lista_horarios_act, array('dia' => $td_dia['dia'],'turno' => $td_turno['turno'], 'hora_ini' => $row_horario_atencion['hora_inicio'],
			'hora_fin' => $row_horario_atencion['hora_fin'], 'id_horario' => $td_id_horario));
		}
	}
	// cargamos el array en el bloque_horario_actual de la plantilla profesional_horario_atencion.tpl
	$tpl->set_loop('bloque_horario_actual', $lista_horarios_act);
	// fin lista horarios de atencion actuales
	
	// consultamos el listado de turnos
	$db_turno = $conexion->Execute("SELECT * FROM turno");
	// creamos un array para almacenar la consulta
	$lista_turno = array();
	// el primer valor del array va a ser seleccione un turno
	array_push($lista_turno, array('id' => '0', 'valor' => 'Seleccione el turno'));
	// llenamos el array con el resultado de la consulta
	foreach($db_turno as $turno){
		array_push($lista_turno, array('id' => $turno['id_turno'], 'valor' => $turno['turno']));
	}
	// cargamos el array en el bloque_turnos de la plantilla profesional_horario_atencion.tpl
	$tpl->set_loop('bloque_turnos', $lista_turno);
	// cargamos listado de horarios de atencion
	// consultamos la cantidad de horarios de atención disponibles
	$db_cant = $conexion->GetRow("SELECT Count(*) FROM horario_atencion;");
	// obtenemso el listado de horarios de atención disponibles
	$sql = "SELECT * FROM horario_atencion ORDER BY turno_id_turno,id_horario_atencion";
	$db_horarios = $conexion->Execute($sql);
	// creamos un array para almacenar los resultados
	$lista_horarios = array();
	// verificamos la existencia de resultados
	if($db_cant["Count(*)"] == 0){
		// no hay horarios de atención registrados
		array_push($lista_horarios, array('turno' => "Sin resultados, antes debe registrar algún horario de atención", 'hora_ini' => "",
		'hora_fin' => "", 'id_horario' => "", 'disabled' => "disabled"));
	}else{
		// llenamos el array con el resultado de la consulta
		foreach($db_horarios as $horario){
			$td_id_turno = $horario["turno_id_turno"];
			$td_turno = $conexion->GetRow("SELECT turno FROM turno WHERE id_turno='$td_id_turno';");
			array_push($lista_horarios, array('turno' => $td_turno['turno'], 'hora_ini' => $horario['hora_inicio'],
			'hora_fin' => $horario['hora_fin'], 'id_horario' => $horario['id_horario_atencion'], 'disabled' => ''));
		}
	}
	// cargamos el array en el bloque_horario de la plantilla profesional_horario_atencion.tpl
	$tpl->set_loop('bloque_horario', $lista_horarios);
	// fin lista horarios de atencion
	
	// cargamos el menú principal
	$tpl->set_var('menu', getMenu());
	// parseamos la plantilla
	$tpl->parse('profesional_horario_atencion');
	
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>