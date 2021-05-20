<?php 
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('nueva_persona', 'nueva_persona.tpl');

// seteamos las variables de la plantilla
$tpl->set_var('error', "");
// consulamos la lista de tipos de documento
$db_tipo_doc = $conexion->Execute("SELECT * FROM tipo_documento");
// creamos un array para almacenar el resultado de la consulta
$lista_tipo_doc = array();
// forzamos a que el primer valor sea seleccione un tipo de documento
array_push($lista_tipo_doc, array('id' => '0', 'valor' => 'Seleccione el Tipo de Documento', 'selected' => ''));
// llenamos el array con los resultados de la consulta
foreach($db_tipo_doc as $tipo_doc){
	array_push($lista_tipo_doc, array('id' => $tipo_doc['id_tipo_documento'], 'valor' => $tipo_doc['valor_tipo_documento'], 'selected' => ''));
}
// cargamos el array en el bloque_tipo_doc de la plantilla nueva_persona.tpl
$tpl->set_loop('bloque_tipo_doc', $lista_tipo_doc);
// fin lista tipo documento
$tpl->set_var('documento', "");
// consultados el listado de sexos
$db_sexo = $conexion->Execute("SELECT * FROM sexo");
// creamos un array para almacenar los resultados de la consulta
$lista_sexo = array();
// forzamos a que el primer valor del combo sea seleccione el sexo
array_push($lista_sexo, array('id' => '0', 'valor' => 'Seleccione el Sexo', 'selected' => ''));
// llenamos el array con el resultado de la consulta
foreach($db_sexo as $sexo){
	array_push($lista_sexo, array('id' => $sexo['id_sexo'], 'valor' => $sexo['sexo'], 'selected' => ''));
}
$tpl->set_loop('bloque_sexo', $lista_sexo);
// fin lista sexo
$tpl->set_var('fenac', "");
$tpl->set_var('nombre', "");
$tpl->set_var('apellido', "");
$tpl->set_var('telefono', "");
$tpl->set_var('celular', "");
$tpl->set_var('email', "");
$tpl->set_var('domicilio', "");

// cargamos el menu principal
$tpl->set_var('menu', getMenu());

// parseamos la plantilla
$tpl->parse('nueva_persona');

?>