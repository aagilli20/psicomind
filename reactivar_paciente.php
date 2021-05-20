<?php  
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once("validacion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('reactivar_paciente','reactivar_paciente.tpl');
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
	// consulta base listado de pacientes inactivos
	$sql = "SELECT p.nro_documento,p.nombre_persona,p.apellido_persona,p.sexo_id_sexo,p.tipo_documento_id_tipo_documento,pa.id_paciente 
				FROM persona p, paciente pa
				WHERE p.nro_documento!='root'
				AND pa.activo_paciente='0'
				AND p.nro_documento=pa.persona_nro_documento
				AND p.sexo_id_sexo=pa.persona_sexo_id_sexo
				AND p.tipo_documento_id_tipo_documento=pa.persona_tipo_documento_id_tipo_documento";
	// consulta base cantidad de pacientes inactivos
	$sql_count = "SELECT Count(*) FROM persona p, paciente pa
					WHERE p.nro_documento!='root'
					AND pa.activo_paciente='0'
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
	// la cantidad de resultados
	$db_cant = $conexion->GetRow($sql_count);
	// creamos el array donde almacenaremos los resultados
	$lista_persona = array();
	// verificamos la existencia de resultados
	if($db_cant["Count(*)"] == 0){
		// no existen resultados coincidentes con la búsqueda
		array_push($lista_persona, array('documento' => "Sin resultados, intente una nueva búsqueda", 'apellido' => "",
		'nombre' => "", 'id_persona' => "", 'disabled' => "disabled"));
	} else {
		// cargamos en el array el resultado de la consulta
		foreach($db_persona as $persona){
			array_push($lista_persona, array('documento' => $persona['nro_documento'], 'apellido' => $persona['apellido_persona'],
			'nombre' => $persona['nombre_persona'], 'id_persona' => $persona['id_paciente'], 'disabled' => ''));
		}
	}
	// cargamos el array en el bloque_persona del template reactivar_paciente.tpl
	$tpl->set_loop('bloque_persona', $lista_persona);
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
	$sql = "SELECT Count(*) FROM paciente WHERE persona_nro_documento!='root' AND activo_paciente='0'";
	$db_cantidad = $conexion->GetRow($sql);
	$cantidad = $db_cantidad["Count(*)"];
	// paginacion
	$limite = 10;
	if(isset($_GET["pagina"])){
		$pagina = $_GET['pagina'];
		$inicio = ($pagina - 1) * 10;
		if($pagina > 1){
			$pag_prev = $pagina - 1;
			$tpl->set_var('enlace_pagina_prev', "reactivar_paciente.php?pagina=$pag_prev");
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
			$tpl->set_var('enlace_pagina_prox', "reactivar_paciente.php?pagina=$pag_prox");
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
			$tpl->set_var('enlace_pagina_prox', "reactivar_paciente.php?pagina=$pag_prox");
			$tpl->set_var('pagina_prox', $pag_prox);
		}
	}
	// consutlamos el listado de pacientes inactivos
	$sql = "SELECT p.nro_documento,p.nombre_persona,p.apellido_persona,p.sexo_id_sexo,p.tipo_documento_id_tipo_documento,pa.id_paciente 
				FROM persona p, paciente pa
				WHERE p.nro_documento!='root'
				AND pa.activo_paciente='0'
				AND p.nro_documento=pa.persona_nro_documento
				AND p.sexo_id_sexo=pa.persona_sexo_id_sexo
				AND p.tipo_documento_id_tipo_documento=pa.persona_tipo_documento_id_tipo_documento 
				ORDER BY p.apellido_persona,p.nombre_persona ASC LIMIT $inicio,$limite";
	$db_persona = $conexion->Execute($sql);
	// creamos un array para almacenar los datos
	$lista_persona = array();
	// verificamos la existencia de resultados
	if($cantidad == 0){
		// no hay pacientes inactivos
		array_push($lista_persona, array('documento' => "Sin resultados, no hay pacientes inactivos", 'apellido' => "",
		'nombre' => "", 'id_persona' => "", 'disabled' => "disabled"));
	}else{
		// cargamos los resultados en el array
		foreach($db_persona as $persona){
			array_push($lista_persona, array('documento' => $persona['nro_documento'], 'apellido' => $persona['apellido_persona'],
			'nombre' => $persona['nombre_persona'], 'id_persona' => $persona['id_paciente'], 'disabled' => ''));
		}
	}
	// cargamos el array en el bloque_persona del template reactivar_paciente.tpl
	$tpl->set_loop('bloque_persona', $lista_persona);
}

// cargamos el menú principal
$tpl->set_var('menu', getMenu());
// parseamos la plantilla
$tpl->parse('reactivar_paciente');

?>