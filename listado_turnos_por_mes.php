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
$tpl->set_file('listado_turnos_por_mes','listado_turnos_por_mes.tpl');

// limite para la paginación
$limite = 10;

// verificar que haya ingresado a traves del botón seleccionar
if(isset($_REQUEST["seleccionar"])){
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
	$matricula = $form["matricula"];
	// verificamos que haya seleccionado un mes y un año
	if(($form["select_mes"] == 0) || ($form["select_anio"] == 0)){
		// no ha seleccionado ningún profesional
		// notificamos error
		header("location:info.php?id=8");
		// forzamos la detención del script
		die();
	}
	// consultamos el nombre y apellido del profesional seleccionado
	$sql = "SELECT p.nombre_persona, p.apellido_persona FROM persona p, profesional pr 
	WHERE p.nro_documento=pr.persona_nro_documento AND p.sexo_id_sexo=pr.persona_sexo_id_sexo
	AND p.tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento
	AND pr.matricula='$matricula';";
	$db_profesional = $conexion->GetRow($sql);
	// seteamos el nombre del profesional en el template
	$tpl->set_var("nombre_profesional", $db_profesional["apellido_persona"].", ".$db_profesional["nombre_persona"]);
	// consultamos el listado de turnos asociados al profesional en el día elegido
	// creamos un array para almacenar los resultados de la consulta
	$lista_turnos = array();
	// consultamos el listado de turnos asociados al profesional en el día elegido
	$mes_anio = $form["select_anio"]."-".$form["select_mes"]."-%%";
	$sql = "SELECT id_turno_otorgado,horario,id_paciente,id_turno,fecha_turno FROM turno_otorgado 
			WHERE fecha_turno LIKE '$mes_anio' AND id_profesional LIKE '$matricula' ORDER BY fecha_turno LIMIT 0,$limite";
	$db_turnos = $conexion->Execute($sql);
	$sql = "SELECT Count(*) FROM turno_otorgado WHERE fecha_turno LIKE '$mes_anio' AND id_profesional LIKE '$matricula'";
	$cantidad = $conexion->GetRow($sql);
	// verificamos la existencia de resultados
	if($cantidad["Count(*)"] == 0){
		// no hay turnos para mostrar
		array_push($lista_turnos, array('horario' => "", 'turno' => "", 'dia' => "",
		'paciente' => "No hay turnos asociados al profesional en el día elegido", 'id_turno_otorgado' => ""));
		$tpl->set_var('enlace_pagina_prev', "#");
		$tpl->set_var('pagina_prev', "#");
		$tpl->set_var('pagina', "1");
		$tpl->set_var('enlace_pagina_prox', "#");
		$tpl->set_var('pagina_prox', "#");
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
			'dia' => fecha_normal($un_turno["fecha_turno"]), 'paciente' => $db_paciente["nombre_persona"]." ".$db_paciente["apellido_persona"], 
			'id_turno_otorgado' => $un_turno["id_turno_otorgado"]));
		}	
		// configuramos los enlaces para la paginación
		$tpl->set_var('enlace_pagina_prev', "#");
		$tpl->set_var('pagina_prev', "#");
		$tpl->set_var('pagina', "1");
		if($cantidad["Count(*)"] < ($limite+1)){
			$tpl->set_var('enlace_pagina_prox', "#");
			$tpl->set_var('pagina_prox', "#");
		} else {
			$pag_prox = 2;
			$tpl->set_var('enlace_pagina_prox', "listado_turnos_por_mes.php?pagina=$pag_prox&ma=$mes_anio&id=$matricula");
			$tpl->set_var('pagina_prox', $pag_prox);
		}
	}
	$tpl->set_loop('bloque_turnos', $lista_turnos);
	// fin lista turnos otorgados
	$tpl->set_var('error', "");
	// cargamos el menu principal
	$tpl->set_var('menu', getMenu());
	// parseamos la plantilla
	$tpl->parse('listado_turnos_por_mes');
} else {
	// ingreso por la paginacion, hay que obtener los datos por GET
	// el acceso por get es mas inseguro, hay que hacer más chequeos
	if(! isset($_GET["id"])){
		// se ha eliminado el número de matricula
		// notificamos el error
		header("location:info.php?id=7");
		// forzamos la detención del script
		die();	
	} else {
		// verificamos que el id no haya sido modificado
		$matricula = $_GET["id"];
		$cant = $conexion->GetRow("SELECT Count(*) FROM profesional WHERE matricula='$matricula';");	
		if($cant["Count(*)"] == 0){
			// el numero de matricula ha sido modificado
			// notificamos error
			header("location:info.php?id=7");
			// forzamos la detención del script
			die();	
		}
	}
	// consultamos el nombre y apellido del profesional seleccionado
	$sql = "SELECT p.nombre_persona, p.apellido_persona FROM persona p, profesional pr 
	WHERE p.nro_documento=pr.persona_nro_documento AND p.sexo_id_sexo=pr.persona_sexo_id_sexo
	AND p.tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento
	AND pr.matricula='$matricula';";
	$db_profesional = $conexion->GetRow($sql);
	// seteamos el nombre del profesional en el template
	$tpl->set_var("nombre_profesional", $db_profesional["apellido_persona"].", ".$db_profesional["nombre_persona"]);
	// consultamos el listado de turnos asociados al profesional en el día elegido
	// hacemos la consulta para la pagina solicitada
	$pagina_elegida = $_GET["pagina"];
	// creamos un array para almacenar los resultados de la consulta
	$lista_turnos = array();
	$mes_anio = $_GET["ma"];
	// cantidad de resultados
		$sql = "SELECT Count(*) FROM turno_otorgado WHERE fecha_turno LIKE '$mes_anio' AND id_profesional LIKE '$matricula'";
	$db_cant = $conexion->GetRow($sql);
	$cantidad = $db_cant["Count(*)"];
	// paginacion
	$limite = 10;
	if(isset($_GET["pagina"])){
		$pagina = $_GET['pagina'];
		$inicio = ($pagina - 1) * 10;
		if($pagina > 1){
			$pag_prev = $pagina - 1;
			$tpl->set_var('enlace_pagina_prev', "listado_turnos_por_mes.php?pagina=$pag_prev&ma=$mes_anio&id=$matricula");
			$tpl->set_var('pagina_prev', $pag_prev);
		} else {
			$tpl->set_var('enlace_pagina_prev', "#");
			$tpl->set_var('pagina_prev', "#");
		}
		$tpl->set_var('pagina', $pagina);
		$ini_prox = $pagina * 10;
		if($ini_prox > $cantidad){
			$tpl->set_var('enlace_pagina_prox', "#");
			$tpl->set_var('pagina_prox', "#");
		} else {
			$pag_prox = $pagina + 1;
			$tpl->set_var('enlace_pagina_prox', "listado_turnos_por_mes.php?pagina=$pag_prox&ma=$mes_anio&id=$matricula");
			$tpl->set_var('pagina_prox', $pag_prox);
		}
	} else {
		$inicio = 0;
		$tpl->set_var('enlace_pagina_prev', "#");
		$tpl->set_var('pagina_prev', "#");
		$tpl->set_var('pagina', "1");
		$ini_prox = 10;
		if($ini_prox > $cantidad){
			$tpl->set_var('enlace_pagina_prox', "#");
			$tpl->set_var('pagina_prox', "#");
		} else {
			$pag_prox = 2;
			$tpl->set_var('enlace_pagina_prox', "listado_turnos_por_mes.php?pagina=$pag_prox&ma=$mes_anio&id=$matricula");
			$tpl->set_var('pagina_prox', $pag_prox);
		}
	}
	// consultamos el listado de turnos asociados al profesional en el día elegido
	$sql = "SELECT id_turno_otorgado,horario,id_paciente,id_turno,fecha_turno FROM turno_otorgado 
			WHERE fecha_turno LIKE '$mes_anio' AND id_profesional LIKE '$matricula' ORDER BY fecha_turno LIMIT $inicio,$limite";
	$db_turnos = $conexion->Execute($sql);
	// verificamos la existencia de resultados
	if($cantidad == 0){
		// no hay turnos para mostrar
		array_push($lista_turnos, array('horario' => "", 'turno' => "", 'dia' => "",
		'paciente' => "No hay turnos asociados al profesional en el día elegido", 'id_turno_otorgado' => ""));
		$tpl->set_var('enlace_pagina_prev', "#");
		$tpl->set_var('pagina_prev', "#");
		$tpl->set_var('pagina', "1");
		$tpl->set_var('enlace_pagina_prox', "#");
		$tpl->set_var('pagina_prox', "#");
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
			'dia' => fecha_normal($un_turno["fecha_turno"]), 'paciente' => $db_paciente["nombre_persona"]." ".$db_paciente["apellido_persona"], 
			'id_turno_otorgado' => $un_turno["id_turno_otorgado"]));
		}	
	}
	$tpl->set_loop('bloque_turnos', $lista_turnos);
	// fin lista turnos otorgados
	$tpl->set_var('error', "");
	// cargamos el menu principal
	$tpl->set_var('menu', getMenu());
	// parseamos la plantilla
	$tpl->parse('listado_turnos_por_mes');
}

?>