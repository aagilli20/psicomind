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
	// leemos los campos del formulario y los guardamos en un array
	$form = array();
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	// validamos que esten completos los datos obligatorios
	if((empty($form["nombre"]))||($form["nombre"]=="Nombre")){
		// El nombre de la obra social es un dato obligatorio
		$datos_obligatorios = false;
		$error_obligatorios = "- Fatan datos obligatorios - Nombre de la obra social</br>";
	}
	
	// si los datos obligatorios estan cargados, validamos el formato de los datos
	if($datos_obligatorios){
		// validamos el formato de los datos ingresados
		if(! is_alphanumeric($form["nombre"], $min_length = 1, $max_length = 100)) {
			$formato_datos = false;
			$error_formato = "- Error de formato - El nombre debe contener solo caracteres alfanumericos con una longitud máxima de 100 caracteres</br>";
		}
		if((! is_alphanumeric($form["rnemp"], $min_length = 0, $max_length = 8))&&($form["rnemp"] != "R.N.E.M.P.")) {
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El R.N.E.M.P. debe contener solo caracteres alfanumericos con una longitud máxima de 8 caracteres</br>";
		}
		if((! contains_phone_number($form["telefono"])) && ($form["telefono"] != "Teléfono (0342-45011XX)")){
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El formato del teléfono, debe seguir el siguiente ejemplo: 0342-1000000</br>";
		}
		if(! is_alphanumeric($form["direccion"], $min_length = 0, $max_length = 60)) {
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El domicilio debe contener solo caracteres alfanumericos con una longitud máxima de 60 caracteres</br>";
		}
		if(! is_alphanumeric($form["localidad"], $min_length = 0, $max_length = 60)) {
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - La localidad debe contener solo caracteres alfanumericos con una longitud máxima de 60 caracteres</br>";
		}
		if(! is_alphanumeric($form["provincia"], $min_length = 0, $max_length = 60)) {
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - La provincia debe contener solo caracteres alfanumericos con una longitud máxima de 60 caracteres</br>";
		}
		if(! is_alphanumeric($form["codigo_postal"], $min_length = 0, $max_length = 8)) {
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El código postal debe contener solo caracteres alfanumericos con una longitud máxima de 8 caracteres</br>";
		}
	}
	
	// si hay error en el formato de los datos o falta un dato obligatorio
	if(!$formato_datos || !$datos_obligatorios){
		// volvemos al form de nueva obra social informando el error
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('ver_datos_obra_social', 'ver_datos_obra_social.tpl');
		// llenamos los campos de la plantilla
		$tpl->set_var('error', $error_obligatorios.$error_formato);
		
		$tpl->set_var('nombre', $form["nombre"]);
		$tpl->set_var('rnemp', $form["rnemp"]);
		$tpl->set_var('telefono', $form["telefono"]);
		$tpl->set_var('direccion', $form["direccion"]);
		$tpl->set_var('localidad', $form["localidad"]);
		$tpl->set_var('provincia', $form["provincia"]);
		$tpl->set_var('codigo_postal', $form["codigo_postal"]);
		
		// cargamos el menu principal
		$tpl->set_var('menu', getMenu());
		// parseamos la plantilla
		$tpl->parse('ver_datos_obra_social');
	}else{
		// si no hay errores
		// preproceso del formulario
		$nombre = $form["nombre"];
		if($form["rnemp"] == "R.N.E.M.P.") $rnemp = "";
		else $rnemp = $form["rnemp"];
		if($form["telefono"] == "Teléfono (0342-45011XX)") $telefono = "";
		else $telefono = $form["telefono"];
		if($form["direccion"] == "Dirección") $direccion = "";
		else $direccion = $form["direccion"];
		if($form["localidad"] == "Localidad") $localidad = "";
		else $localidad = $form["localidad"];
		if($form["provincia"] == "Provincia") $provincia = "";
		else $provincia = $form["provincia"];
		if($form["codigo_postal"] == "Código Postal") $codigo_postal = "";
		else $codigo_postal = $form["codigo_postal"];
		// verificamos que no sea un duplicado
		$cantidad = 0;
		if($rnemp == "") $cantidad = $conexion->GetRow("SELECT Count(*) FROM obra_social WHERE nombre='$nombre'");
		else $cantidad = $conexion->GetRow("SELECT Count(*) FROM obra_social WHERE nombre='$nombre' OR rnemp='$rnemp'");
		if($cantidad["Count(*)"] > 0) {
			// si la cantidad es mayor a cero, entonces es duplicado
			$duplicado = true;
			// inicializamos la plantilla
			$tpl = new tpleng;
			$tpl->set_file('info', 'info.tpl');
			// notificar error
			$tpl->set_var('mensaje', "Error al guardar la obra social: Ya existe una obra social registrada con mismo nombre o R.N.E.M.P.");
			$tpl->set_var('html_adicional', "");
			// codificamos el id obra social para pasarlo por get
			$id_obra_social_cod = base64_encode($form["id_os"]);
			$tpl->set_var('enlace_std', "ver_datos_obra_social.php?id=".$id_obra_social_cod);
			$tpl->set_var('mensaje_std', "Volver a Intentarlo");
			// parseamos la plantilla
			$tpl->parse('info');
			// forzamos la detención del script
			die();
		}
		// guardamos la obra social en la base
		$id_obra_social = $form["id_os"];
		$sql = "UPDATE obra_social 
				SET rnemp='$rnemp',nombre='$nombre',telefono='$telefono',direccion='$direccion',localidad='$localidad',provincia='$provincia',cp='$codigo_postal'
				WHERE id_obra_social='$id_obra_social';";
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// ejecutamos el INSERT y verificamos el resultado
		if($conexion->Execute($sql)){
			// notificar que la obra social fue creada
			$tpl->set_var('mensaje', "La obra social fue modificada correctamente");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "index.php");
			$tpl->set_var('mensaje_std', "Volver al inicio");
			// parseamos la plantilla
			$tpl->parse('info');
		}else{
			// notificar error
			$tpl->set_var('mensaje', "Error al modificar la obra social en la base de datos, intentelo nuevamente");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "ver_datos_obra_social.php?id=".$id_obra_social_cod);
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