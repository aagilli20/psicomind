<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");

// verificamos que el acceso provenga del botón guardar
if(isset($_REQUEST['guardar'])){
	// leemos los campos del formulario y los almacenamos en un array
	$form = array();
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	// verificamos que haya seleccionado un horario para el turno
	if(! empty($form["hora_inicio"])){
		// no hay error
		// preproceso del formulario
		$horario = $form["hora_inicio"];
		$matricula = $form["id_profesional"];
		$id_paciente = $form["id_paciente"];
		// guardamos el turno
		$fecha = fecha_mysql($form["dia_elegido"]);
		$id_turno = $form["id_turno"];
		// guardamos el turno otorgado en la base
		$sql = "INSERT INTO turno_otorgado(id_turno_otorgado,fecha_turno,horario,id_paciente,id_profesional,id_turno) 
		VALUES ('NULL','$fecha','$horario','$id_paciente','$matricula','$id_turno');";
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// ejecutamos el INSERT y verificamos el resultado
		if($conexion->Execute($sql)){
			// notificar que el dia no laboral fue creado
			$tpl->set_var('mensaje', "El turno fue registrado exitosamente");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "index.php");
			$tpl->set_var('mensaje_std', "Volver al inicio");
			// parseamos la plantilla
			$tpl->parse('info');
			// forzamos la detención del script
			die();
		}else{
			// notificar error
			$tpl->set_var('mensaje', "Error turno no se ha podido guardar, intentelo nuevamente");
			$tpl->set_var('html_adicional', "");
			$id_paciente2 = base64_encode($form["id_paciente"]);
			$matricula2 = base64_encode($form["id_profesional"]);
			$tpl->set_var('enlace_std', "consignar_turno.php?id1=".$id_paciente2."&id2=".$matricula2);
			$tpl->set_var('mensaje_std', "Volver a Intentarlo");
			// parseamos la plantilla
			$tpl->parse('info');
			// forzamos la detención del script
			die();
		}
	} else {
		// no seleccionó un horario para el turno
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');	
		// notificar error
			$tpl->set_var('mensaje', "Antes de confirmar debe seleccionar el horario para el turno");
			$tpl->set_var('html_adicional', "");
			$id_paciente2 = base64_encode($form["id_paciente"]);
			$matricula2 = base64_encode($form["id_profesional"]);
			$tpl->set_var('enlace_std', "consignar_turno.php?id1=".$id_paciente2."&id2=".$matricula2);
			$tpl->set_var('mensaje_std', "Volver a Intentarlo");
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