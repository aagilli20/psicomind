<?php 
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// verificamos si ingresó por el botón filtrar
if(isset($_REQUEST['filtrar'])){
	// ingresar por el botón filtrar
	// recuperamos el turno seleccionado
	$seleccion_turno = $_REQUEST['select_turno'];
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('profesional_horario_atencion', 'profesional_horario_atencion.tpl');
	// leemos el id del profesional del formulario
	$matricula = $_REQUEST["matricula"];
	// llenamos los campos de la plantilla
	$tpl->set_var('matricula', $matricula);
	// consultar nombre y apellido del profesional
	$persona = $conexion->GetRow("SELECT p.nombre_persona,p.apellido_persona FROM persona p,profesional pr 
										WHERE p.sexo_id_sexo=pr.persona_sexo_id_sexo 
										AND tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento 
										AND nro_documento=pr.persona_nro_documento
										AND pr.matricula='$matricula'");
	$tpl->set_var('error', ""); 
	$tpl->set_var('nombre', $persona['nombre_persona']);
	$tpl->set_var('apellido', $persona['apellido_persona']);
	
	// cargamos listado de horarios de atencion actuales
	// cantidad de horarios de atención asociados al profesional
	$db_cant = $conexion->GetRow("SELECT Count(*) FROM horario_atencion_profesional WHERE matricula_profesional='$matricula';");
	// listado de horarios de atención asociados al profesional
	$sql = "SELECT * FROM horario_atencion_profesional WHERE matricula_profesional='$matricula';";
	$db_horarios_act = $conexion->Execute($sql);
	// creamos un array para almacenar los datos de la consulta
	$lista_horarios_act = array();
	// verificamos que existan registros a mostrar
	if($db_cant["Count(*)"] == 0){
		// no hay horarios de atención asociados al profesional
		array_push($lista_horarios_act, array('dia' => "El profesional no tiene horarios de atención asignados", 'turno' => "", 'hora_ini' => "",
		'hora_fin' => "", 'id_horario' => ""));
	}else{
		// cargamos en el array el listado de los horarios de atención asociados al profesional
		foreach($db_horarios_act as $horario){
			$td_id_horario_atencion = $horario["id_horario_atencion"];
			$td_id_dia = $horario["id_dia"];
			// consultamos a que día apunta el identificador de día registrado para el horario de atención
			$td_dia = $conexion->GetRow("SELECT dia FROM dia WHERE id_dia='$td_id_dia';");
			// consulta todos los datos del identificador de horario de atención asociado al profesional
			$row_horario_atencion = $conexion->GetRow("SELECT * FROM horario_atencion WHERE id_horario_atencion='$td_id_horario_atencion';");
			$td_id_turno = $row_horario_atencion["turno_id_turno"];
			// consultamos a que turno pertenece el identificador de turno asociado al profesional
			$td_turno = $conexion->GetRow("SELECT turno FROM turno WHERE id_turno='$td_id_turno';");
			// se utiliza para identificar cada de forma única cada horario de atención, dado que podría ser seleccionado
			$td_id_horario = "prof=".base64_encode($matricula)."&hora=".base64_encode($td_id_horario_atencion)."&dia=".base64_encode($td_id_dia);
			array_push($lista_horarios_act, array('dia' => $td_dia['dia'],'turno' => $td_turno['turno'], 
			'hora_ini' => $row_horario_atencion['hora_inicio'], 'hora_fin' => $row_horario_atencion['hora_fin'], 'id_horario' => $td_id_horario));
		}
	}
	// cargamos el arreglo en el bloque_horario_actual en la plantilla profesional_horario_atencion.tpl
	$tpl->set_loop('bloque_horario_actual', $lista_horarios_act);
	
	// consultamos el listado de turnos disponibles
	$db_turno = $conexion->Execute("SELECT * FROM turno");
	// creamos un array para almacenar la consulta
	$lista_turno = array();
	// para que el primer registro muestre la opción seleccione un turno
	array_push($lista_turno, array('id' => '0', 'valor' => 'Seleccione el turno'));
	// llenamos el array con el resultado de la conculta
	foreach($db_turno as $turno){
		array_push($lista_turno, array('id' => $turno['id_turno'], 'valor' => $turno['turno']));
	}
	// cargamos el array en el bloque_turnos de la plantilla profesional_horario_atencion.tpl
	$tpl->set_loop('bloque_turnos', $lista_turno);
	// cargamos listado de horarios de atencion disponibles
	// verificamos si seleccionó un turno para filtrar
	if($seleccion_turno > 0){
		// filtrado por turno
		$db_cant = $conexion->GetRow("SELECT Count(*) FROM horario_atencion WHERE turno_id_turno='$seleccion_turno'");
		$sql = "SELECT * FROM horario_atencion WHERE turno_id_turno='$seleccion_turno' ORDER BY id_horario_atencion;";
		$db_horarios = $conexion->Execute($sql);
	} else {
		// sin filtros
		$db_cant = $conexion->GetRow("SELECT Count(*) FROM horario_atencion;");
		$sql = "SELECT * FROM horario_atencion ORDER BY turno_id_turno,id_horario_atencion;";
		$db_horarios = $conexion->Execute($sql);
	}
	// creamos un array para almacenar los horarios de atención disponibles
	$lista_horarios = array();
	// verificamos la existencia de resultados
	if($db_cant["Count(*)"] == 0){
		// no hay ningún turno de atención registrado
		array_push($lista_persona, array('turno' => "Sin resultados, antes debe registrar algún horario de atención", 'hora_ini' => "",
		'hora_fin' => "", 'id_horario' => "", 'disabled' => "disabled"));
	}else{
		// llenamos el arary con los horarios de atención registrados
		foreach($db_horarios as $horario){
			$td_id_turno = $horario["turno_id_turno"];
			$td_turno = $conexion->GetRow("SELECT turno FROM turno WHERE id_turno='$td_id_turno';");
			array_push($lista_horarios, array('turno' => $td_turno['turno'], 'hora_ini' => $horario['hora_inicio'],
			'hora_fin' => $horario['hora_fin'], 'id_horario' => $horario['id_horario_atencion'], 'disabled' => ''));
		}
	}
	// cargamos el array en el bloque_horario de la plantilla profesional_horario_atencion.tpl
	$tpl->set_loop('bloque_horario', $lista_horarios);
	// fin listado de horarios de atencion
	
	// cargamos el menú principal
	$tpl->set_var('menu', getMenu());
	// parseamos la plantilla
	$tpl->parse('profesional_horario_atencion');
	// forzamos la finalzación del script
	die();
}

// verificamos si ingresó por el botón seleccionar del PASO 1
if(isset($_REQUEST['seleccionar'])){
	// ingresó por el botón seleccionar
	// verificamos que haya elegido una persona
	if(empty($_REQUEST["matricula"])){
		// no seleccionó ningún profesional en el paso 1
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// seteamos las variables
		// notificar error
		$tpl->set_var('mensaje', "Antes de asociar una especialidad debe seleccionar un profesional");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "elegir_profesional_especialidad.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
		// forzamos la detención del script
		die();
	}
	// acceso correcto desde el paso 1
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('profesional_horario_atencion', 'profesional_horario_atencion.tpl');
	// leemos el id persona del formulario
	$matricula = $_REQUEST["matricula"];
	// llenamos los campos de la plantilla
	$tpl->set_var('matricula', $matricula);
	// consultar nombre y apellido del profesional seleccionado
	$persona = $conexion->GetRow("SELECT p.nombre_persona,p.apellido_persona FROM persona p,profesional pr 
										WHERE p.sexo_id_sexo=pr.persona_sexo_id_sexo 
										AND tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento 
										AND nro_documento=pr.persona_nro_documento
										AND pr.matricula='$matricula'");
	$tpl->set_var('error', ""); 
	$tpl->set_var('nombre', $persona['nombre_persona']);
	$tpl->set_var('apellido', $persona['apellido_persona']);
	
	// cantidad de horarios de atención asociados al profesional
	$db_cant = $conexion->GetRow("SELECT Count(*) FROM horario_atencion_profesional WHERE matricula_profesional='$matricula';");
	// cargamos listado de horarios de atencion actuales asociados al profesional
	$sql = "SELECT * FROM horario_atencion_profesional WHERE matricula_profesional='$matricula';";
	$db_horarios_act = $conexion->Execute($sql);
	// creamos array para almacenar el resultado de la consulta
	$lista_horarios_act = array();
	// verificamos la existencia de resultados
	if($db_cant["Count(*)"] == 0){
		// el profesional no tiene horarios de atención asociados
		array_push($lista_horarios_act, array('dia' => "El profesional no tiene horarios de atención asignados", 'turno' => "", 'hora_ini' => "",
		'hora_fin' => "", 'id_horario' => ""));
	}else{
		// llenamos el array con los horarios de atención asociados al profesional
		foreach($db_horarios_act as $horario){
			$td_id_horario_atencion = $horario["id_horario_atencion"];
			$td_id_dia = $horario["id_dia"];
			// obtenemos el nombre del día a partir de su identificador
			$td_dia = $conexion->GetRow("SELECT dia FROM dia WHERE id_dia='$td_id_dia';");
			$row_horario_atencion = $conexion->GetRow("SELECT * FROM horario_atencion WHERE id_horario_atencion='$td_id_horario_atencion';");
			$td_id_turno = $row_horario_atencion["turno_id_turno"];
			$td_turno = $conexion->GetRow("SELECT turno FROM turno WHERE id_turno='$td_id_turno';");
			// creamos un identificador para poder detectar que horario elige el usuario en el formulario
			$td_id_horario = "prof=".base64_encode($matricula)."&hora=".base64_encode($td_id_horario_atencion)."&dia=".base64_encode($td_id_dia);
			array_push($lista_horarios_act, array('dia' => $td_dia['dia'],'turno' => $td_turno['turno'], 'hora_ini' => $row_horario_atencion['hora_inicio'],
			'hora_fin' => $row_horario_atencion['hora_fin'], 'id_horario' => $td_id_horario));
		}
	}
	// cargamos el array en el bloque_horario_actual de la plantilla profesional_horario_atencion.tpl
	$tpl->set_loop('bloque_horario_actual', $lista_horarios_act);
	// fin lista horarios de atencion actuales
	
	// consultamos el listado de turnos disponibles
	$db_turno = $conexion->Execute("SELECT * FROM turno");
	// creamos un array para almacenar la lista de turnos
	$lista_turno = array();
	// hacemos que el primer valor sea seleccione un turno
	array_push($lista_turno, array('id' => '0', 'valor' => 'Seleccione el turno'));
	// llenamos el array con el resultado de la consulta
	foreach($db_turno as $turno){
		array_push($lista_turno, array('id' => $turno['id_turno'], 'valor' => $turno['turno']));
	}
	// cargamos el array en el bloque_turnos del la plantilla profesional_horario_atencion.tpl
	$tpl->set_loop('bloque_turnos', $lista_turno);
	// cargamos listado de horarios de atencion disponibles
	// consultamos la cantidad de horarios de atención disponibles
	$db_cant = $conexion->GetRow("SELECT Count(*) FROM horario_atencion;");
	// consultamos el listado de horarios de atencion disponibles
	$sql = "SELECT * FROM horario_atencion ORDER BY turno_id_turno,id_horario_atencion";
	$db_horarios = $conexion->Execute($sql);
	// cramos un array para almacenar la consulta
	$lista_horarios = array();
	// verificamos la existencia de resultados
	if($db_cant["Count(*)"] == 0){
		// no hay turnos registrados
		array_push($lista_horarios, array('turno' => "Sin resultados, antes debe registrar algún horario de atención", 'hora_ini' => "",
		'hora_fin' => "", 'id_horario' => "", 'disabled' => "disabled"));
	}else{
		// llenamos el array con el listado de horarios de atención registrados
		foreach($db_horarios as $horario){
			$td_id_turno = $horario["turno_id_turno"];
			// consultamos el turno del horario de atención a partir de su identificador
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
} else {
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>