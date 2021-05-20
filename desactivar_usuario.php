<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");

// verificamos que el acceso sea válido
if(isset($_GET['id'])){
	// acceso válido
	// decodificamos el id del usuario
	$id_usuario = base64_decode($_GET['id']);
	// iniciar transaccion
	$conexion->StartTrans();
	// desactivamos el profesional
	$sql = "UPDATE usuario SET activo_usuario='0' WHERE id_usuario='$id_usuario';";
	$conexion->Execute($sql);
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');
	// seteamos las variables
	if($conexion->CompleteTrans()){
		// notificar que el usuario ha sido dado de baja
		$tpl->set_var('mensaje', "El usuario ha sido dado de baja");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "index.php");
		$tpl->set_var('mensaje_std', "Volver al inicio");
		// parseamos la plantilla
		$tpl->parse('info');
	}else{
		// notificar error
		$tpl->set_var('mensaje', "Error al registrar la baja del usuario, intentelo nuevamente");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "listado_usuarios.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
	}
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>