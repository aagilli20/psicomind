<?php 
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// verificamos que haya ingresado desde el botón seleccionar
if(isset($_REQUEST['seleccionar'])){
	// verificamos que haya elegido una persona
	if(empty($_REQUEST["persona"])){
		// no seleccionó ninguna persona
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// seteamos las variables
		// notificar error
		$tpl->set_var('mensaje', "Antes de guardar un nuevo profesional debe seleccionar una persona");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "elegir_persona_profesional.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
		die();
	}
	
	// ingresó a través del botón seleccionar y eligió una persona
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('nuevo_profesional', 'nuevo_profesional.tpl');
	// leemos el id persona del formulario
	$id_persona = $_REQUEST["persona"];
	// descomponemos el identificador de persona
	$id_sexo = substr($id_persona, 0, 1);
	$id_tipo_doc = substr($id_persona, 1, 1);
	$fin = strlen($id_persona) - 1;
	$nro_documento = substr($id_persona, 2, $fin);
	// llenamos los campos de la plantilla
	$tpl->set_var('id_persona', $id_persona);
	// consultar nombre y apellido
	$persona = $conexion->GetRow("SELECT nombre_persona,apellido_persona FROM persona 
										WHERE sexo_id_sexo='$id_sexo' 
										AND tipo_documento_id_tipo_documento='$id_tipo_doc' 
										AND nro_documento='$nro_documento'");
	$tpl->set_var('error', ""); 
	$tpl->set_var('nombre', $persona['nombre_persona']);
	$tpl->set_var('apellido', $persona['apellido_persona']);
	$tpl->set_var('matricula', "");
	$tpl->set_var('domicilio', "");
	$tpl->set_var('telefono', "");
	$tpl->set_var('celular', "");
	$tpl->set_var('email', "");
	
	// cargamos el menu principal
	$tpl->set_var('menu', getMenu());
	// parseamos la plantilla
	$tpl->parse('nuevo_profesional');
} else {
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>