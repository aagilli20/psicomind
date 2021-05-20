<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");

// verificamos que el ingresso provenga del botón guardar
if(isset($_REQUEST['guardar'])){
	// leemos y guardamos en un array todos los campos del formulario
	$form = array();
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	// obtenemos el número de matrícula
	$matricula = $form["matricula"];
	// iniciar transaccion
	$conexion->StartTrans();
	// eliminamos las relaciones profesional-especialidad cargadas en forma anterior
	$conexion->Execute("DELETE FROM profesional_especialidad WHERE profesional_matricula='$matricula'");
	// cargamos las nuevas relaciones
	// antes verificamos que haya elegido al menos una
	if(isset($_REQUEST["especialidad"])){
		// para toda especialidad tildada en el formulario
		foreach($form["especialidad"] as $id_espe){
			$db_prof_espe = $conexion->Execute("INSERT INTO profesional_especialidad(profesional_matricula,especialidad_id_especialidad)
												VALUES('$matricula','$id_espe')");
		}
	}
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');
	// verificamos el resultado de la transacción
	if($conexion->CompleteTrans()){
		// notificar que las especialidades fueron cargadas
		$tpl->set_var('mensaje', "La especialidades del profesional han sido registradas");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "index.php");
		$tpl->set_var('mensaje_std', "Volver al inicio");
		// parseamos la plantilla
		$tpl->parse('info');
	}else{
		// notificar error
		$tpl->set_var('mensaje', "Error al guardar las especialidades del profesional en la base de datos, intentelo nuevamente");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "elegir_profesional_especialidad.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
	}
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>