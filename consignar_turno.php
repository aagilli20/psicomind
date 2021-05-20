<?php  
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once("validacion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('consignar_turno','consignar_turno.tpl');
// inicializamos contador para la paginación
$cantidad = 0;
// seteamos en blanco los campos para filtrar
$tpl->set_var('filtro_dia', '-1');
$tpl->set_var('filtro_apellido', "");

$volver = false;
// verificamos si proviene de la opción volver de info.php
if(isset($_GET["id1"]) && isset($_GET["id2"])){
	// decodificamos el identificador del paciente
	$id1 = base64_decode($_GET["id1"]);
	// decodificamos el número de matrícula
	$id2 = base64_decode($_GET["id2"]);
	// cantidad de pacientes que coiniciden con el id
	$cant1 = $conexion->GetRow("SELECT Count(*) FROM paciente WHERE id_paciente='$id1'");
	// cantidad de profesionales que coinciden con el número de matrícula
	$cant2 = $conexion->GetRow("SELECT Count(*) FROM profesional WHERE matricula='$id2'");
	if(($cant1["Count(*)"] > 0) && ($cant2["Count(*)"] > 0)) {
		// acceso válido por opción volver de info.php
		$volver = true;
	} else {
		// acceso no válido
		header("location:info.php?id=3");
		// forzamos la detención del script
		die();
	}
}

// verificamos si el ingreso es válido
if(isset($_REQUEST['seleccionar']) || $volver){
	if(! $volver){
		if(empty($_REQUEST["matricula"])){
			// acceso no válido
			// notificamos error
			header("location:info.php?id=3");
			// forzamos la detención del script
			die();
		}
	}
	/*
	
	no sirve
	
	// cargamos la lista de dias
	$db_dia = $conexion->Execute("SELECT * FROM dia");
	$lista_dia = array();
	array_push($lista_dia, array('id' => '-1', 'valor' => "Seleccione un día", 'selected' => ''));
	foreach($db_dia as $dia){
		array_push($lista_dia, array('id' => $dia['id_dia'], 'valor' => $dia['dia'], 'selected' => ''));
	}
	$tpl->set_loop('bloque_dia', $lista_dia);
	// fin dia
	
	*/
	
	// consultamos la cantidad de turnos asignados que tiene el profesional
	if($volver) $matricula = $id2;
	else $matricula = $_REQUEST["matricula"];
	$sql = "SELECT Count(*)	FROM horario_atencion_profesional
				WHERE matricula_profesional='$matricula';";
	$db_cantidad = $conexion->GetRow($sql);
	$cantidad = $db_cantidad["Count(*)"];
	// consultamos el listado de horarios asignados al profesional
	$sql = "SELECT hap.id_horario_atencion,d.valor_dia,ha.hora_inicio,ha.hora_fin,t.valor_turno
				FROM horario_atencion_profesional hap, dia d, horario_atencion ha, turno t
				WHERE hap.matricula_profesional='$matricula'
				AND hap.id_dia=d.id_dia 
				AND hap.id_horario_atencion=ha.id_horario_atencion 
				AND ha.turno_id_turno=t.id_turno
				ORDER BY hap.id_dia,ha.turno_id_turno ASC";
	$db_persona = $conexion->Execute($sql);
	// creamos un array para almacenar los resultados de la consulta
	$lista_persona = array();
	if($cantidad == 0){
		// el profesional no tiene turnos asignados
		array_push($lista_persona, array('dia' => "Sin resultados, asigne un horario de atención al profesional", 'turno' => "",
		'hora_ini' => "", 'hora_fin' => ""));
		$tpl->set_var("sin_horarios", "disabled=disabled");
	}else{
		// cargamos en el array los turnos asignados al profesional
		$tpl->set_var("sin_horarios", "");
		foreach($db_persona as $persona){
			array_push($lista_persona, array('dia' => $persona['valor_dia'], 'turno' => $persona['valor_turno'], 
			'hora_ini' => $persona['hora_inicio'], 'hora_fin' => $persona['hora_fin']));
		}
	}
	// asignamos el array al bloque definido en el template consignar_turno.tpl
	$tpl->set_loop('bloque_persona', $lista_persona);
	if($volver) $id_paciente = $id1;
	else $id_paciente = $_REQUEST["id_paciente"];
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
	$tpl->set_var("id_paciente", $id_paciente);
	$tpl->set_var("id_paciente2", base64_encode($id_paciente));
	$tpl->set_var("id_profesional", $matricula);
	// consultamos la lista de turnos disponibles
	$db_turno = $conexion->Execute("SELECT * FROM turno");
	// creamos un array para almacenar los resultados
	$lista_turno = array();
	$primero = true;
	// cargamos los resultados en el array
	foreach($db_turno as $turno){
		if($primero) {
			array_push($lista_turno, array('id' => $turno['id_turno'], 'valor' => $turno['turno'], 'selected' => 'selected="selected"'));
			$primero = false;	
		}
		else array_push($lista_turno, array('id' => $turno['id_turno'], 'valor' => $turno['turno'], 'selected' => ''));
	}
	// cargamos el listado de turnos en el bloque_turno del template consignar_turno.php
	$tpl->set_loop('bloque_turno', $lista_turno);
	// fin turno
}

// cargamos el menú principal
$tpl->set_var('menu', getMenu());
// parseamos la planitlla
$tpl->parse('consignar_turno');

?>