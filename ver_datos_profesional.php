<?php 
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once('tpleng.class.php');
require_once("fechas.php");
require_once("menu.php");

// verificamos si el acceso es válido
if(isset($_GET['id'])){
	// acceso válido
	// decodificamos el número de matrícula
	$matricula = base64_decode($_GET['id']);
	// verificamos validez del ID
	$cantidad = $conexion->GetRow("SELECT Count(*) FROM profesional WHERE matricula='$matricula';");
	if($cantidad["Count(*)"] < 1){
		// intento de acceso no válido
		// notificamos el error
		header("location:info.php?id=3");
		// forzamos la detención del script
		die();
	}
	// consultamos los datos del profesional elegido
	$profesional = $conexion->GetRow("SELECT * FROM profesional WHERE matricula='$matricula';");
	$id_sexo = $profesional["persona_sexo_id_sexo"];
	$id_tipo_doc = $profesional["persona_tipo_documento_id_tipo_documento"];
	$nro_documento = $profesional["persona_nro_documento"];
	$persona = $conexion->GetRow("SELECT nombre_persona,apellido_persona FROM persona 
										WHERE sexo_id_sexo='$id_sexo' 
										AND tipo_documento_id_tipo_documento='$id_tipo_doc' 
										AND nro_documento='$nro_documento'");
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('ver_datos_profesional', 'ver_datos_profesional.tpl');
	// llenamos los campos de la plantilla
	$tpl->set_var('error', ""); 
	$tpl->set_var('nombre', $persona['nombre_persona']);
	$tpl->set_var('apellido', $persona['apellido_persona']);
	$tpl->set_var('matricula', $matricula);
	$tpl->set_var('domicilio', $profesional["domicilio_profesional"]);
	$tpl->set_var('telefono', $profesional["telefono_profesional"]);
	$tpl->set_var('celular', $profesional["celular_profesional"]);
	$tpl->set_var('email', $profesional["email_profesional"]);
	$tpl->set_var('fecha_desde', fecha_normal($profesional["fecha_desde"]));
	$id_persona = $id_sexo.$id_tipo_doc.$nro_documento;
	$tpl->set_var('id_persona', base64_encode($id_persona));
	
	// cargamos el menú principal
	$tpl->set_var('menu', getMenu());
	// parseamos la plantilla
	$tpl->parse('ver_datos_profesional');
} else {
	// intento de acceso no válido
	header("location:info.php?id=3");
}

?>