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

// verificamos que se reciba por GET un parametro ID
if(isset($_GET['id'])){
	// verificamos que el ID no sea nulo
	if($_GET["id"] == ""){
		// ID nulo
		// inicializamos la plantilla para informar error
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// seteamos las variables
		$tpl->set_var('mensaje', "Debe elegir una obra social válida");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "listado_obras_sociales.php");
		$tpl->set_var('mensaje_std', "Volver a intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');	
		// forzamos la detención del script
		die();
	}
	// se recibió el identificador de la obra social por GET
	// hay que decodificar el id
	$id_obra_social = base64_decode($_GET['id']);
	
	// si hasta aquí no hay error
	if(! $hay_error){
		// verificamos que la obra social no tenga planes asociados
		$sql = "SELECT Count(*) FROM plan_obra_social WHERE id_obra_social='$id_obra_social';";
		$cantidad = $conexion->GetRow($sql);
		if($cantidad["Count(*)"] > 0){
			// hay planes asociados a esta obra social
			// inicializamos la plantilla para informar el error
			$tpl = new tpleng;
			$tpl->set_file('info', 'info.tpl');
			// seteamos las variables
			$tpl->set_var('mensaje', "No se puede eliminar la obra social debido a que tiene planes asociados");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "listado_obras_sociales.php");
			$tpl->set_var('mensaje_std', "Volver a intentarlo");
			// parseamos la plantilla
			$tpl->parse('info');	
			// forzamos la denteción del script
			die();
		}
		// si hasta aca no hay error
		// eliminamos el plan
		$sql = "DELETE FROM obra_social WHERE id_obra_social='$id_obra_social';";
		// verificamos el resultado del DELETE
		if(! $conexion->Execute($sql)) {
			// se produjo un error
			$hay_error = true;
			$msg_error = "Se produjo un error inesperado al eliminar la obra social, intentelo nuevamente".$conexion->ErrorMsg();		
		}
	}
	// inicializamos la plantilla informando el borrado o error
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');
	// seteamos las variables
	if($hay_error) $tpl->set_var('mensaje', $msg_error);
	else $tpl->set_var('mensaje', "La obra social ha sido eliminada con éxito");
	$tpl->set_var('html_adicional', "");
	$tpl->set_var('enlace_std', "index.php");
	$tpl->set_var('mensaje_std', "Volver al inicio");
	// parseamos la plantilla
	$tpl->parse('info');	
	// forzamos la detención del script
	die();
	
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>