<?php  
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once("validacion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// verificamos que se haya recibido por GET el identificador del día no laboral
if(! isset($_GET["id"])){
	// intento de acceso no válido
	header("location:info.php?id=2");
	// forzamos la denteción del script
	die();
}

// verificamos que el identificador recibido no sea nulo
if($_GET["id"] == ""){
	// id nulo, por lo tanto, lo informamos como error
	// inicializamos la plantilla de información
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');
	// seteamos las variables
	$tpl->set_var('mensaje', "Debe elegir un día no laboral antes de asociarle algún profesional");
	$tpl->set_var('html_adicional', "");
	$tpl->set_var('enlace_std', "listado_dias_no_laborales.php");
	$tpl->set_var('mensaje_std', "Volver a intentarlo");
	// parseamos la plantilla
	$tpl->parse('info');	
	// forzamos la detención del script
	die();
}

// como hasta aquí no se detectaron errores, ya podemos inicializar la plantilla
$tpl = new tpleng;
$tpl->set_file('dnlqp','dia_no_laboral_quitar_profesional.tpl');

// inicializamos un contador para la paginación
$cantidad = 0;

// mostramos el listado sin filtrar
$tpl->set_var('documento', "");
$tpl->set_var('apellido', "");
// decodificamos el identificador recibido por get
$id_aux = base64_decode($_GET["id"]);
// obtenemos la cantidad de resultados
$sql = "SELECT Count(*) FROM profesional p,profesional_dia_no_laboral d WHERE p.persona_nro_documento!='root'
		AND p.matricula=d.matricula AND d.id_dia_no_laboral='$id_aux';";
$db_cantidad = $conexion->GetRow($sql);
$cantidad = $db_cantidad["Count(*)"];
// paginacion
$limite = 10;
if(isset($_GET["pagina"])){
	$pagina = $_GET['pagina'];
	$inicio = ($pagina - 1) * 10;
	if($pagina > 1){
		$pag_prev = $pagina - 1;
		$tpl->set_var('enlace_pagina_prev', "dia_no_laboral_quitar_profesional.php?pagina=$pag_prev");
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
		$tpl->set_var('enlace_pagina_prox', "dia_no_laboral_quitar_profesional.php?pagina=$pag_prox");
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
		$tpl->set_var('enlace_pagina_prox', "dia_no_laboral_quitar_profesional.php?pagina=$pag_prox");
		$tpl->set_var('pagina_prox', $pag_prox);
	}
}
// consultamos el listado de personas
$sql = "SELECT p.nro_documento,p.nombre_persona,p.apellido_persona,p.sexo_id_sexo,p.tipo_documento_id_tipo_documento,pr.matricula 
			FROM persona p, profesional pr, profesional_dia_no_laboral d
			WHERE nro_documento!='root'
			AND pr.activo_profesional='1'
			AND p.nro_documento=pr.persona_nro_documento
			AND p.sexo_id_sexo=pr.persona_sexo_id_sexo
			AND p.tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento 
			AND pr.matricula=d.matricula
			AND d.id_dia_no_laboral='$id_aux'
			ORDER BY p.apellido_persona,p.nombre_persona ASC LIMIT $inicio,$limite";
$db_persona = $conexion->Execute($sql);
// creamos un array para almacenar los resultados
$lista_persona = array();
// verificamos la existencia de resultados
if($cantidad == 0){
	// no hay resultados a mostrar
	array_push($lista_persona, array('documento' => "Sin resultados, antes debe registrar algún profesional", 'apellido' => "",
	'nombre' => "", 'id_persona' => "", 'disabled' => "disabled"));
}else{
	// cargamos los resultados de la consulta en el array
	foreach($db_persona as $persona){
		array_push($lista_persona, array('documento' => $persona['nro_documento'], 'apellido' => $persona['apellido_persona'],
		'nombre' => $persona['nombre_persona'], 'id_persona' => $persona['matricula'], 'disabled' => ''));
	}
}
// cargamos el array en el bloque_persona de la plantilla dia_no_laboral_quitar_profesional.tpl
$tpl->set_loop('bloque_persona', $lista_persona);
// fin lista persona
// seteamos el resto de las variables de la plantilla
$id_dia_no_laboral = base64_decode($_GET["id"]);
$fecha = $conexion->GetRow("SELECT fecha FROM dia_no_laboral WHERE id_dia_no_laboral='$id_dia_no_laboral';");
$tpl->set_var('id_dia_no_laboral', $id_dia_no_laboral);
$tpl->set_var('fecha_no_laboral', $fecha["fecha"]);
	
// cargamos el menu principal
$tpl->set_var('menu', getMenu());
$tpl->set_var('error', "");
// parseamos la plantilla
$tpl->parse('dnlqp');

?>