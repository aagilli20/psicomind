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
$tpl->set_file('listado_dias_no_laborales','listado_dias_no_laborales.tpl');
// inicializamos contador
$cantidad = 0;

// verificamos si ingreso por el botón filtrar
if(isset($_REQUEST['filtrar'])){
	// consultamos el listado de días no laborales y la cantidad de resultados
	$sql = "SELECT * FROM dia_no_laboral ORDER BY fecha ASC;";
	$sql_count = "SELECT Count(*) FROM dia_no_laboral;";
	$db_dia_no_laboral = $conexion->Execute($sql);
	$db_cant = $conexion->GetRow($sql_count);
	// creamos un array para almacenar el listado de resultados
	$lista_dias = array();
	// verificamos la cantidad de resultados
	if($db_cant["Count(*)"] == 0){
		// no hay resultados para mostrar
		array_push($lista_dias, array('fecha' => "Sin resultados, intente una nueva búsqueda o registre el día no laboral", 'motivo' => "",
		'id_dia_no_laboral' => ""));
	}else{
		// cargamso el resultado de la consulta en el array
		foreach($db_dia_no_laboral as $dia){
			$id_dia_no_laboral = $dia["id_dia_no_laboral"];
			$fecha = $dia["fecha"];
			$motivo = $dia["motivo"];
			array_push($lista_dias, array('fecha' => fecha_normal($fecha), 'motivo' => $motivo, 
			'id_dia_no_laboral' => base64_encode($id_dia_no_laboral)));
		}
	}
	// cargamos el array en el bloque_dias de la plantilla listado_dias_no_laborales.tpl
	$tpl->set_loop('bloque_dias', $lista_dias);
	// fin lista dia no laboral
} else {
	// mostramos el listado sin filtrar
	// consultamos el listado de días no laborales y la cantidad
	$sql = "SELECT * FROM dia_no_laboral WHERE fecha>=CURDATE() ORDER BY fecha ASC;";
	$sql_count = "SELECT Count(*) FROM dia_no_laboral WHERE fecha>=CURDATE();";
	$db_dia_no_laboral = $conexion->Execute($sql);
	$db_cant = $conexion->GetRow($sql_count);
	// creamos un array para almacenar los resultados de la consulta
	$lista_dias = array();
	// verificamos la existencia de resultados
	if($db_cant["Count(*)"] == 0){
		// no hay resultados para mostrar
		array_push($lista_dias, array('fecha' => "Sin resultados, intente una nueva búsqueda o registre el día no laboral", 'motivo' => "",
		'id_dia_no_laboral' => ""));
	}else{
		// cargamos el resultado de la consulta en el array
		foreach($db_dia_no_laboral as $dia){
			$id_dia_no_laboral = $dia["id_dia_no_laboral"];
			$fecha = $dia["fecha"];
			$motivo = $dia["motivo"];
			array_push($lista_dias, array('fecha' => fecha_normal($fecha), 'motivo' => $motivo, 
			'id_dia_no_laboral' => base64_encode($id_dia_no_laboral)));
		}
	}
	// cargamos el array en el bloque_dias de la plantilla listado_dias_no_laborales.tpl
	$tpl->set_loop('bloque_dias', $lista_dias);
	// fin lista dia no laboral
	
}
$tpl->set_var('error', "");
// cargamos el menu principal
$tpl->set_var('menu', getMenu());
// parseamos la plantilla
$tpl->parse('listado_dias_no_laborales');

?>