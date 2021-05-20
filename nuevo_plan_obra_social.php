<?php 
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('nuevo_plan_obra_social', 'nuevo_plan_obra_social.tpl');

// llenamos los campos de la plantilla
$tpl->set_var('error', "");
// consultamos el listado de obras sociales
$db_obra_social = $conexion->Execute("SELECT id_obra_social,nombre FROM obra_social");
// creamos un array para almacenar los resultados
$lista_obra_social = array();
// forzamos a que el primer valor sea seleccione una obra social
array_push($lista_obra_social, array('id' => '0', 'valor' => 'Seleccione una obra social', 'selected' => ''));
// llenamos el array con el resultado de la consulta
foreach($db_obra_social as $obra_social){
	array_push($lista_obra_social, array('id' => $obra_social['id_obra_social'], 'valor' => $obra_social['nombre'], 'selected' => ''));
}
// cargamos el array en el bloque_obra_social de la plantilla nuevo_plan_obra_social.tpl 
$tpl->set_loop('bloque_obra_social', $lista_obra_social);
// cargamos el listado de obras sociales
$tpl->set_var('plan', "");
$tpl->set_var('cobertura', "");

// cargamos el menu principal
$tpl->set_var('menu', getMenu());
// parseamos la plantilla
$tpl->parse('nuevo_plan_obra_social');

?>