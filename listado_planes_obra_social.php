<?php  
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once("validacion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('listado_planes_obra_social','listado_planes_obra_social.tpl');
// inicializamos contador
$cantidad = 0;

// verificamos que se reciba por GET el identificador del plan
if(isset($_GET['id'])){
	// verificamos que el id recibido no sea nulo
	if($_GET["id"] == ""){
		// id nulo
		// inicializamos la plantilla de información para notificar el error
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// seteamos las variables
		$tpl->set_var('mensaje', "Debe elegir una Obra Social válida");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "listado_obras_sociales.php");
		$tpl->set_var('mensaje_std', "Volver a intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
		// forzamos la denteción del script	
		die();
	}
	// hay que decodificar el id
	$id_obra_social = base64_decode($_GET['id']);
	// mostramos el listado sin filtrar
	$obra_social = $conexion->GetRow("SELECT nombre FROM obra_social WHERE id_obra_social='$id_obra_social';");
	$tpl->set_var('nombre_obra_social', $obra_social["nombre"]);
	// cantidad de datos
	$sql = "SELECT Count(*) FROM plan_obra_social WHERE id_obra_social='$id_obra_social';";
	$db_cantidad = $conexion->GetRow($sql);
	$cantidad = $db_cantidad["Count(*)"];
	// consultamos el listado de planes
	$sql = "SELECT id_plan,plan,cobertura FROM plan_obra_social WHERE id_obra_social='$id_obra_social' ORDER BY id_plan ASC";
	$db_plan = $conexion->Execute($sql);
	// creamos un array para almacenar los resultados de la consulta
	$lista_plan = array();
	// verificamos la existencia de resultados
	if($cantidad == 0){
		// no hay resultados para mostrar
		array_push($lista_plan, array('id_plan' => "No existe ningún Plan asociado a la Obra Social seleccionada", 'plan' => "",
		'cobertura' => "", 'id_plan2' => ""));
	}else{
		// cargamos el resultado de la consulta en el array
		foreach($db_plan as $plan){
			array_push($lista_plan, array('id_plan' => $plan['id_plan'], 'plan' => $plan['plan'],
			'cobertura' => $plan['cobertura'], 'id_plan2' => base64_encode($plan['id_plan'])));
		}
	}
	// cargamos el array en el bloque_plan_obra_social de la plantilla listado_planes_obra_social.tpl
	$tpl->set_loop('bloque_plan_obra_social', $lista_plan);
	// fin lista plan
	
// cargamos el menu principal
$tpl->set_var('menu', getMenu());

// parseamos la plantilla
$tpl->parse('listado_planes_obra_social');

}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>