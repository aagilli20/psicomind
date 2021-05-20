<?php 
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once('tpleng.class.php');
require_once("fechas.php");
require_once("menu.php");

// verificamos que sea un acceso válido
if(isset($_GET['id'])){
	// acceso válido
	// decodificamos el identificador del usuario
	$id_usuario = base64_decode($_GET['id']);
	// verificamos validez del ID
	$cantidad = $conexion->GetRow("SELECT Count(*) FROM usuario WHERE id_usuario='$id_usuario';");
	if($cantidad["Count(*)"] < 1){
		// intento de acceso no válido
		// notificamos el error
		header("location:info.php?id=6");
		// forzamos la detención del script
		die();
	}
	// consultamos los datos del usuario elegido
	$usuario = $conexion->GetRow("SELECT * FROM usuario WHERE id_usuario='$id_usuario';");
	$id_sexo = $usuario["sexo_id_sexo"];
	$id_tipo_doc = $usuario["tipo_documento_id_tipo_documento"];
	$nro_documento = $usuario["persona_nro_documento"];
	$persona = $conexion->GetRow("SELECT * FROM persona 
										WHERE sexo_id_sexo='$id_sexo' 
										AND tipo_documento_id_tipo_documento='$id_tipo_doc' 
										AND nro_documento='$nro_documento'");
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('ver_datos_usuario', 'ver_datos_usuario.tpl');
	// llenamos los campos de la plantilla
	$tpl->set_var('error', ""); 
	$tpl->set_var('nombre', $persona['nombre_persona']);
	$tpl->set_var('apellido', $persona['apellido_persona']);
	$tpl->set_var('nick', $id_usuario);
	$tpl->set_var('domicilio', $persona["domicilio_persona"]);
	$tpl->set_var('telefono', $persona["telefono_persona"]);
	$tpl->set_var('celular', $persona["celular_persona"]);
	$tpl->set_var('email', $persona["email_persona"]);
	$id_persona = $persona['sexo_id_sexo'].$persona['tipo_documento_id_tipo_documento'].$persona['nro_documento'];
	$tpl->set_var('id_persona', base64_encode($id_persona));
	
	// cargamos el menú principal
	$tpl->set_var('menu', getMenu());
	// parseamos la plantilla
	$tpl->parse('ver_datos_usuario');
} else {
	// intento de acceso no válido
	header("location:info.php?id=6");
}

?>