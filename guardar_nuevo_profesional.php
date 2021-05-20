<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");
require_once("menu.php");

// inicialización de variables para el manejo de errores
$datos_obligatorios = true;
$formato_datos = true;
$error_obligatorios = "";
$error_formato = "";

// verificamos que haya ingresado a través del botón guardar
if(isset($_REQUEST['guardar'])){
	// leemos los campos del formulario y los almacenamos en un array
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
		// validamos el formato de los datos ingresados
		if(! is_alphanumeric($form["matricula"], $min_length = 1, $max_length = 14)) {
			$formato_datos = false;
			$error_formato = "- Error de formato - La matrícula debe contener solo caracteres alfanumericos con una longitud máxima de 14 caracteres</br>";
		}
		if(! is_alphanumeric($form["domicilio"], $min_length = 1, $max_length = 60)) {
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El domicilio del Profesional debe contener solo caracteres alfanumericos con una longitud máxima de 40 caracteres</br>";
		}
		if((! contains_phone_number($form["telefono"])) && ($form["telefono"] != "Teléfono Profesional (0342-45011XX)")){
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El formato del teléfono, debe seguir el siguiente ejemplo: 0342-1000000</br>";
		}
		if((! contains_phone_number($form["celular"])) && ($form["celular"] != "Celular Profesional (34245677XX)")){
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El formato del celular, debe seguir el siguiente ejemplo: 0342-1000000</br>";
		}
		if((! is_email($form["email"])) && ($form["email"] != "Correo electrónico Profesional")){
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El formato del email, debe seguir el siguiente ejemplo: username@dominio.com</br>";
		}
	}
	
	// verificamos que no existan errores
	if(!$formato_datos || !$datos_obligatorios){
		// hay error
		// volvemos al form nuevo profesional informando el error
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('nuevo_profesional', 'nuevo_profesional.tpl');
		// llenamos los campos de la plantilla
		$tpl->set_var('error', $error_obligatorios.$error_formato);
		// cargamos los datos ingresados por el usuario
		$tpl->set_var('id_persona', $form["id_persona"]);
		$tpl->set_var('error', $error_obligatorios.$error_formato); 
		$tpl->set_var('nombre',$form["nombre"]);
		$tpl->set_var('apellido', $form["apellido"]);
		$tpl->set_var('matricula', $form["matricula"]);
		$tpl->set_var('domicilio', $form["domicilio"]);
		$tpl->set_var('telefono', $form["telefono"]);
		$tpl->set_var('celular', $form["celular"]);
		$tpl->set_var('email', $form["email"]);
		
		// cargamos el menú principal
		$tpl->set_var('menu', getMenu());
		// parseamos la plantilla
		$tpl->parse('nuevo_profesional');
	}else{
		// no hay error
		// preproceso del formulario
		// leemos el id persona del formulario
		$id_persona = $form["id_persona"];
		$id_sexo = substr($id_persona, 0, 1);
		$id_tipo_doc = substr($id_persona, 1, 1);
		$fin = strlen($id_persona) - 1;
		$nro_documento = substr($id_persona, 2, $fin);
		if($form["telefono"] == "Teléfono Profesional (0342-45011XX)") $telefono = "";
		else $telefono = $form["telefono"];
		if($form["celular"] == "Celular Profesional (34245677XX)") $celular = "";
		else $celular = $form["celular"];
		if($form["email"] == "Correo electrónico Profesional") $email = "";
		else $email = $form["email"];
		$matricula = $form["matricula"];
		$domicilio = $form["domicilio"];
		// verificamos que no sea un duplicado - persona
		$cantidad = $conexion->GetRow("SELECT Count(*) FROM profesional WHERE persona_nro_documento='$nro_documento' 
																	AND persona_sexo_id_sexo='$id_sexo' 
																	AND persona_tipo_documento_id_tipo_documento='$id_tipo_doc'");
		// si la cantidad de resultados es mayor a cero, profesional duplicado
		if($cantidad["Count(*)"] > 0) {
			// informamos error
			$duplicado = true;
			// inicializamos la plantilla
			$tpl = new tpleng;
			$tpl->set_file('info', 'info.tpl');
			// notificar error
			$tpl->set_var('mensaje', "Error al guardar el profesional: Ya existe un profesional registrado con el mismo sexo, tipo y número de documento");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "elegir_persona_profesional.php");
			$tpl->set_var('mensaje_std', "Volver a Intentarlo");
			// parseamos la plantilla
			$tpl->parse('info');
			// forzamos la detención del script
			die();
		}
		// verificamos que no sea un duplicado - matricula
		$cantidad = $conexion->GetRow("SELECT Count(*) FROM profesional WHERE matricula='$matricula';");
		// si la cantidad es mayor a cero, matricula duplicada
		if($cantidad["Count(*)"] > 0) {
			// informamos error
			$duplicado = true;
			// inicializamos la plantilla
			$tpl = new tpleng;
			$tpl->set_file('info', 'info.tpl');
			// notificar error
			$tpl->set_var('mensaje', "Error al guardar el profesional: Ya existe un profesional registrado con el mismo número de matrícula");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "elegir_persona_profesional.php");
			$tpl->set_var('mensaje_std', "Volver a Intentarlo");
			// parseamos la plantilla
			$tpl->parse('info');
			// forzamos la detención del script
			die();
		}
		// tomamos la fecha actual
		$hoy = date("Y-m-d");
		// si pasó todas las validaciones
		// guardamos el profesional en la base
		$sql = "INSERT INTO profesional(matricula,domicilio_profesional,telefono_profesional,celular_profesional,email_profesional,
		activo_profesional,fecha_desde,persona_nro_documento,persona_sexo_id_sexo,persona_tipo_documento_id_tipo_documento) 
		VALUES ('$matricula', '$domicilio', '$telefono', '$celular', '$email', '1', 
				  '$hoy', '$nro_documento', '$id_sexo', '$id_tipo_doc');";
		// inicializamoas la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// ejecutamos el script y verificamos su resultado
		if($conexion->Execute($sql)){
			// notificar que la persona fue creada
			$tpl->set_var('mensaje', "El profesional fue registrado correctamente");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "index.php");
			$tpl->set_var('mensaje_std', "Volver al inicio");
			// parseamos la plantilla
			$tpl->parse('info');
		}else{
			// notificar error
			$tpl->set_var('mensaje', "Error al guardar el profesional en la base de datos, intentelo nuevamente");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "elegir_persona_profesional.php");
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