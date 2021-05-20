<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("fechas.php");
require_once("menu.php");

// inicializamos variables para el manejo de errores
$hay_error = false;
$msg_error = "";

// verificamos que haya seleccionado un plan correctamente
if(isset($_GET['id'])){
	// ha elegido un plan
	if($_GET["id"] == ""){
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// seteamos las variables
		$tpl->set_var('mensaje', "Debe elegir un plan válido");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "listado_obras_sociales.php");
		$tpl->set_var('mensaje_std', "Volver a intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');	
		die();
	}
	// hay que decodificar el id
	$id_plan = base64_decode($_GET['id']);
	// verificar que no haya turnos pendientes en ese horario de atención
	
	if(! $hay_error){
		// verificamos que el plan no esté asociado a ningún paciente
		$sql = "SELECT Count(*) FROM paciente WHERE id_plan_obra_social='$id_plan';";
		$cantidad = $conexion->GetRow($sql);
		if($cantidad["Count(*)"] > 0){
			// hay pacientes asociados a este plan
			$tpl = new tpleng;
			$tpl->set_file('info', 'info.tpl');
			// seteamos las variables
			$tpl->set_var('mensaje', "No se puede eliminar el plan debido a que tiene pacientes asociados");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "listado_obras_sociales.php");
			$tpl->set_var('mensaje_std', "Volver a intentarlo");
			// parseamos la plantilla
			$tpl->parse('info');	
			die();
		}
		// eliminamos el plan
		$sql = "DELETE FROM plan_obra_social WHERE id_plan='$id_plan';";
		if(! $conexion->Execute($sql)) {
			$hay_error = true;
			$msg_error = "Se produjo un error inesperado al eliminar el plan, intentelo nuevamente".$conexion->ErrorMsg();		
		}
	}
	// cargamos la plantilla informando el borrado
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');
	// seteamos las variables
	$tpl->set_var('mensaje', "El plan ha sido eliminado con éxito");
	$tpl->set_var('html_adicional', "");
	$tpl->set_var('enlace_std', "index.php");
	$tpl->set_var('mensaje_std', "Volver al inicio");
	// parseamos la plantilla
	$tpl->parse('info');	
	die();
	
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>