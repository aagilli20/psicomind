<?php  
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once("validacion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('listado_obras_sociales','listado_obras_sociales.tpl');
// inicializamos contador
$cantidad = 0;

// mostramos el listado sin filtrar
// cantidad de datos
$sql = "SELECT Count(*) FROM obra_social";
$db_cantidad = $conexion->GetRow($sql);
$cantidad = $db_cantidad["Count(*)"];
// consultamos el listado de obras sociales
$sql = "SELECT id_obra_social,rnemp,nombre,telefono,direccion,localidad	FROM obra_social ORDER BY nombre ASC";
$db_obra_social = $conexion->Execute($sql);
// creamos un array para almacenar los resultados de la consulta
$lista_obra_social = array();
// verificamos la existencia de resultados
if($cantidad == 0){
	// no hay resultados a mostrar
	array_push($lista_obra_social, array('rnemp' => "No existe ninguna Obra Social", 'nombre' => "",
	'telefono' => "", 'direccion' => "", 'localidad' => ""));
}else{
	// cargamos los resultados de la consulta en el array
	foreach($db_obra_social as $obra_social){
		array_push($lista_obra_social, array('rnemp' => $obra_social['rnemp'], 'nombre' => $obra_social['nombre'],
		'telefono' => $obra_social['telefono'], 'direccion' => $obra_social['direccion'], 'localidad' => $obra_social['localidad'],
		'id_obra_social' => base64_encode($obra_social['id_obra_social'])));
	}
}
// cargamos el array en el bloque_obra_social de la plantilla listado_obras_sociales.tpl
$tpl->set_loop('bloque_obra_social', $lista_obra_social);
// fin lista obra social
	
// cargamos el menu principal
$tpl->set_var('menu', getMenu());

// parseamos la plantilla
$tpl->parse('listado_obras_sociales');

?>