<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");

// verificamos que el acceso provenga del botón reactivar
if(isset($_REQUEST['reactivar'])){
	// leemos los campos del formulario y los gaurdamos en un array
	$form = array();
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	// verificamos que haya elegido un profesional
	if(empty($_REQUEST["matricula"])){
		// no seleccionó ningún profesional
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// seteamos las variables
		// notificar error
		$tpl->set_var('mensaje', "Debe seleccionar el profesional a reactivar");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "reactivar_profesional.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
		// forzamos la detención del script
		die();
	}
	
	// obtenemos el numero de matricula
	$matricula = $form["matricula"];
	
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');
	$sql = "UPDATE profesional SET activo_profesional='1' WHERE matricula='$matricula'"; 
	// verificamos el resultado del update
	if($conexion->Execute($sql)){
		// notificar que las especialidades fueron cargadas
		$tpl->set_var('mensaje', "El profesional fue reactivado");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "index.php");
		$tpl->set_var('mensaje_std', "Volver al inicio");
		// parseamos la plantilla
		$tpl->parse('info');
	}else{
		// notificar error
		$tpl->set_var('mensaje', "Error al reactivar el profesional, intentelo nuevamente");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "reactivar_profesional.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
	}
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>