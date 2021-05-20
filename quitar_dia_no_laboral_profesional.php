<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");
require_once("menu.php");

/*
* Esta clase elimina la asociación entre un día laboral y un profesional
*/

// se inicializan variables globales para el manejo de errores
$hay_error = false;
$msg_error = "";

// verificamos que el ingreso haya sido por el botón guardar
if(isset($_REQUEST['guardar'])){
	// acceso correcto
	// creamos un array para leer los campos recuperados del formulario
	$form = array();
	// recuperamos los datos del formulario y los almacenamos en el array
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	// verificamos que haya seleccionado al menos un profesional
	if(! isset($form["matricula"])){
		// debe seleccionar al menos un profesional
		$hay_error = true;
		$msg_error = "Fatan datos obligatorios - Debe seleccionar al menos un profesional para quitar del día no laboral</br>";
	}
	// verificamos que hasta aquí no se hayan detectado errores
	if(! $hay_error){
		// iniciar transaccion
		$conexion->StartTrans();
		// recuperamso el día no laboral elegido
		$id_dia = $_POST["id_dia_no_laboral"];
		$db_error = "";
		// para cada profesional seleccionado
		foreach($form["matricula"] as $una_matricula){
			// verificamos la existencia de la asociación a eliminar
			$sql = "SELECT Count(*) FROM profesional_dia_no_laboral WHERE id_dia_no_laboral='$id_dia' AND matricula='$una_matricula';";
			$db_cant = $conexion->GetRow($sql);
			if($db_cant["Count(*)"] > 0){
				// existe la asociación, entonces la eliminamos
				$sql = "DELETE FROM profesional_dia_no_laboral WHERE id_dia_no_laboral='$id_dia' AND matricula='$una_matricula';";
				$conexion->Execute($sql);
				$db_error .= $conexion->ErrorMsg();
			}
		}
		// finaliza la transacción
		if(! $conexion->CompleteTrans()) {
			// la transacción no se completó correctamente
			$hay_error = true;
			$db_error .= $conexion->ErrorMsg();
			$msg_error = $msg_error."Error inesperado al guardar los cambios en la base de datos, intentelo nuevamente".$db_error;
		}
	}
	
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');
	// seteamos las variables
	if($hay_error) {
		// informamos el error
		$tpl->set_var('mensaje', $msg_error);
		$tpl->set_var('enlace_std', "listado_dias_no_laborales.php");
		$tpl->set_var('mensaje_std', "Volver a intentarlo");	
	} else {
		// informamos que se eliminó el registro
		$tpl->set_var('mensaje', "El/Los profesional/le ha/n sido desasociado/s con éxito del día no laboral");
		$tpl->set_var('enlace_std', "index.php");
		$tpl->set_var('mensaje_std', "Volver al inicio");
	}
	
	$tpl->set_var('html_adicional', "");
	
	// parseamos la plantilla
	$tpl->parse('info');	
	// forzamos la detención del script
	die();	
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>