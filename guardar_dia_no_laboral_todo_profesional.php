<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");
require_once("menu.php");

// inicialización de variables para el manejo de errores
$hay_error = false;
$msg_error = "";
	
// verificamos que se reciba como parámetro un identificador por el metodo GET
if(isset($_GET["id"])){
	// verificamos que el identificador recibido no sea nulo
	if($_GET["id"] == ""){
		// debe seleccionar al menos un día no laboral
		$hay_error = true;
		$msg_error = "Fatan datos obligatorios - Intento de acceso no autorizado</br>";
	}
		
	// verificamos que no se hayan detectado errores
	if(! $hay_error){
		// no hay error
		// iniciar transaccion
		$conexion->StartTrans();
		// obtenemos el identificador del día
		$id_dia = $_GET["id"];
		$db_error = "";
		// consultamos la matrícula de todos los profesionales
		$db_profesional = $conexion->Execute("SELECT matricula FROM profesional WHERE 1=1;");
		// para cada profesional
		foreach($db_profesional as $profesional){
			// asociamos el profesional al día no laboral
			$una_matricula = $profesional["matricula"];
			// verificamos que no sea duplicado
			$sql = "SELECT Count(*) FROM profesional_dia_no_laboral WHERE id_dia_no_laboral='$id_dia' AND matricula='$una_matricula';";
			$db_cant = $conexion->GetRow($sql);
			if($db_cant["Count(*)"] == 0){
				// si no tiene asignado el día no laboral, se lo asignamos
				$sql = "INSERT INTO profesional_dia_no_laboral (id_dia_no_laboral,matricula) VALUES ('$id_dia','$una_matricula');";
				$conexion->Execute($sql);
				$db_error .= $conexion->ErrorMsg();
			}
			// si lo tiene asignado, no hacemos nada
		}
		// finaliza la transacción y se verifica la existencia de errores
		if(! $conexion->CompleteTrans()) {
			// error
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
		$tpl->set_var('mensaje', $msg_error);
		$tpl->set_var('enlace_std', "listado_dias_no_laborales.php");
		$tpl->set_var('mensaje_std', "Volver a intentarlo");	
	} else {
		$tpl->set_var('mensaje', "El/Los profesional/le ha/n sido asociado/s con éxito al día no laboral");
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