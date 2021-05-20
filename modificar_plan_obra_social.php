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

// verificamos que el ingreso provenga del botón guardar
if(isset($_REQUEST['guardar'])){
	// si presionó guardar
	// leemos todos los campos del formulario y los almacenamos en un array
	$form = array();
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	// validamos que esten completos los datos obligatorios
	if((empty($form["plan"]))||($form["plan"]=="Plan")){
		// El plan de la obra social es un dato obligatorio
		$datos_obligatorios = false;
		$error_obligatorios = "- Fatan datos obligatorios - Plan de la obra social</br>";
	}
	if($form["select_obra_social"] == 0){
		// El nombre de la obra social es un dato obligatorio
		$datos_obligatorios = false;
		$error_obligatorios = "- Fatan datos obligatorios - Seleccione el nombre de la obra social</br>";
	}
	
	// si estan los datos obligatorios continuamos
	if($datos_obligatorios){
		// validamos el formato de los datos ingresados
		if(! is_alphanumeric($form["plan"], $min_length = 1, $max_length = 100)) {
			$formato_datos = false;
			$error_formato = "- Error de formato - El plan debe contener solo caracteres alfanumericos con una longitud máxima de 100 caracteres</br>";
		}
		if(! is_alphanumeric($form["cobertura"], $min_length = 0, $max_length = 200)) {
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - La cobertura debe contener solo caracteres alfanumericos con una longitud máxima de 200 caracteres</br>";
		}
	}
	
	// verificamos si falto algún dato o hay datos incorrectos
	if(!$formato_datos || !$datos_obligatorios){
		//se encontraron errores
		// volvemos al form nueva persona informando el error
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('ver_datos_plan_obra_social', 'ver_datos_plan_obra_social.tpl');
		// llenamos los campos de la plantilla
		$tpl->set_var('error', $error_obligatorios.$error_formato);
		// consultamos el listado de obras sociales
		$db_obra_social = $conexion->Execute("SELECT id_obra_social,nombre FROM obra_social");
		// creamos un array para almacenar el resultado de la consulta
		$lista_obra_social = array();
		// llenamos el array con el resultado de la consulta
		foreach($db_obra_social as $obra_social){
			if($obra_social['id_obra_social'] == $form["select_obra_social"]){
				// se selecciona el que había elegido el usuario en el form
				array_push($lista_obra_social, array('id' => $obra_social['id_obra_social'], 'valor' => $obra_social['nombre'], 'selected' => 'selected=selected'));
			} else { 
				array_push($lista_obra_social, array('id' => $obra_social['id_obra_social'], 'valor' => $obra_social['nombre'], 'selected' => ''));
			}
		}
		// cargamos el array en el bloque_obra_social de la plantilla ver_datos_plan_obra_social.tpl
		$tpl->set_loop('bloque_obra_social', $lista_obra_social);
		$tpl->set_var('plan', $form["plan"]);
		$tpl->set_var('cobertura', $form["cobertura"]);
		$tpl->set_var('id_plan', $form["id_plan"]);
		
		// cargamos el menú principal
		$tpl->set_var('menu', getMenu());
		// parseamos la plantilla
		$tpl->parse('ver_datos_plan_obra_social');
	}else{
		// no se detectaron inconvenientes
		// preproceso del formulario
		$id_obra_social = $form["select_obra_social"];
		if($form["plan"] == "Plan") $plan = "";
		else $plan = $form["plan"];
		if($form["cobertura"] == "Cobertura") $cobertura = "";
		else $cobertura = $form["cobertura"];
		$id_plan = $form["id_plan"];
		// escribimos la consulta para guardar el plan de la obra social en la base
		$sql = "UPDATE plan_obra_social SET plan='$plan',cobertura='$cobertura',id_obra_social='$id_obra_social' 
				WHERE id_plan='$id_plan';";
		// inicializamos la plantilal de información
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// seteamos las variables de acuerdo al éxito o no de la ejecución de la consulta
		if($conexion->Execute($sql)){
			// notificar que la obra social fue creada
			$tpl->set_var('mensaje', "El plan de la obra social fue modificado correctamente");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "index.php");
			$tpl->set_var('mensaje_std', "Volver al inicio");
			// parseamos la plantilla
			$tpl->parse('info');
		}else{
			// notificar error
			$tpl->set_var('mensaje', "Error al guardar el plan de la obra social en la base de datos, intentelo nuevamente");
			$tpl->set_var('html_adicional', "");
			$id_plan_codificado = base64_encode($id_plan);
			$tpl->set_var('enlace_std', "ver_datos_plan_obra_social.php?id=$id_plan_codificado");
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