<?php 
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// verificamos que el acceso sea válido
if(isset($_GET['id'])){
	// acceso válido
	// decodificamos el identificador del plan seleccionado
	$id_plan = base64_decode($_GET['id']);
	// verificamos validez del ID
	$cantidad = $conexion->GetRow("SELECT Count(*) FROM plan_obra_social WHERE id_plan='$id_plan';");
	if($cantidad["Count(*)"] < 1){
		// intento de acceso no válido
		// notificamos el error
		header("location:info.php?id=3");
		// forzamos la detención del script
		die();
	}

	// consultamos los datos del plan
	$plan = $conexion->GetRow("SELECT * FROM plan_obra_social WHERE id_plan='$id_plan';");
	
	// inicializamos la plantilla	
	$tpl = new tpleng;
	$tpl->set_file('ver_datos_plan_obra_social', 'ver_datos_plan_obra_social.tpl');

	// llenamos los campos de la plantilla
	$tpl->set_var('error', "");
	// consultamos el listado de obras sociales
	// esto es por si el usuario desea modificar la obra social asociada al plan
	$db_obra_social = $conexion->Execute("SELECT id_obra_social,nombre FROM obra_social");
	// creamos un array para cargar los datos consultados
	$lista_obra_social = array();
	// cargamos los datos de las obras sociales al array
	foreach($db_obra_social as $obra_social){
	  	if($obra_social['id_obra_social'] == $plan["id_obra_social"]){ 
			  array_push($lista_obra_social, array('id' => $obra_social['id_obra_social'], 'valor' => $obra_social['nombre'], 'selected' => 				'selected=selected'));
	  	} else {
			  array_push($lista_obra_social, array('id' => $obra_social['id_obra_social'], 'valor' => $obra_social['nombre'], 'selected' => ''));
	  	}
  	}
  	// cargamos el listado en el bloque_obra_social del template ver_datos_plan_obra_social.tpl
	$tpl->set_loop('bloque_obra_social', $lista_obra_social);
	// cargamos el resto de las variables de la plantilla
	$tpl->set_var('plan', $plan["plan"]);
	$tpl->set_var('cobertura', $plan["cobertura"]);
	$tpl->set_var('id_plan', $id_plan);
	
	// cargamos el menú principal
	$tpl->set_var('menu', getMenu());
	// parseamos la plantilla
	$tpl->parse('ver_datos_plan_obra_social');

} else {
	// intento de acceso no válido
	header("location:info.php?id=7");
}
?>