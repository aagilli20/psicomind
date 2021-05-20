<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");
require_once("menu.php");

/*
* Esta clase elimina la asociación entre el día no laboral elegido por el usuario y todos los profesionales asociados al mismo
*/


// inicialización de variables que se utilizan, en el caso de aparecer un error, detectarlo e informarlo
$hay_error = false;
$msg_error = "";

// verificamos que el acceso sea válido
if(isset($_GET["id"])){
	// acceso válido
	// verificamos que el identificador no sea nulo
	if($_GET["id"] == ""){
		// identificador nulo, por lo cual, no seleccionó ningún día no laboral
		// registramos el error y el mensaje correspondiente
		$hay_error = true;
		$msg_error = "Fatan datos obligatorios - Intento de acceso no autorizado</br>";
	}
	// verificamos que hasta aquí no haya errores	
	if(! $hay_error){
		// no hay error
		// iniciar transaccion
		$conexion->StartTrans();
		// recuperamos el identificador del día no laboral
		$id_dia = $_GET["id"];
		$db_error = "";
		// consultamos el número de matrícula de todos los profesionales
		$db_profesional = $conexion->Execute("SELECT matricula FROM profesional WHERE 1=1;");
		foreach($db_profesional as $profesional){
			// para cada profesional se verifica si tiene asociado el día no laboral a eliminar
			$una_matricula = $profesional["matricula"];
			$sql = "SELECT Count(*) FROM profesional_dia_no_laboral WHERE id_dia_no_laboral='$id_dia' AND matricula='$una_matricula';";
			$db_cant = $conexion->GetRow($sql);
			if($db_cant["Count(*)"] > 0){
				// tiene el día elegido asociado, entonces eliminamos el registro
				$sql = "DELETE FROM profesional_dia_no_laboral WHERE id_dia_no_laboral='$id_dia' AND matricula='$una_matricula';";
				$conexion->Execute($sql);
				// si la base devolvió un error lo almacenamos
				$db_error .= $conexion->ErrorMsg();
			}
		}
		// finaliza la transacción
		if(! $conexion->CompleteTrans()) {
			// si la transacción no se completó con éxito, entonces guardamos el error
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
		// si hubo error, informamos el inconveniente
		$tpl->set_var('mensaje', $msg_error);
		$tpl->set_var('enlace_std', "listado_dias_no_laborales.php");
		$tpl->set_var('mensaje_std', "Volver a intentarlo");	
	} else {
		// si no hubo error, informamos que la solicitud se realizó con éxito
		$tpl->set_var('mensaje', "El/Los profesional/le ha/n sido desasociado/s con éxito al día no laboral");
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