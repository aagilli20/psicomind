<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");

// verificamos que haya ingresado desde el botón guardar
if(isset($_REQUEST['guardar'])){
	// leemos los campos del formulario y los almacenamos en un array
	$form = array();
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	// obtenemos el id del paciente
	$id_paciente = $form["id_paciente"];
	// iniciar transaccion
	$conexion->StartTrans();
	// eliminamos las relaciones profesional-especialidad cargadas en forma anterior
	$conexion->Execute("DELETE FROM paciente_minusvalia WHERE paciente_id_paciente='$id_paciente'");
	// cargamos las nuevas relaciones
	// antes verificamos que haya elegido al menos una minusvalía
	if(isset($_REQUEST["minusvalia"])){
		// para cada minusvalía tildada en el formulario
		foreach($form["minusvalia"] as $id_minus){
			$db_pac_minus = $conexion->Execute("INSERT INTO paciente_minusvalia(paciente_id_paciente,minusvalia_id_minusvalia)
												VALUES('$id_paciente','$id_minus')");
		}
	}
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');
	// verificamos el resultado de la transacción
	if($conexion->CompleteTrans()){
		// notificar que las especialidades fueron cargadas
		$tpl->set_var('mensaje', "La minusvalías del paciente han sido registradas");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "index.php");
		$tpl->set_var('mensaje_std', "Volver al inicio");
		// parseamos la plantilla
		$tpl->parse('info');
	}else{
		// notificar error
		$tpl->set_var('mensaje', "Error al guardar las minusvalías del paciente en la base de datos, intentelo nuevamente");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "elegir_paciente_minusvalia.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
	}
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>