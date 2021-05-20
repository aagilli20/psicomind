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

// verificamos que haya ingresado a través del botón guardar
if(isset($_REQUEST['guardar'])){
	// presionó guardar
	// leemos los campos del formulario y los cargamos en un array
	$form = array();
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	// validamos que esten completos los datos obligatorios
	if((empty($form["matricula"]))||($form["matricula"]=="Número de Matrícula")){
		// La matrícula del profesional es un dato obligatorio
		$datos_obligatorios = false;
		$error_obligatorios = "- Fatan datos obligatorios - Número de Matrícula</br>";
		}if((empty($form["domicilio"]))||($form["domicilio"]=="Domicilio Profesional")){
			// El domicilio donde atiende el profesional es obligatorio
			$datos_obligatorios = false;
			$error_obligatorios = $error_obligatorios."- Fatan datos obligatorios - Domicilio donde atiende el Profesional</br>";
		}
	
	if($datos_obligatorios){
		// los datos obligatorios fueron ingresados
		// validamos el formato de los datos ingresados
		if(! is_alphanumeric($form["matricula"], $min_length = 1, $max_length = 14)) {
			$formato_datos = false;
			$error_formato = "- Error de formato - La matrícula debe contener solo caracteres alfanumericos con una longitud máxima de 14 caracteres</br>";
		}
		if(! is_alphanumeric($form["domicilio"], $min_length = 0, $max_length = 60)) {
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El domicilio del Profesional debe contener solo caracteres alfanumericos con una longitud máxima de 40 caracteres</br>";
		}
		if((! contains_phone_number($form["telefono"])) && ($form["telefono"] != "")){
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El formato del teléfono, debe seguir el siguiente ejemplo: 0342-1000000</br>";
		}
		if((! contains_phone_number($form["celular"])) && ($form["celular"] != "")){
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El formato del celular, debe seguir el siguiente ejemplo: 0342-1000000</br>";
		}
		if((! is_email($form["email"])) && ($form["email"] != "")){
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El formato del email, debe seguir el siguiente ejemplo: username@dominio.com</br>";
		}
	}
	
	// verificamos la existencia de errores
	if(!$formato_datos || !$datos_obligatorios){
		// hay error
		// volvemos al form nuevo profesional informando el error
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('ver_datos_profesional', 'ver_datos_profesional.tpl');
		// llenamos los campos de la plantilla
		$tpl->set_var('error', $error_obligatorios.$error_formato);
		// cargamos los datos ingresados por el usuario
		$tpl->set_var('error', $error_obligatorios.$error_formato); 
		$tpl->set_var('nombre',$form["nombre"]);
		$tpl->set_var('apellido', $form["apellido"]);
		$tpl->set_var('matricula', $form["matricula"]);
		$tpl->set_var('domicilio', $form["domicilio"]);
		$tpl->set_var('telefono', $form["telefono"]);
		$tpl->set_var('celular', $form["celular"]);
		$tpl->set_var('email', $form["email"]);
		
		// cargamos el menu principal
		$tpl->set_var('menu', getMenu());
		// parseamos la plantilla
		$tpl->parse('ver_datos_profesional');
	}else{
		// no hay errores, entonces preprocesamos del formulario
		if($form["telefono"] == "Teléfono Profesional (0342-45011XX)") $telefono = "";
		else $telefono = $form["telefono"];
		if($form["celular"] == "Celular Profesional (34245677XX)") $celular = "";
		else $celular = $form["celular"];
		if($form["email"] == "Correo electrónico Profesional") $email = "";
		else $email = $form["email"];
		$matricula = $form["matricula"];
		$domicilio = $form["domicilio"];		
		// guardamos los cambios en la base
		$sql = "UPDATE profesional SET domicilio_profesional='$domicilio',telefono_profesional='$telefono',
				celular_profesional='$celular',email_profesional='$email' WHERE matricula='$matricula';";
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('ver_datos_profesional', 'ver_datos_profesional.tpl');
		// verificamos que el update se ejecute correctamente
		if($conexion->Execute($sql)){
			// no hubo errores
			// consultamos los datos de la base ya actualizados y los mostramos en pantalla
			$profesional = $conexion->GetRow("SELECT * FROM profesional WHERE matricula='$matricula';");
			$tpl->set_var('nombre',$form["nombre"]);
			$tpl->set_var('apellido', $form["apellido"]);
			$tpl->set_var('matricula', $matricula);
			$tpl->set_var('domicilio', $profesional["domicilio_profesional"]);
			$tpl->set_var('telefono', $profesional["telefono_profesional"]);
			$tpl->set_var('celular', $profesional["celular_profesional"]);
			$tpl->set_var('email', $profesional["email_profesional"]);
			$tpl->set_var('fecha_desde', fecha_normal($profesional["fecha_desde"]));			
			$tpl->set_var('error', "Los datos fueron modificados correctamente");
		}else{ 
			// informamos que se produjo un error y mostramos los datos que ingresó el usuario en el form
			$tpl->set_var('nombre',$form["nombre"]);
			$tpl->set_var('apellido', $form["apellido"]);
			$tpl->set_var('matricula', $form["matricula"]);
			$tpl->set_var('domicilio', $form["domicilio"]);
			$tpl->set_var('telefono', $form["telefono"]);
			$tpl->set_var('celular', $form["celular"]);
			$tpl->set_var('email', $form["email"]);
			$tpl->set_var('error', "Se produjo un error al modificar los datos, intentelo nuevamente");
		}
		
		// cargamos el menu principal
		$tpl->set_var('menu', getMenu());
		
		// parseamos la plantilla
		$tpl->parse('ver_datos_profesional');
	}
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>