<?php  
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once("validacion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('horarios_atencion_asignados','horarios_atencion_asignados.tpl');
// inicializamos contador para la paginación
$cantidad = 0;
// seteamos las variables por defecto de la plantilla 
$tpl->set_var('filtro_dia', '-1');
$tpl->set_var('filtro_apellido', "");

// si presiono filtrar se verifica que haya ingresado algún filtro
$filtro = false;
if(isset($_REQUEST['filtrar'])){
	if(!(empty($_REQUEST["apellido"])) || ($_REQUEST["apellido"]!="Apellido")) $filtro = true;
	if($_REQUEST["select_dia"] != '-1') $filtro = true;
}

// verificamos si ingreso por el botón filtrar
if(isset($_REQUEST['filtrar']) && $filtro){
	// filtramos la consulta
	$tpl->set_var('apellido', $_REQUEST["apellido"]);
	$id_dia = $_REQUEST["select_dia"];
	// consultamos la lista de dias
	$db_dia = $conexion->Execute("SELECT * FROM dia");
	// creamos un array para almacenar los resultados de la consulta
	$lista_dia = array();
	// forzamos a que el primer valor del combo sea seleccione un día
	array_push($lista_dia, array('id' => '-1', 'valor' => "Seleccione un día", 'selected' => ''));
	// cargamos el resultado de la consulta en el array
	foreach($db_dia as $dia){
		if($dia['id_dia'] == $id_dia) {
			array_push($lista_dia, array('id' => $dia['id_dia'], 'valor' => $dia['dia'], 'selected' => 'selected="selected"'));
		} else array_push($lista_dia, array('id' => $dia['id_dia'], 'valor' => $dia['dia'], 'selected' => ''));
	}
	// cargamos el array en el bloque_dia de la plantilla horarios_atencion_asignados.tpl
	$tpl->set_loop('bloque_dia', $lista_dia);
	// fin dia
	// consulta base del listado de personas
	$sql = "SELECT p.nombre_persona,p.apellido_persona,hap.id_horario_atencion,d.valor_dia,ha.hora_inicio,ha.hora_fin,t.valor_turno
				FROM persona p, profesional pr, horario_atencion_profesional hap, dia d, horario_atencion ha, turno t
				WHERE nro_documento!='root'
				AND pr.activo_profesional='1'
				AND p.nro_documento=pr.persona_nro_documento
				AND p.sexo_id_sexo=pr.persona_sexo_id_sexo
				AND p.tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento 
				AND pr.matricula=hap.matricula_profesional 
				AND hap.id_dia=d.id_dia 
				AND hap.id_horario_atencion=ha.id_horario_atencion 
				AND ha.turno_id_turno=t.id_turno";
	// consulta base para obtener la cantidad
	$sql_count = "SELECT Count(*)
				FROM persona p, profesional pr, horario_atencion_profesional hap, dia d, horario_atencion ha, turno t
				WHERE nro_documento!='root'
				AND pr.activo_profesional='1'
				AND p.nro_documento=pr.persona_nro_documento
				AND p.sexo_id_sexo=pr.persona_sexo_id_sexo
				AND p.tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento 
				AND pr.matricula=hap.matricula_profesional 
				AND hap.id_dia=d.id_dia 
				AND hap.id_horario_atencion=ha.id_horario_atencion 
				AND ha.turno_id_turno=t.id_turno";
	// agregamos los filtros
	if($id_dia != -1){
		$sql = $sql." AND hap.id_dia='$id_dia'";
		$sql_count = $sql_count." AND hap.id_dia='$id_dia'";
		$tpl->set_var('filtro_dia', $id_dia);
	}
	if(is_alphanumeric($_REQUEST["apellido"], $min_length = 1, $max_length = 40) && ($_REQUEST["apellido"]!="Apellido")){
		$ape = "%".$_REQUEST["apellido"]."%";
		$sql = $sql." AND p.apellido_persona LIKE '$ape'";
		$sql_count = $sql_count." AND p.apellido_persona LIKE '$ape'";
		$tpl->set_var('filtro_apellido', $_REQUEST["apellido"]);
	}
	// fin agregar filtro
	// ordenamos la consulta
	$sql = $sql." ORDER BY p.apellido_persona,p.nombre_persona,hap.id_dia,ha.turno_id_turno ASC";
	// ejecutamos la consulta
	$db_persona = $conexion->Execute($sql);
	// obtenemos la cantidad de resultados
	$db_cant = $conexion->GetRow($sql_count);
	// creamos el array donde almacenaremos los resultados
	$lista_persona = array();
	// verificamos la existencia de resultados
	if($db_cant["Count(*)"] == 0){
		// no hay resultados a mostrar
		array_push($lista_persona, array('apellido' => "Sin resultados, antes debe registrar algún profesional", 'dia' => "", 'turno' => "",
		'hora_ini' => "", 'hora_fin' => ""));
	} else {
		// cargamos el resultado de la consutla en el array
		foreach($db_persona as $persona){
			$nombre = $persona['apellido_persona'].", ".$persona['nombre_persona'];
			array_push($lista_persona, array('apellido' => $nombre, 'dia' => $persona['valor_dia'], 'turno' => $persona['valor_turno'], 
			'hora_ini' => $persona['hora_inicio'], 'hora_fin' => $persona['hora_fin']));
		}
	}
	// cargamos el array en el bloque_persona de la plantilla horarios_atencion_asignados.tpl
	$tpl->set_loop('bloque_persona', $lista_persona);
	// fin lista persona
	// paginacion
	$tpl->set_var('enlace_pagina_prev', "#");
	$tpl->set_var('pagina_prev', "#");
	$tpl->set_var('pagina', "1");
	$tpl->set_var('enlace_pagina_prox', "#");
	$tpl->set_var('pagina_prox', "#");
} else {
	// mostramos el listado sin filtrar
	// consultamos la lista de dias
	$db_dia = $conexion->Execute("SELECT * FROM dia");
	// creamos un array para almacenar el resultado de la consulta
	$lista_dia = array();
	// forzamos a que el primer valor del combo sea seleccione un dia
	array_push($lista_dia, array('id' => '-1', 'valor' => "Seleccione un día", 'selected' => ''));
	// cargamos el resultado de la consulta en el array
	foreach($db_dia as $dia){
		array_push($lista_dia, array('id' => $dia['id_dia'], 'valor' => $dia['dia'], 'selected' => ''));
	}
	// cargamos el array en el bloque_dia de la plantilla horarios_atencion_asignados.tpl
	$tpl->set_loop('bloque_dia', $lista_dia);
	// fin dia
	$tpl->set_var('apellido', "");
	// cantidad de datos
	$sql = "SELECT Count(*)
				FROM persona p, profesional pr, horario_atencion_profesional hap, dia d, horario_atencion ha, turno t
				WHERE nro_documento!='root'
				AND pr.activo_profesional='1'
				AND p.nro_documento=pr.persona_nro_documento
				AND p.sexo_id_sexo=pr.persona_sexo_id_sexo
				AND p.tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento 
				AND pr.matricula=hap.matricula_profesional 
				AND hap.id_dia=d.id_dia 
				AND hap.id_horario_atencion=ha.id_horario_atencion 
				AND ha.turno_id_turno=t.id_turno;";
	$db_cantidad = $conexion->GetRow($sql);
	$cantidad = $db_cantidad["Count(*)"];
	// paginacion
	$limite = 10;
	if(isset($_GET["pagina"])){
		$pagina = $_GET['pagina'];
		$inicio = ($pagina - 1) * 10;
		if($pagina > 1){
			$pag_prev = $pagina - 1;
			$tpl->set_var('enlace_pagina_prev', "horarios_atencion_asignados.php?pagina=$pag_prev");
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
			$tpl->set_var('enlace_pagina_prox', "horarios_atencion_asignados.php?pagina=$pag_prox");
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
			$tpl->set_var('enlace_pagina_prox', "horarios_atencion_asignados.php?pagina=$pag_prox");
			$tpl->set_var('pagina_prox', $pag_prox);
		}
	}
	// consultamos el listado de personas
	$sql = "SELECT p.nombre_persona,p.apellido_persona,hap.id_horario_atencion,d.valor_dia,ha.hora_inicio,ha.hora_fin,t.valor_turno
				FROM persona p, profesional pr, horario_atencion_profesional hap, dia d, horario_atencion ha, turno t
				WHERE nro_documento!='root'
				AND pr.activo_profesional='1'
				AND p.nro_documento=pr.persona_nro_documento
				AND p.sexo_id_sexo=pr.persona_sexo_id_sexo
				AND p.tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento 
				AND pr.matricula=hap.matricula_profesional 
				AND hap.id_dia=d.id_dia 
				AND hap.id_horario_atencion=ha.id_horario_atencion 
				AND ha.turno_id_turno=t.id_turno
				ORDER BY p.apellido_persona,p.nombre_persona,hap.id_dia,ha.turno_id_turno ASC LIMIT $inicio,$limite";
	$db_persona = $conexion->Execute($sql);
	// creamos un array para almacenar el resultado de la consulta
	$lista_persona = array();
	// verificamos la existencia de resultados
	if($cantidad == 0){
		// no hay resultados a mostrar
		array_push($lista_persona, array('apellido' => "Sin resultados, antes debe registrar algún profesional", 'dia' => "", 'turno' => "",
		'hora_ini' => "", 'hora_fin' => ""));
	}else{
		// cargamos el resultado de la consulta en el array
		foreach($db_persona as $persona){
			$nombre = $persona['apellido_persona'].", ".$persona['nombre_persona'];
			array_push($lista_persona, array('apellido' => $nombre, 'dia' => $persona['valor_dia'], 'turno' => $persona['valor_turno'], 
			'hora_ini' => $persona['hora_inicio'], 'hora_fin' => $persona['hora_fin']));
		}
	}
	// cargamos el array en el bloque_persona de la plantilla horarios_atencion_asignados.tpl
	$tpl->set_loop('bloque_persona', $lista_persona);
	// fin lista persona
	
}

// cargamos el menú principal
$tpl->set_var('menu', getMenu());
// parseamos la planitlla
$tpl->parse('horarios_atencion_asignados');

?>