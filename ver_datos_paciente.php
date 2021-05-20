<?php 
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once('tpleng.class.php');
require_once("fechas.php");
require_once("menu.php");

// verificamos que el acceso sea válido
if(isset($_GET['id'])){
	// decodificamos el identificador del paciente
	$id_paciente = base64_decode($_GET['id']);
	// verificamos validez del ID
	$cantidad = $conexion->GetRow("SELECT Count(*) FROM paciente WHERE id_paciente='$id_paciente';");
	if($cantidad["Count(*)"] < 1){
		// intento de acceso no válido
		// notificamos el error
		header("location:info.php?id=4");
		// forzamos la detención del script
		die();
	}
	// consultamos los datos del paciente elegido
	$paciente = $conexion->GetRow("SELECT * FROM paciente WHERE id_paciente='$id_paciente';");
	$id_sexo = $paciente["persona_sexo_id_sexo"];
	$id_tipo_doc = $paciente["persona_tipo_documento_id_tipo_documento"];
	$nro_documento = $paciente["persona_nro_documento"];
	$persona = $conexion->GetRow("SELECT * FROM persona 
										WHERE sexo_id_sexo='$id_sexo' 
										AND tipo_documento_id_tipo_documento='$id_tipo_doc' 
										AND nro_documento='$nro_documento'");
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('ver_datos_paciente', 'ver_datos_paciente.tpl');
	// llenamos los campos de la plantilla
	$tpl->set_var('error', ""); 
	$tpl->set_var('nombre', $persona['nombre_persona']);
	$tpl->set_var('apellido', $persona['apellido_persona']);
	$tpl->set_var('codigo', $id_paciente);
	$tpl->set_var('domicilio', $persona["domicilio_persona"]);
	$tpl->set_var('telefono', $persona["telefono_persona"]);
	$tpl->set_var('celular', $persona["celular_persona"]);
	$tpl->set_var('email', $persona["email_persona"]);
	$tpl->set_var('fecha_desde', fecha_normal($paciente["fecha_desde"]));
	$id_persona = $persona['sexo_id_sexo'].$persona['tipo_documento_id_tipo_documento'].$persona['nro_documento'];
	$tpl->set_var('id_persona', base64_encode($id_persona));
	
	// cargamos el menú principal
	$tpl->set_var('menu', getMenu());
	// parseamos la plantilla
	$tpl->parse('ver_datos_paciente');
} else {
	// intento de acceso no válido
	header("location:info.php?id=4");
}

?>