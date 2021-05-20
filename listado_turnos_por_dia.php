<?php  
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once("validacion.php");
require_once('tpleng.class.php');
require_once("fechas.php");
require_once("menu.php");

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('listado_turnos_por_dia','listado_turnos_por_dia.tpl');

// verificar que haya ingresado a traves del botón seleccionar
if($_REQUEST["seleccionar"]){
	// ingresóo por el botón seleccionar
	// leemos los datos del formulario
	// leemos todos los datos del formulario y los almacenamos en un array
	$form = array();
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	// verificamos que haya seleccionado un profesional
	if(empty($_REQUEST["matricula"])){
		// no ha seleccionado ningún profesional
		// notificamos error
		header("location:info.php?id=3");
		// forzamos la detención del script
		die();
	}
	if((! fecha_valida_futura($form["fecha"])) || ($form["fecha"] == "DD/MM/AAAA")) {
		// no ha seleccionado ningún profesional
		// notificamos error
		header("location:info.php?id=7");
		// forzamos la detención del script
		die();
	}
	$matricula = $form["matricula"];
	// consultamos el nombre y apellido del profesional seleccionado
	$sql = "SELECT p.nombre_persona, p.apellido_persona FROM persona p, profesional pr 
	WHERE p.nro_documento=pr.persona_nro_documento AND p.sexo_id_sexo=pr.persona_sexo_id_sexo
	AND p.tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento
	AND pr.matricula='$matricula';";
	$db_profesional = $conexion->GetRow($sql);
	// seteamos el nombre del profesional en el template
	$tpl->set_var("nombre_profesional", $db_profesional["apellido_persona"].", ".$db_profesional["nombre_persona"]);
	// seteamos la fecha en el template
	$tpl->set_var("dia_turno",$form["fecha"]);
	// consultamos el listado de turnos asociados al profesional en el día elegido
	// creamos un array para almacenar los resultados de la consulta
	$lista_turnos = array();
	// consultamos el listado de turnos asociados al profesional en el día elegido
	$fecha_mysql = fecha_mysql($form["fecha"]);
	$sql = "SELECT id_turno_otorgado,horario,id_paciente,id_turno FROM turno_otorgado 
			WHERE fecha_turno='$fecha_mysql' AND id_profesional LIKE '$matricula'";
	$db_turnos = $conexion->Execute($sql);
	$sql = "SELECT Count(*) FROM turno_otorgado WHERE fecha_turno='$fecha_mysql' AND id_profesional LIKE '$matricula'";
	$cantidad = $conexion->GetRow($sql);
	// verificamos la existencia de resultados
	if($cantidad["Count(*)"] == 0){
		// no hay turnos para mostrar
		array_push($lista_turnos, array('horario' => "", 'turno' => "",
		'paciente' => "No hay turnos asociados al profesional en el día elegido", 'id_turno_otorgado' => ""));	
	} else {
		// cargamos el listado de turnos
		foreach($db_turnos as $un_turno){
			// consultamos el nombre del turno
			$id_turno = $un_turno["id_turno"];
			$turno = $conexion->GetRow("SELECT turno FROM turno WHERE id_turno='$id_turno';");
			//consultamos el nombre del paciente
			$id_paciente = $un_turno["id_paciente"];
			$sql = "SELECT p.nombre_persona, p.apellido_persona FROM persona p, paciente pa 
			WHERE p.nro_documento=pa.persona_nro_documento AND p.sexo_id_sexo=pa.persona_sexo_id_sexo
			AND p.tipo_documento_id_tipo_documento=pa.persona_tipo_documento_id_tipo_documento
			AND pa.id_paciente='$id_paciente';";
			$db_paciente = $conexion->GetRow($sql);
			array_push($lista_turnos, array('horario' => $un_turno["horario"], 'turno' => $turno["turno"],
			'paciente' => $db_paciente["nombre_persona"]." ".$db_paciente["apellido_persona"], 
			'id_turno_otorgado' => $un_turno["id_turno_otorgado"]));
		}
	}
	$tpl->set_loop('bloque_turnos', $lista_turnos);
	// fin lista turnos otorgados
	$tpl->set_var('error', "");
	// cargamos el menu principal
	$tpl->set_var('menu', getMenu());
	// parseamos la plantilla
	$tpl->parse('listado_turnos_por_dia');
} else {
	// intento de acceso no autorizado	
	header("location:info.php?id=2");
}

?>