<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");

// verificamos que el acceso provenga del botón reactivar
if(isset($_REQUEST['reactivar'])){
	// leemos los campos del formulario y los cargamos en un array
	$form = array();
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	// verificamos qeu el ID del usuario no sea nulo
	if(empty($_REQUEST["id_persona"])){
		// id nulo
		// inicializamos la plantilla de información
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// seteamos las variables
		// notificar error
		$tpl->set_var('mensaje', "Debe seleccionar el usuario que desea reactivar");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "reactivar_usuario.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
		// forzamos la detención del script
		die();
	}
	// obtenemos el identificador del usuario
	$id_usuario = $form["id_persona"];
	// inicializamos la plantilla de información
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');
	// seteamos las variables
	$sql = "UPDATE usuario SET activo_usuario='1' WHERE id_usuario='$id_usuario'"; 
	// verificamos el resultado del update
	if($conexion->Execute($sql)){
		// notificar que las especialidades fueron cargadas
		$tpl->set_var('mensaje', "El usuario ha sido reactivado");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "index.php");
		$tpl->set_var('mensaje_std', "Volver al inicio");
		// parseamos la plantilla
		$tpl->parse('info');
	}else{
		// notificar error
		$tpl->set_var('mensaje', "Error al reactivar el usuario, intentelo nuevamente");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "reactivar_usuario.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
	}
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>