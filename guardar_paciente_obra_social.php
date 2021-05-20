<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");

// verificamos que haya ingresado desde el botón guardar
if(isset($_REQUEST['guardar'])){
	// inicializamos variables para el manejo de errores
	$ok = true;
	$error_msg = "";
	// leemos los campos del formulario y los almacenamos en un array
	$form = array();
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	// obtenemos el id del paciente
	$id_paciente = $form["id_paciente"];
	
	// verificamos que haya seleccionado un plan de obra social
	if(! isset($_REQUEST["id_os"])){
		// no seleccionó ningún plan de obra social
		$ok = false;
		$error_msg = "Debe seleccionar una obra social y plan antes de guardar";	
	} else {
		$id_plan = $form["id_os"];	
	}
	
	// verificamos que el paciente no tenga asociada una obra social
	$sql = "SELECT id_plan_obra_social FROM  paciente WHERE id_paciente='$id_paciente'";
	$db_paciente = $conexion->GetRow($sql);
	if($ok){
		if($db_paciente["id_plan_obra_social"] != NULL){
			// ya tiene un plan de obra social asociado
			$ok = false;
			$error_msg = "El paciente ya tiene una obra social asociada";	
		}
	}
	
	if($ok){
		// si no hay errores, entonces asociamos el plan seleccionado al paciente
		$sql = "UPDATE paciente SET id_plan_obra_social='$id_plan' WHERE id_paciente='$id_paciente';";
		$ok = $conexion->Execute($sql);
		if(! $ok) $error_msg = "Error al guardar los cambios en la base de datos - ".$conexion->ErrorMsg();	
	}
		
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');

	if($ok){
		// notificar que la obra socia fue asociada correctamente
		$tpl->set_var('mensaje', "La obra social fue asociada al paciente de manera existosa");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "index.php");
		$tpl->set_var('mensaje_std', "Volver al inicio");
		// parseamos la plantilla
		$tpl->parse('info');
	}else{
		// notificar error
		$tpl->set_var('mensaje', $error_msg);
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "elegir_paciente_obra_social.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
	}
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>