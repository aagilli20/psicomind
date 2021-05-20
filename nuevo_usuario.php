<?php  
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once("validacion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('nuevo_usuario','nuevo_usuario.tpl');

// inicializamos contador para la paginación de resultados
$cantidad = 0;

// si presiono filtrar se verifica que haya ingresado algún filtro
$filtro = false;
if(isset($_REQUEST['filtrar'])){
	if(!(empty($_REQUEST["apellido"])) || ($_REQUEST["apellido"]!="Apellido")) $filtro = true;
	if(!(empty($_REQUEST["documento"])) || ($_REQUEST["documento"]!="Número de Documento")) $filtro = true;
}

// verificamos si ingreso por el botón filtrar e ingreso algún filtro
if(isset($_REQUEST['filtrar']) && $filtro){
	// filtramos la consulta
	$tpl->set_var('documento', $_REQUEST["documento"]);
	$tpl->set_var('apellido', $_REQUEST["apellido"]);
	// consulta base para obtener el listado de personas
	$sql = "SELECT nro_documento,nombre_persona,apellido_persona,sexo_id_sexo,tipo_documento_id_tipo_documento FROM persona
		WHERE nro_documento!='root'";
	// consulta base para obtener la cantidad de resultados
	$sql_count = "SELECT Count(*) FROM persona WHERE nro_documento!='root'";
	// agregamos el filtro a las consultas base
	if(is_alphanumeric($_REQUEST["documento"], $min_length = 1, $max_length = 16) && ($_REQUEST["documento"]!="Número de Documento")){
		$nro_doc = "%".$_REQUEST["documento"]."%";
		$sql = $sql." AND nro_documento LIKE '$nro_doc'";
		$sql_count = $sql_count." AND nro_documento LIKE '$nro_doc'";
	}
	if(is_alphanumeric($_REQUEST["apellido"], $min_length = 1, $max_length = 40) && ($_REQUEST["apellido"]!="Apellido")){
		$ape = "%".$_REQUEST["apellido"]."%";
		$sql = $sql." AND apellido_persona LIKE '$ape'";
		$sql_count = $sql_count." AND apellido_persona LIKE '$ape'";
	}
	// fin agregar filtro
	// ordenamos la consulta
	$sql = $sql." ORDER BY apellido_persona,nombre_persona ASC";
	// ejecutamos las consulta
	$db_persona = $conexion->Execute($sql);
	$db_cant = $conexion->GetRow($sql_count);
	// creamos el array donde almacenaremos los resultados
	$lista_persona = array();
	// verificamos la cantidad de resultados
	if($db_cant["Count(*)"] == 0){
		// no hay resultados coincidentes con la búsqueda
		array_push($lista_persona, array('documento' => "Sin resultados, intente una nueva búsqueda o registre la persona", 'apellido' => "",
		'nombre' => "", 'id_persona' => "", 'disabled' => "disabled"));
	} else {
		// cargamos el array con los resultados de la consulta
		foreach($db_persona as $persona){
			$id_persona = $persona['sexo_id_sexo'].$persona['tipo_documento_id_tipo_documento'].$persona['nro_documento'];
			array_push($lista_persona, array('documento' => $persona['nro_documento'], 'apellido' => $persona['apellido_persona'],
			'nombre' => $persona['nombre_persona'], 'id_persona' => $id_persona, 'disabled' => ''));
		}
	}
	// cargamos el array en el bloque_persona de la plantilla nuevo_usuario.tpl
	$tpl->set_loop('bloque_persona', $lista_persona);
	// paginacion
	$tpl->set_var('enlace_pagina_prev', "#");
	$tpl->set_var('pagina_prev', "#");
	$tpl->set_var('pagina', "1");
	$tpl->set_var('enlace_pagina_prox', "#");
	$tpl->set_var('pagina_prox', "#");
} else {
	// mostramos el listado de personas sin filtrar
	$tpl->set_var('documento', "");
	$tpl->set_var('apellido', "");
	// cantidad de datos
	$sql = "SELECT Count(*) FROM persona
		WHERE nro_documento!='root'";
	$db_cantidad = $conexion->GetRow($sql);
	$cantidad = $db_cantidad["Count(*)"];
	// paginacion
	$limite = 10;
	if(isset($_GET["pagina"])){
		$pagina = $_GET['pagina'];
		$inicio = ($pagina - 1) * 10;
		if($pagina > 1){
			$pag_prev = $pagina - 1;
			$tpl->set_var('enlace_pagina_prev', "nuevo_usuario.php?pagina=$pag_prev");
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
			$tpl->set_var('enlace_pagina_prox', "nuevo_usuario.php?pagina=$pag_prox");
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
			$tpl->set_var('enlace_pagina_prox', "nuevo_usuario.php?pagina=$pag_prox");
			$tpl->set_var('pagina_prox', $pag_prox);
		}
	}
	// obtenemos el listado de personas
	$sql = "SELECT nro_documento,nombre_persona,apellido_persona,sexo_id_sexo,tipo_documento_id_tipo_documento FROM persona
		WHERE nro_documento!='root' ORDER BY apellido_persona,nombre_persona ASC LIMIT $inicio,$limite";
	$db_persona = $conexion->Execute($sql);
	// creamos un array para almacenar los resultados
	$lista_persona = array();
	// verificamos la existencia de resultados a mostrar
	if($cantidad == 0){
		// no existen resultados
		array_push($lista_persona, array('documento' => "Sin resultados, intente una nueva búsqueda o registre la persona", 'apellido' => "",
		'nombre' => "", 'id_persona' => "", 'disabled' => "disabled"));
	}else{
		// cargamso en el array el resultado de la consulta
		foreach($db_persona as $persona){
			$id_persona = $persona['sexo_id_sexo'].$persona['tipo_documento_id_tipo_documento'].$persona['nro_documento'];
			array_push($lista_persona, array('documento' => $persona['nro_documento'], 'apellido' => $persona['apellido_persona'],
			'nombre' => $persona['nombre_persona'], 'id_persona' => $id_persona, 'disabled' => ''));
		}
	}
	// cargamos en el array en el bloque_persona de la plantilla nuevo_usuario.tpl
	$tpl->set_loop('bloque_persona', $lista_persona);
	
}

// cargamos el menú principal
$tpl->set_var('menu', getMenu());
// parseamos la plantilla
$tpl->parse('nuevo_usuario');

?>