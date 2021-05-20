<?php  
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once("validacion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('epto','elegir_paciente_turno_optimo.tpl');

// inicializamos contador para la paginación
$cantidad = 0;

// si presiono filtrar se verifica que haya ingresado algún filtro
$filtro = false;
if(isset($_REQUEST['filtrar'])){
	if(!(empty($_REQUEST["apellido"])) || ($_REQUEST["apellido"]!="Apellido")) $filtro = true;
	if(!(empty($_REQUEST["documento"])) || ($_REQUEST["documento"]!="Número de Documento")) $filtro = true;
}

// verificamos si ingreso por el botón filtrar
if(isset($_REQUEST['filtrar']) && $filtro){
	// filtramos la consulta
	$tpl->set_var('documento', $_REQUEST["documento"]);
	$tpl->set_var('apellido', $_REQUEST["apellido"]);
	// consulta base del listado de personas
	$sql = "SELECT p.nro_documento,p.nombre_persona,p.apellido_persona,p.sexo_id_sexo,p.tipo_documento_id_tipo_documento,pa.id_paciente 
				FROM persona p, paciente pa
				WHERE p.nro_documento!='root'
				AND pa.activo_paciente='1'
				AND p.nro_documento=pa.persona_nro_documento
				AND p.sexo_id_sexo=pa.persona_sexo_id_sexo
				AND p.tipo_documento_id_tipo_documento=pa.persona_tipo_documento_id_tipo_documento";
	// consulta base para saber la cantidad de resultados
	$sql_count = "SELECT Count(*) FROM persona p, paciente pa
					WHERE p.nro_documento!='root'
					AND pa.activo_paciente='1'
					AND p.nro_documento=pa.persona_nro_documento
					AND p.sexo_id_sexo=pa.persona_sexo_id_sexo
					AND p.tipo_documento_id_tipo_documento=pa.persona_tipo_documento_id_tipo_documento";
	// agregamos los filtros
	if(is_alphanumeric($_REQUEST["documento"], $min_length = 1, $max_length = 16) && ($_REQUEST["documento"]!="Número de Documento")){
		$nro_doc = "%".$_REQUEST["documento"]."%";
		$sql = $sql." AND p.nro_documento LIKE '$nro_doc'";
		$sql_count = $sql_count." AND p.nro_documento LIKE '$nro_doc'";
	}
	if(is_alphanumeric($_REQUEST["apellido"], $min_length = 1, $max_length = 40) && ($_REQUEST["apellido"]!="Apellido")){
		$ape = "%".$_REQUEST["apellido"]."%";
		$sql = $sql." AND p.apellido_persona LIKE '$ape'";
		$sql_count = $sql_count." AND p.apellido_persona LIKE '$ape'";
	}
	// fin agregar filtro
	// ordenamos la consulta
	$sql = $sql." ORDER BY p.apellido_persona,p.nombre_persona ASC";
	// ejecutamos la consulta
	$db_persona = $conexion->Execute($sql);
	// obtenemos la cantidad de resultados
	$db_cant = $conexion->GetRow($sql_count);
	// creamos el array donde almacenaremos los resultados
	$lista_persona = array();
	// verificamos la existencia de resultados
	if($db_cant["Count(*)"] == 0){
		// no hay resultados coincidentes con la búsqueda
		array_push($lista_persona, array('documento' => "Sin resultados, intente una nueva búsqueda o registre el paciente", 'apellido' => "",
		'nombre' => "", 'id_persona' => "", 'disabled' => "disabled"));
	} else {
		foreach($db_persona as $persona){
			// cargamos los resultados de la consulta en el array
			array_push($lista_persona, array('documento' => $persona['nro_documento'], 'apellido' => $persona['apellido_persona'],
			'nombre' => $persona['nombre_persona'], 'id_persona' => $persona['id_paciente'], 'disabled' => ''));
		}
	}
	// cargamos el array en el bloque_persona de la plantilla elegir_paciente_consignar_turno.tpl
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
	$tpl->set_var('documento', "");
	$tpl->set_var('apellido', "");
	// cantidad de datos
	$sql = "SELECT Count(*) FROM paciente WHERE persona_nro_documento!='root'";
	$db_cantidad = $conexion->GetRow($sql);
	$cantidad = $db_cantidad["Count(*)"];
	// paginacion
	$limite = 10;
	if(isset($_GET["pagina"])){
		$pagina = $_GET['pagina'];
		$inicio = ($pagina - 1) * 10;
		if($pagina > 1){
			$pag_prev = $pagina - 1;
			$tpl->set_var('enlace_pagina_prev', "elegir_paciente_turno_optimo.php?pagina=$pag_prev");
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
			$tpl->set_var('enlace_pagina_prox', "elegir_paciente_turno_optimo.php?pagina=$pag_prox");
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
			$tpl->set_var('enlace_pagina_prox', "elegir_paciente_turno_optimo.php?pagina=$pag_prox");
			$tpl->set_var('pagina_prox', $pag_prox);
		}
	}
	// consultamos el listado de personas
	$sql = "SELECT p.nro_documento,p.nombre_persona,p.apellido_persona,p.sexo_id_sexo,p.tipo_documento_id_tipo_documento,pa.id_paciente 
				FROM persona p, paciente pa
				WHERE p.nro_documento!='root'
				AND pa.activo_paciente='1'
				AND p.nro_documento=pa.persona_nro_documento
				AND p.sexo_id_sexo=pa.persona_sexo_id_sexo
				AND p.tipo_documento_id_tipo_documento=pa.persona_tipo_documento_id_tipo_documento 
				ORDER BY p.apellido_persona,p.nombre_persona ASC LIMIT $inicio,$limite";
	$db_persona = $conexion->Execute($sql);
	// creamos un array para almacenar los resultados de la consulta
	$lista_persona = array();
	// verificamos la existencia de resultados
	if($cantidad == 0){
		// no hay resultados para mostrar
		array_push($lista_persona, array('documento' => "Sin resultados, antes debe registrar algún paciente", 'apellido' => "",
		'nombre' => "", 'id_persona' => "", 'disabled' => "disabled"));
	}else{
		// cargamos los resultados de la consulta en el array
		foreach($db_persona as $persona){
			array_push($lista_persona, array('documento' => $persona['nro_documento'], 'apellido' => $persona['apellido_persona'],
			'nombre' => $persona['nombre_persona'], 'id_persona' => $persona['id_paciente'], 'disabled' => ''));
		}
	}
	// cargamos el array en el bloque_persona de la plantilla elegir_paciente_consignar_turno.tpl
	$tpl->set_loop('bloque_persona', $lista_persona);
	// fin lista persona
	
}

// cargamos el menú principal
$tpl->set_var('menu', getMenu());
// parseamos la plantilla
$tpl->parse('epto');

?>