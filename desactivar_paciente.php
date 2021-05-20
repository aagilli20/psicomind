<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");

// verificamos que se haya recibido el identificador del paciente por GET
if(isset($_GET['id'])){
	// decodificamos el identificador del paciente
	$id_paciente = base64_decode($_GET['id']);
	// iniciar transaccion
	$conexion->StartTrans();
	// desactivamos el paciente
	$sql = "UPDATE paciente SET activo_paciente='0' WHERE id_paciente='$id_paciente';";
	$conexion->Execute($sql);
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');
	// verificamos que la transacción se complete con éxito
	if($conexion->CompleteTrans()){
		// transacción OK
		// notificar que el paciente ha sido dado de baja
		$tpl->set_var('mensaje', "El paciente ha sido dado de baja");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "index.php");
		$tpl->set_var('mensaje_std', "Volver al inicio");
		// parseamos la plantilla
		$tpl->parse('info');
	}else{
		// falló la transacción
		// notificar error
		$tpl->set_var('mensaje', "Error al registrar la baja del paciente, intentelo nuevamente");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "listado_pacientes.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
	}
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>