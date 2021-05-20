<?php 
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once('tpleng.class.php');
require_once("menu.php");

if(isset($_GET['id'])){
	// acceso válido
	// decodificamos el identificador del plan seleccionado
	$id_obra_social = base64_decode($_GET['id']);
	// verificamos validez del ID
	$cantidad = $conexion->GetRow("SELECT Count(*) FROM obra_social WHERE id_obra_social='$id_obra_social';");
	if($cantidad["Count(*)"] < 1){
		// intento de acceso no válido
		// notificamos el error
		header("location:info.php?id=7");
		// forzamos la detención del script
		die();
	}

	// consultamos los datos del plan
	$obra_social = $conexion->GetRow("SELECT * FROM obra_social WHERE id_obra_social='$id_obra_social';");
	
// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('ver_datos_obra_social', 'ver_datos_obra_social.tpl');

// llenamos los campos de la plantilla
$tpl->set_var('error', "");

$tpl->set_var('id_os', $id_obra_social);
$tpl->set_var('nombre', $obra_social["nombre"]);
$tpl->set_var('rnemp', $obra_social["rnemp"]);
$tpl->set_var('telefono', $obra_social["telefono"]);
$tpl->set_var('direccion', $obra_social["direccion"]);
$tpl->set_var('localidad', $obra_social["localidad"]);
$tpl->set_var('provincia', $obra_social["provincia"]);
$tpl->set_var('codigo_postal', $obra_social["cp"]);

// cargamos el menu principal
$tpl->set_var('menu', getMenu());

// parseamos la plantilla
$tpl->parse('ver_datos_obra_social');
} else {
	// intento de acceso no válido
	header("location:info.php?id=7");
}

?>