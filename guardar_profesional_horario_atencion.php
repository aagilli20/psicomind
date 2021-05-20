<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");
require_once("menu.php");

// inicializamos variables para el manejo de errores
$hay_error = false;
$msg_error = "";

// verificamos que el acceso provenga del botón guardar
if(isset($_REQUEST['guardar'])){
	// leemos los campos del formulario y los almacenamos en un array
	$form = array();
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	// obtenemos el numero de matrícula
	$matricula = $form["matricula"];
	// verificamos que haya seleccionado al menos un día
	if(! isset($form["dias"])){
		// debe seleccionar al menos un día para este horario
		$hay_error = true;
		$msg_error = "Fatan datos obligatorios - Debe seleccionar al menos un día para este horario</br>";
	}
	// verificamos que haya seleccionado un horario de atención
	if(! isset($form["id_horario"])){
		// debe seleccionar el horario de atención
		$hay_error = true;
		$msg_error = $msg_error."Fatan datos obligatorios - Debe seleccionar un horario de atención</br>";
	}		
	// si hasta aca no hubo errores
	// verificamos que no existan turnos duplicados
	if(! $hay_error){
		// obtenemos el id del horario
		$id_horario = $form["id_horario"];
		// consultamos el turno correspondiente al horario
		$row_horario = $conexion->GetRow("SELECT turno_id_turno FROM horario_atencion WHERE id_horario_atencion='$id_horario';");
		$id_turno = $row_horario["turno_id_turno"];
		$cantidad = 0;
		// para cada dia tildado en el formulario
		foreach($form["dias"] as $id_dia){
			// consultamos la cantidad de registros en la base que coincidan con el registro a almacenar
			$row_cant = $conexion->GetRow("SELECT Count(*) FROM horario_atencion_profesional hap,horario_atencion ha 
										WHERE hap.matricula_profesional='$matricula'
										AND hap.id_horario_atencion=ha.id_horario_atencion 
										AND ha.turno_id_turno='$id_turno' 
										AND hap.id_dia='$id_dia';");
			if($row_cant["Count(*)"] > 0) $cantidad++;
		}
		// verificamos si la cantidad supera 0
		if($cantidad > 0){
			// el profesional ya tiene un horario de atención asignado en ese turno para ese día
			$hay_error = true;
			$msg_error = $msg_error."Formato de datos - El profesional ya tiene asignado un horario de atención en ese turno para el/los días seleccionados</br>";		
		}
	}
	// si hasta aca no hay errores, vamos a guardar el registro
	if(! $hay_error){
		// iniciar transaccion
		$conexion->StartTrans();
		$sql = "INSERT INTO horario_atencion_profesional (matricula_profesional,id_horario_atencion,id_dia) VALUES ";
		// grabar por día
		$primero = true;
		foreach($form["dias"] as $id_dia){
			if($primero) {
				$sql = $sql."('$matricula','$id_horario','$id_dia')";
				$primero = false;	
			}
			else $sql = $sql.",('$matricula','$id_horario','$id_dia')";
		}
		$sql = $sql.";";
		$conexion->Execute($sql);
		// finaliza la transacción
		if(! $conexion->CompleteTrans()) {
			// fallo la transaccion
			$hay_error = true;
			$msg_error = $msg_error."Error inesperado al guardar los cambios en la base de datos, intentelo nuevamente".$conexion->ErrorMsg();
		}
	}
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('profesional_horario_atencion', 'profesional_horario_atencion.tpl');
	// obtenemos el numero de matricula
	$matricula = $_REQUEST["matricula"];
	// llenamos los campos de la plantilla
	$tpl->set_var('matricula', $matricula);
	// consultar nombre y apellido
	$persona = $conexion->GetRow("SELECT p.nombre_persona,p.apellido_persona FROM persona p,profesional pr 
										WHERE p.sexo_id_sexo=pr.persona_sexo_id_sexo 
										AND tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento 
										AND nro_documento=pr.persona_nro_documento
										AND pr.matricula='$matricula'");
	// informamos si hubo error o se registró correctamente la asociación
	if($hay_error) $tpl->set_var('error', $msg_error); 
	else $tpl->set_var('error', "El horario de atención ha sido asociado de forma correcta al profesional"); 
	$tpl->set_var('nombre', $persona['nombre_persona']);
	$tpl->set_var('apellido', $persona['apellido_persona']);
	
	// cargamos listado de horarios de atencion actuales
	// consultamos la cantidad de horarios de atencion asociados al profesional
	$db_cant = $conexion->GetRow("SELECT Count(*) FROM horario_atencion_profesional WHERE matricula_profesional='$matricula';");
	// consultamos la lista de horarios de atencion asociados al profesional
	$sql = "SELECT * FROM horario_atencion_profesional WHERE matricula_profesional='$matricula';";
	$db_horarios_act = $conexion->Execute($sql);
	// creamos un arary para almacenar los resultados de la consulta
	$lista_horarios_act = array();
	// verificamos la existencia de resultados
	if($db_cant["Count(*)"] == 0){
		// no hay resultados para mostrar
		array_push($lista_horarios_act, array('dia' => "El profesional no tiene horarios de atención asignados", 'turno' => "", 'hora_ini' => "",
		'hora_fin' => "", 'id_horario' => ""));
	}else{
		// cargamos el resultado de la consulta en el array
		foreach($db_horarios_act as $horario){
			$td_id_horario_atencion = $horario["id_horario_atencion"];
			$td_id_dia = $horario["id_dia"];
			// consultamos el nombre del día a partir de su ID
			$td_dia = $conexion->GetRow("SELECT dia FROM dia WHERE id_dia='$td_id_dia';");
			// consultamos el horario de atención a partir de su ID
			$row_horario_atencion = $conexion->GetRow("SELECT * FROM horario_atencion WHERE id_horario_atencion='$td_id_horario_atencion';");
			$td_id_turno = $row_horario_atencion["turno_id_turno"];
			// consultamos el turno a partir de su ID
			$td_turno = $conexion->GetRow("SELECT turno FROM turno WHERE id_turno='$td_id_turno';");
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
	// creamos un array para almacenar los resultadods
	$lista_turno = array();
	// forzamos a que el primer valor sea seleccione un turno
	array_push($lista_turno, array('id' => '0', 'valor' => 'Seleccione el turno'));
	// cargamos el resultado de la consulta en el array
	foreach($db_turno as $turno){
		array_push($lista_turno, array('id' => $turno['id_turno'], 'valor' => $turno['turno']));
	}
	// cargamos el array en el bloque_turnos de la plantilla profesional_horario_atencion.tpl
	$tpl->set_loop('bloque_turnos', $lista_turno);
	// consultamos listado de horarios de atencion
	$db_cant = $conexion->GetRow("SELECT Count(*) FROM horario_atencion;");
	$sql = "SELECT * FROM horario_atencion ORDER BY turno_id_turno,id_horario_atencion";
	$db_horarios = $conexion->Execute($sql);
	// creamos un array para almacenar el resultado de la consulta
	$lista_horarios = array();
	// verificamos la existencia de resultados
	if($db_cant["Count(*)"] == 0){
		// no hay resultados para mostrar
		array_push($lista_horarios, array('turno' => "Sin resultados, antes debe registrar algún horario de atención", 'hora_ini' => "",
		'hora_fin' => "", 'id_horario' => "", 'disabled' => "disabled"));
	}else{
		// cargamos el resultado de la consulta en el array
		foreach($db_horarios as $horario){
			$td_id_turno = $horario["turno_id_turno"];
			// consultamos el turno a partir de su ID
			$td_turno = $conexion->GetRow("SELECT turno FROM turno WHERE id_turno='$td_id_turno';");
			array_push($lista_horarios, array('turno' => $td_turno['turno'], 'hora_ini' => $horario['hora_inicio'],
			'hora_fin' => $horario['hora_fin'], 'id_horario' => $horario['id_horario_atencion'], 'disabled' => ''));
		}
	}
	// cargamos el array en el bloque_horario de la plantilla profesional_horario_atencion.tpl
	$tpl->set_loop('bloque_horario', $lista_horarios);
	
	// cargamos el menu principal	
	$tpl->set_var('menu', getMenu());
	// parseamos la plantilla
	$tpl->parse('profesional_horario_atencion');
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>