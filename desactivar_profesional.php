<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");

// verificamos que el intento de acceso sea válido
if(isset($_GET['id'])){
	// decodificamos el número de matrícula
	$matricula = base64_decode($_GET['id']);
	// iniciar transaccion
	$conexion->StartTrans();
	// desactivamos el profesional
	$sql = "UPDATE profesional SET activo_profesional='0' WHERE matricula='$matricula';";
	$conexion->Execute($sql);
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');
	// seteamos las variables
	if($conexion->CompleteTrans()){
		// notificar que el profesional ha sido dado de baja
		$tpl->set_var('mensaje', "El profesional ha sido dado de baja");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "index.php");
		$tpl->set_var('mensaje_std', "Volver al inicio");
		// parseamos la plantilla
		$tpl->parse('info');
	}else{
		// notificar error
		$tpl->set_var('mensaje', "Error al registrar la baja del profesional, intentelo nuevamente");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "listado_profesionales.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
	}
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>