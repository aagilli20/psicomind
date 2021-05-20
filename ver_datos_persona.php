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
	// decodificamos el identificador de la persona
	$id_persona = base64_decode($_GET['id']);
	// separamos el identificador
	$id_sexo = substr($id_persona, 0, 1);
	$id_tipo_doc = substr($id_persona, 1, 1);
	$fin = strlen($id_persona) - 1;
	$nro_documento = substr($id_persona, 2, $fin);	
	// verificamos validez del ID
	$cantidad = $conexion->GetRow("SELECT Count(*) FROM persona WHERE nro_documento='$nro_documento' 
																AND sexo_id_sexo='$id_sexo' 
																AND tipo_documento_id_tipo_documento='$id_tipo_doc'");
	if($cantidad["Count(*)"] < 1){
		// intento de acceso no válido
		// notificamos el error
		header("location:info.php?id=3");
		// forzamos la detención del script
		die();
	}
	
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('ver_datos_persona', 'ver_datos_persona.tpl');
	// consultamos los datos de la persona seleccionada
	$persona = $conexion->GetRow("SELECT * FROM persona WHERE nro_documento='$nro_documento' 
																AND sexo_id_sexo='$id_sexo' 
																AND tipo_documento_id_tipo_documento='$id_tipo_doc';");
	// consultamos valor tipo doc
	$tipo_doc = $conexion->GetRow("SELECT tipo_documento FROM tipo_documento WHERE id_tipo_documento='$id_tipo_doc';");
	// consultamos valor sexo
	$sexo = $conexion->GetRow("SELECT sexo FROM sexo WHERE id_sexo='$id_sexo';");
	// cargamos los datos en la plantilla
	if($persona["url_foto_persona"] == NULL) $tpl->set_var('url_foto', "./style/images/sin_imagen_usuario.jpg");
	else $tpl->set_var('url_foto', $persona["url_foto_persona"]);	
	$tpl->set_var('error', "");
	$tpl->set_var('id_persona', $id_persona);
	$tpl->set_var('tipo_doc_valor', $tipo_doc["tipo_documento"]);
	$tpl->set_var('documento', $persona["nro_documento"]);
	$tpl->set_var('sexo_valor', $sexo["sexo"]);
	$tpl->set_var('fenac', fecha_datepicker($persona["fecha_nacimiento"]));
	$tpl->set_var('nombre', $persona["nombre_persona"]);
	$tpl->set_var('apellido', $persona["apellido_persona"]);
	$tpl->set_var('telefono', $persona["telefono_persona"]);
	$tpl->set_var('celular', $persona["celular_persona"]);
	$tpl->set_var('email', $persona["email_persona"]);
	$tpl->set_var('domicilio', $persona["domicilio_persona"]);
	
	// cargamos el menú principal
	$tpl->set_var('menu', getMenu());
	// parseamos la plantilla
	$tpl->parse('ver_datos_persona');
} else {
	// intento de acceso no válido
	header("location:info.php?id=5");
}

?>