<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");
require_once("menu.php");

// inicializamos variables para el manejo de errores
$datos_obligatorios = true;
$formato_datos = true;
$error_obligatorios = "";
$error_formato = "";

// verificamos que el acceso provenga del botón guardar
if(isset($_REQUEST['guardar'])){
	// leemos los campos del formulario y los almacenamos en un array
	$form = array();
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	// verificamos que esten todos los datos obligatorios
	if(($form["motivo"] == "Motivo") || (empty($form["motivo"]))){
		// el motivo es un dato obligatorio
		$datos_obligatorios = false;
		$error_obligatorios = $error_obligatorios."- Fatan datos obligatorios - Motivo por el cual el día es no laboral</br>";
	}
	// si los datos obligatorios estan, entonces continuamos
	if($datos_obligatorios){
		// verificamos que el formato del resto de los datos sea correcto
		if((! fecha_valida_futura($form["fecha"])) || ($form["fecha"] == "Día no laboral (DD/MM/AAAA)")) {
			// fecha incorrecta, informar error
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El formato de la fecha, debe seguir el siguiente ejemplo: dd/mm/aaaa</br>";
		}
		if(! is_alphanumeric($form["motivo"],1,140)){
			// motivo incorrecto, informar error
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El motivo debe contener entre 1 y 140 caracteres alfanuméricos</br>";
		}
	}
	// verificamos si estan todos los datos obligatorios y su formato es correcto
	if(!$formato_datos || !$datos_obligatorios){
		// hay error
		// informamos el error
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// seteamos las variables
		// notificar error
		$tpl->set_var('mensaje', $error_obligatorios.$error_formato);
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "nuevo_dia_no_laboral.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
		// forzamos la detención del script
		die();
	}else{
		// no hay error
		// guardamos el día
		// preproceso del formulario
		$motivo = $form["motivo"];
		// adecua el fomrato de la fecha para guardarlo en MySQL
		$fecha = fecha_mysql($form["fecha"]);
		// verificamos que no sea un duplicado
		$cantidad = $conexion->GetRow("SELECT Count(*) FROM dia_no_laboral WHERE fecha='$fecha';");
		if($cantidad["Count(*)"] > 0) {
			// si cantidad es mayor a cero, entonces día duplicado
			$duplicado = true;
			// inicializamos la plantilla
			$tpl = new tpleng;
			$tpl->set_file('info', 'info.tpl');
			// notificar error
			$tpl->set_var('mensaje', "Error al guardar el dia no laboral: Ya existe un día no laboral registrado en la fecha seleccionada");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "nuevo_dia_no_laboral.php");
			$tpl->set_var('mensaje_std', "Volver a Intentarlo");
			// parseamos la plantilla
			$tpl->parse('info');
			// forzamos la detención del script
			die();
		}
		// guardamos el dia no laboral en la base
		$sql = "INSERT INTO dia_no_laboral(id_dia_no_laboral,fecha,motivo) VALUES ('NULL','$fecha', '$motivo');";
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// ejecutamos el INSERT y verificamos el resultado
		if($conexion->Execute($sql)){
			// notificar que el dia no laboral fue creado
			$tpl->set_var('mensaje', "El día no laboral fue registrado exitosamente");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "index.php");
			$tpl->set_var('mensaje_std', "Volver al inicio");
			// parseamos la plantilla
			$tpl->parse('info');
		}else{
			// notificar error
			$tpl->set_var('mensaje', "Error al guardar el día no laboral, intentelo nuevamente");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "nuevo_horario_atencion.php");
			$tpl->set_var('mensaje_std', "Volver a Intentarlo");
			// parseamos la plantilla
			$tpl->parse('info');
		}
	}
	
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>