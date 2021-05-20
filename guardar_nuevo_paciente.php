<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");

// validar que haya entrado por guardar y haya elgido una persona
if(isset($_REQUEST['guardar'])){
	// verificamos que haya elegido una persona
	// leemos el id persona del formulario
	if(empty($_REQUEST["persona"])){
		// cargamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// seteamos las variables
		// notificar error
		$tpl->set_var('mensaje', "Antes de guardar un nuevo paciente debe seleccionar una persona");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "elegir_persona_paciente.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
		die();
	}
	// preproceso del formulario
	$id_persona = $_REQUEST["persona"];
	$id_sexo = substr($id_persona, 0, 1);
	$id_tipo_doc = substr($id_persona, 1, 1);
	$fin = strlen($id_persona) - 1;
	$nro_documento = substr($id_persona, 2, $fin);	
	$cantidad = $conexion->GetRow("SELECT Count(*) FROM paciente WHERE persona_nro_documento='$nro_documento' 
																AND persona_sexo_id_sexo='$id_sexo' 
																AND persona_tipo_documento_id_tipo_documento='$id_tipo_doc'");
	if($cantidad["Count(*)"] > 0) {
		$duplicado = true;
		// cargamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// notificar error
		$tpl->set_var('mensaje', "Error al guardar el paciente: Ya existe un paciente registrado con el mismo sexo, tipo y número de documento");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "elegir_persona_paciente.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
		die();
	}
	// tomamos la fecha actual
	$hoy = date("Y-m-d");
	// guardamos el paciente en la base
	$sql = "INSERT INTO paciente(fecha_desde,activo_paciente,persona_nro_documento,persona_sexo_id_sexo,persona_tipo_documento_id_tipo_documento) 
	VALUES ('$hoy', '1', '$nro_documento', '$id_sexo', '$id_tipo_doc');";
	// cargamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');
	// seteamos las variables
	if($conexion->Execute($sql)){
		// notificar que la persona fue creada
		$tpl->set_var('mensaje', "El paciente fue registrado correctamente");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "index.php");
		$tpl->set_var('mensaje_std', "Volver al inicio");
		// parseamos la plantilla
		$tpl->parse('info');
	}else{
		// notificar error
		$tpl->set_var('mensaje', "Error al guardar la persona en la base de datos, intentelo nuevamente");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "elegir_persona_paciente.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
	}
	
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>