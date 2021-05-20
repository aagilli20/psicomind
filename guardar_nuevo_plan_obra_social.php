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

// verificamos que haya ingresado por el botón guardar
if(isset($_REQUEST['guardar'])){
	// leemos los campos del formulario y los almacenamos en un array
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
	// verificamos que los datos obligatorios estén cargados
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
	// verificamos si falló alguna de las validaciones
	if(!$formato_datos || !$datos_obligatorios){
		// volvemos al form nueva persona informando el error
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('nuevo_plan_obra_social', 'nuevo_plan_obra_social.tpl');
		// llenamos los campos de la plantilla
		$tpl->set_var('error', $error_obligatorios.$error_formato);
		// consultamos el listado de obras sociales
		$db_obra_social = $conexion->Execute("SELECT id_obra_social,nombre FROM obra_social");
		// creamos un array para almacenar el resultado de la consulta
		$lista_obra_social = array();
		// forzamos primer valor a seleccione una obra social
		array_push($lista_obra_social, array('id' => '0', 'valor' => 'Seleccione una obra social', 'selected' => ''));
		// cargamos el resultado de la conculta en el array
		foreach($db_obra_social as $obra_social){
			if($obra_social['id_obra_social'] == $form["select_obra_social"]){
				array_push($lista_obra_social, array('id' => $obra_social['id_obra_social'], 'valor' => $obra_social['nombre'], 'selected' => 'selected=selected'));
			} else { 
				array_push($lista_obra_social, array('id' => $obra_social['id_obra_social'], 'valor' => $obra_social['nombre'], 'selected' => ''));
			}
		}
		// cargamos el array en el bloque_obra_social de la plantilla nuevo_plan_obra_social.tpl
		$tpl->set_loop('bloque_obra_social', $lista_obra_social);
		// cargamos el listado de obras sociales
		$tpl->set_var('plan', $form["plan"]);
		$tpl->set_var('cobertura', $form["cobertura"]);

		// cargamos el menu principal
		$tpl->set_var('menu', getMenu());
		// parseamos la plantilla
		$tpl->parse('nuevo_plan_obra_social');
	}else{
		// no se detectaron errores
		// preproceso del formulario
		$id_obra_social = $form["select_obra_social"];
		if($form["plan"] == "Plan") $plan = "";
		else $plan = $form["plan"];
		if($form["cobertura"] == "Cobertura") $cobertura = "";
		else $cobertura = $form["cobertura"];
		// verificamos que no sea un duplicado
		$cantidad = 0;
		$cantidad = $conexion->GetRow("SELECT Count(*) FROM plan_obra_social WHERE id_obra_social='$id_obra_social' AND plan='$plan'");
		if($cantidad["Count(*)"] > 0) {
			// si la cantidad de resultados es mayor a cero, entonces plan duplicado
			$duplicado = true;
			// inicializamos la plantilla
			$tpl = new tpleng;
			$tpl->set_file('info', 'info.tpl');
			// notificar error
			$tpl->set_var('mensaje', "Error al guardar el plan de la obra social: Ya existe un plan registrado con el mismo nombre para la obra social seleccionada");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "nueva_obra_social.php");
			$tpl->set_var('mensaje_std', "Volver a Intentarlo");
			// parseamos la plantilla
			$tpl->parse('info');
			// forzamos la detención del script
			die();
		}
		// guardamos el plan de la obra social en la base
		$sql = "INSERT INTO plan_obra_social(id_plan,plan,cobertura,id_obra_social) 
				  VALUES ('NULL', '$plan', '$cobertura', '$id_obra_social');";
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// ejecutamos el INSERT y verificamos su resultado
		if($conexion->Execute($sql)){
			// notificar que la obra social fue creada
			$tpl->set_var('mensaje', "El plan de la obra social fue creado correctamente");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "index.php");
			$tpl->set_var('mensaje_std', "Volver al inicio");
			// parseamos la plantilla
			$tpl->parse('info');
		}else{
			// notificar error
			$tpl->set_var('mensaje', "Error al guardar el plan de la obra social en la base de datos, intentelo nuevamente");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "nueva_obra_social.php");
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