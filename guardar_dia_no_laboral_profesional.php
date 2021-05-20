<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");
require_once("menu.php");

// variables para el manejo de errores
$hay_error = false;
$msg_error = "";

// verificamos que el acceso sea válido
if(isset($_REQUEST['guardar'])){
	// ingreso válido
	// creamos un array para almacenar los campos de formulario
	$form = array();
	// leemos los campos del formulario y los pasamos al array
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	// verificamos que haya seleccionado al menos un profesional
	if(! isset($form["matricula"])){
		// debe seleccionar al menos un profesional
		$hay_error = true;
		$msg_error = "Fatan datos obligatorios - Debe seleccionar al menos un profesional para el día no laboral</br>";
	}
	
	// verificamos que hasta aquí no haya errores
	if(! $hay_error){
		// iniciar transaccion
		$conexion->StartTrans();
		// guardado por cada profesional tildado
		// recuperamos el identificador del día no laboral a asociar
		$id_dia = $_POST["id_dia_no_laboral"];
		$db_error = "";
		// para cada profesional tildado en el formulario
		foreach($form["matricula"] as $una_matricula){
			// verificamos que no exista la asociación
			$sql = "SELECT Count(*) FROM profesional_dia_no_laboral WHERE id_dia_no_laboral='$id_dia' AND matricula='$una_matricula';";
			$db_cant = $conexion->GetRow($sql);
			if($db_cant["Count(*)"] == 0){
				$sql = "INSERT INTO profesional_dia_no_laboral (id_dia_no_laboral,matricula) VALUES ('$id_dia','$una_matricula');";
				$conexion->Execute($sql);
				$db_error .= $conexion->ErrorMsg();
			}
		}
		// finaliza la transacción
		if(! $conexion->CompleteTrans()) {
			// si falla la transacción lo notificamos
			$hay_error = true;
			$db_error .= $conexion->ErrorMsg();
			$msg_error = $msg_error."Error inesperado al guardar los cambios en la base de datos, intentelo nuevamente".$db_error;
		}
	}
	
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');
	// seteamos las variables
	// verificamos la existencia de errores
	if($hay_error) {
		// se detectó un error
		$tpl->set_var('mensaje', $msg_error);
		$tpl->set_var('enlace_std', "listado_dias_no_laborales.php");
		$tpl->set_var('mensaje_std', "Volver a intentarlo");	
	} else {
		// la operación se realizó con éxito
		$tpl->set_var('mensaje', "El/Los profesional/le ha/n sido asociado/s con éxito al día no laboral");
		$tpl->set_var('enlace_std', "index.php");
		$tpl->set_var('mensaje_std', "Volver al inicio");
	}
	
	$tpl->set_var('html_adicional', "");
	
	// parseamos la plantilla
	$tpl->parse('info');	
	die();
		
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>