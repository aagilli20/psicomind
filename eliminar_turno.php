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

// verificamos que se reciba el parametro ID por GET
if(isset($_GET['id'])){
	// verificamos que el ID no sea nulo
	if($_GET["id"] == ""){
		// ID nulo
		// inicializamos la plantilla para notificar el error
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// seteamos las variables
		$tpl->set_var('mensaje', "Debe elegir un turno otorgado para eliminar");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "index.php");
		$tpl->set_var('mensaje_std', "Volver al inicio");
		// parseamos la plantilla
		$tpl->parse('info');	
		// forzamos la detención del script
		die();
	}
	// se recibió el ID
	// hay que decodificar el id
	$id_turno = $_GET['id'];
	
	// verificamos que hasta aca no haya error
	if(! $hay_error){
		// eliminamos el turno otorgado
		$sql = "DELETE FROM turno_otorgado WHERE id_turno_otorgado='$id_turno';";
		// ejecutamos el DELETE y verificamos su resultado
		if(! $conexion->Execute($sql)) {
			// error
			$hay_error = true;
			$msg_error = "Se produjo un error inesperado al eliminar el turno otorgado, intentelo nuevamente".$conexion->ErrorMsg();		
		}
	}
	// inicializamos la plantilla
	// ID nulo
	// inicializamos la plantilla para notificar el error
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');
	if($hay_error){
		// seteamos las variables
		$tpl->set_var('mensaje', $msg_error);
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "index.php");
		$tpl->set_var('mensaje_std', "Volver al inicio");
		// parseamos la plantilla
		$tpl->parse('info');	
		// forzamos la detención del script
		die();	
	} else {
		// seteamos las variables
		$tpl->set_var('mensaje', "El turno ha sido eliminado correctamente");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "index.php");
		$tpl->set_var('mensaje_std', "Volver al inicio");
		// parseamos la plantilla
		$tpl->parse('info');	
		// forzamos la detención del script
		die();	
	}
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>