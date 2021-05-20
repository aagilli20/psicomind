<?php  
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once("validacion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// si el acceso es correcto, se debería recibir un identificador por POST, con origen en un formulario y un identificador por GET
if((! isset($_GET["id"])) && (! isset($_POST["id_dia"]))){
	// falta alguno de los ID o ambos
	// intento de acceso no válido
	// enviamos mensaje de error
	header("location:info.php?id=2");
	// forzamos la finalización del script
	die();
}

// verificamos que el identificador recibido por GET no sea nulo
if((isset($_GET["id"])) && ($_GET["id"] == "")){
	// no seleccionó ningún día no laboral
	// enviamos mensaje de error en el template info.tpl
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');
	// seteamos las variables
	$tpl->set_var('mensaje', "Debe elegir un día no laboral antes de asociarle algún profesional");
	$tpl->set_var('html_adicional', "");
	$tpl->set_var('enlace_std', "listado_dias_no_laborales.php");
	$tpl->set_var('mensaje_std', "Volver a intentarlo");
	// parseamos la plantilla
	$tpl->parse('info');	
	// forzamos la finalización del script
	die();
}

// si no cayó en los filtros anteriores, entonces el acceso es correcto

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('dnlap','dia_no_laboral_agregar_profesional.tpl');

// inicializamos contador para la paginación del listado de profesionales
$cantidad = 0;

// si presiono filtrar se verifica que haya ingresado algún filtro
$filtro = false;
if(isset($_REQUEST['filtrar'])){
	if(!(empty($_REQUEST["apellido"])) || ($_REQUEST["apellido"]!="Apellido")) $filtro = true;
	if(!(empty($_REQUEST["documento"])) || ($_REQUEST["documento"]!="Número de Documento")) $filtro = true;
}

// verificamos si ingreso por el botón filtrar
if(isset($_REQUEST['filtrar']) && $filtro){
	// recuperamos los campos del filtro dia_no_laboral_agregar_profesional.tpl
	$tpl->set_var('documento', $_REQUEST["documento"]);
	$tpl->set_var('apellido', $_REQUEST["apellido"]);
	// consulta base
	$sql = "SELECT p.nro_documento,p.nombre_persona,p.apellido_persona,p.sexo_id_sexo,p.tipo_documento_id_tipo_documento,pr.matricula 
				FROM persona p, profesional pr
				WHERE nro_documento!='root'
				AND pr.activo_profesional='1'
				AND p.nro_documento=pr.persona_nro_documento
				AND p.sexo_id_sexo=pr.persona_sexo_id_sexo
				AND p.tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento";
	$sql_count = "SELECT Count(*) FROM persona p, profesional pr
					WHERE nro_documento!='root'
					AND pr.activo_profesional='1'
					AND p.nro_documento=pr.persona_nro_documento
					AND p.sexo_id_sexo=pr.persona_sexo_id_sexo
					AND p.tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento";
	// agregamos el filtro a la consulta base
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
	// verificamos la cantidad de resultados
	$db_cant = $conexion->GetRow($sql_count);
	// creamos el array donde almacenaremos los resultados
	$lista_persona = array();
	if($db_cant["Count(*)"] == 0){
		// si no hay resultados coincidentes con la búsqueda lo informamos
		array_push($lista_persona, array('documento' => "Sin resultados, intente una nueva búsqueda o registre el profesional", 'apellido' => "",
		'nombre' => "", 'id_persona' => "", 'disabled' => "disabled"));
	} else {
		// en otro caso, se muestran los resultados correspondientes
		foreach($db_persona as $persona){
			array_push($lista_persona, array('documento' => $persona['nro_documento'], 'apellido' => $persona['apellido_persona'],
			'nombre' => $persona['nombre_persona'], 'id_persona' => $persona['matricula'], 'disabled' => ''));
		}
	}
	// cargamos el arreglo con los resultados en bloque_persona dentro del template
	// dia_no_laboral_agregar_profesional.tpl
	$tpl->set_loop('bloque_persona', $lista_persona);
	// paginacion
	$tpl->set_var('enlace_pagina_prev', "#");
	$tpl->set_var('pagina_prev', "#");
	$tpl->set_var('pagina', "1");
	$tpl->set_var('enlace_pagina_prox', "#");
	$tpl->set_var('pagina_prox', "#");
	// identificador del día no laboral elegido
	$id_dia_no_laboral = $_POST["id_dia"];
	// a partir del identificador obtenemos la fecha correspondiente
	$fecha = $conexion->GetRow("SELECT fecha FROM dia_no_laboral WHERE id_dia_no_laboral='$id_dia_no_laboral';");
	// completamos dos variables que nos permiten mantener el día elegido por el usuario
	$tpl->set_var('id_dia_no_laboral', $id_dia_no_laboral);
	$tpl->set_var('fecha_no_laboral', $fecha["fecha"]);
} else {
	// mostramos el listado sin filtrar
	$tpl->set_var('documento', "");
	$tpl->set_var('apellido', "");
	// cantidad de datos
	$sql = "SELECT Count(*) FROM profesional WHERE persona_nro_documento!='root'";
	$db_cantidad = $conexion->GetRow($sql);
	$cantidad = $db_cantidad["Count(*)"];
	// paginacion
	$limite = 10;
	if(isset($_GET["pagina"])){
		$pagina = $_GET['pagina'];
		$inicio = ($pagina - 1) * 10;
		if($pagina > 1){
			$pag_prev = $pagina - 1;
			$tpl->set_var('enlace_pagina_prev', "dia_no_laboral_agregar_profesional.php?pagina=$pag_prev");
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
			$tpl->set_var('enlace_pagina_prox', "dia_no_laboral_agregar_profesional.php?pagina=$pag_prox");
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
			$tpl->set_var('enlace_pagina_prox', "dia_no_laboral_agregar_profesional.php?pagina=$pag_prox");
			$tpl->set_var('pagina_prox', $pag_prox);
		}
	}
	// consultamos el listado de profesionales
	$sql = "SELECT p.nro_documento,p.nombre_persona,p.apellido_persona,p.sexo_id_sexo,p.tipo_documento_id_tipo_documento,pr.matricula 
				FROM persona p, profesional pr
				WHERE nro_documento!='root'
				AND pr.activo_profesional='1'
				AND p.nro_documento=pr.persona_nro_documento
				AND p.sexo_id_sexo=pr.persona_sexo_id_sexo
				AND p.tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento 
				ORDER BY p.apellido_persona,p.nombre_persona ASC LIMIT $inicio,$limite";
	// ejecutamos la consulta
	$db_persona = $conexion->Execute($sql);
	// creamos un array para almacenar la consulta
	$lista_persona = array();
	if($cantidad == 0){
		// si la consulta no arrojó resultados
		array_push($lista_persona, array('documento' => "Sin resultados, antes debe registrar algún profesional", 'apellido' => "",
		'nombre' => "", 'id_persona' => "", 'disabled' => "disabled"));
	}else{
		foreach($db_persona as $persona){
			// si la consulta arrojó resultados los cargamos
			array_push($lista_persona, array('documento' => $persona['nro_documento'], 'apellido' => $persona['apellido_persona'],
			'nombre' => $persona['nombre_persona'], 'id_persona' => $persona['matricula'], 'disabled' => ''));
		}
	}
	// cargamos el listado en el bloque_persona dentro del template
	// dia_no_laboral_agregar_profesional.tpl
	$tpl->set_loop('bloque_persona', $lista_persona);
	// identificador de la fecha elegida
	$id_dia_no_laboral = base64_decode($_GET["id"]);
	// a partir del identificador obtener la fecha seleccionada por el usuario
	$fecha = $conexion->GetRow("SELECT fecha FROM dia_no_laboral WHERE id_dia_no_laboral='$id_dia_no_laboral';");
	// utilizamos variables para mantener la fecha seleccionada por el usuario
	$tpl->set_var('id_dia_no_laboral', $id_dia_no_laboral);
	$tpl->set_var('fecha_no_laboral', $fecha["fecha"]);
	
}

// cargamos el menú principal en la página
$tpl->set_var('menu', getMenu());
// no se muestran mendajes de error
$tpl->set_var('error', "");
// parseamos la plantilla
$tpl->parse('dnlap');

?>