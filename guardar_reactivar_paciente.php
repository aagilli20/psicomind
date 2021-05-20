<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");

// verificamos que el acceso provenga del botón reactivar
if(isset($_REQUEST['reactivar'])){
	// leemos los campos del formulario y los almacenamos en un array
	$form = array();
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	// verificamos que haya seleccionado un paciente
	if(empty($_REQUEST["id_persona"])){
		// no seleccionó ningún paciente inactivo
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// seteamos las variables
		// notificar error
		$tpl->set_var('mensaje', "Debe seleccionar el paciente que desea reactivar");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "reactivar_paciente.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
		// forzamos la denteción del script
		die();
	}
	// no hay errores
	// obtenemos el id del paciente
	$id_paciente = $form["id_persona"];
	// cargamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');
	$sql = "UPDATE paciente SET activo_paciente='1' WHERE id_paciente='$id_paciente'"; 
	// verificamos el resultado del update
	if($conexion->Execute($sql)){
		// notificar que el paciente fue reactivado
		$tpl->set_var('mensaje', "El paciente ha sido reactivado");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "index.php");
		$tpl->set_var('mensaje_std', "Volver al inicio");
		// parseamos la plantilla
		$tpl->parse('info');
	}else{
		// notificar error
		$tpl->set_var('mensaje', "Error al reactivar el paciente, intentelo nuevamente");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "reactivar_paciente.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
	}
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>