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
	// validación de datos obligatorios
	if($form["select_hora_ini"] == 0){
		// la hora de inicio es un dato obligatorio
		$datos_obligatorios = false;
		$error_obligatorios = $error_obligatorios."- Fatan datos obligatorios - Hora en que inicia la atención</br>";
	}
	if($form["select_hora_fin"] == 0){
		// la hora de fin es un dato obligatorio
		$datos_obligatorios = false;
		$error_obligatorios = $error_obligatorios."- Fatan datos obligatorios - Hora en que finaliza la atención</br>";
	}
	if($form["select_minuto_ini"] == -1){
		// el minuto de inicio es un dato obligatorio
		$datos_obligatorios = false;
		$error_obligatorios = $error_obligatorios."- Fatan datos obligatorios - Minuto en que inicia la atención</br>";
	}
	if(! isset($form["select_minuto_fin"])){
		// el minuto de inicio es un dato obligatorio
		$datos_obligatorios = false;
		$error_obligatorios = $error_obligatorios."- Fatan datos obligatorios - Minuto en que finaliza la atención</br>";
	}
	// verificamos que se hayan cargado todos los datos obligatorios
	if($datos_obligatorios){
		// datos obligatorios OK
		// validamos la hora de fin
		// consultamos el tiempo de cada turno
		$db_turno = $conexion->GetRow("SELECT tiempo_turno FROM configuracion");
		$turno = $db_turno["tiempo_turno"];
		// validamos que la hora final sea mayor que la hora inicial
		if($turno == 0){
			if($form["select_hora_ini"] >= $form["select_hora_fin"]){
				$formato_datos = false;
				$error_formato = $error_formato."- Error de formato - La hora de fin debe ser mayor a la hora en que se inicia la atención</br>";	
			}  	
		} else {
			if(($form["select_minuto_ini"] + 30) > 59){
				if($form["select_hora_ini"] >= $form["select_hora_fin"]){
					$formato_datos = false;
					$error_formato = $error_formato."- Error de formato - La hora de fin debe ser mayor a la hora en que se inicia la atención</br>";	
				}  		
			} else {
				if($form["select_hora_ini"] > $form["select_hora_fin"]){
					$formato_datos = false;
					$error_formato = $error_formato."- Error de formato - La hora de fin debe ser mayor a la hora en que se inicia la atención</br>";	
				}
			}	
		}
	}
	// verificamos que el formato de los datos sea correcto y esten los obligatorios
	if(!$formato_datos || !$datos_obligatorios){
		// hay error
		// volvemos al form nuevo horario de atencion informando el error
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('nuevo_horario_atencion', 'nuevo_horario_atencion.tpl');
		// llenamos los campos de la plantilla
		$tpl->set_var('error', $error_obligatorios.$error_formato);
		// cargamos la lista de turnos
		// consultamos el listaod de turnos
		$db_turno = $conexion->Execute("SELECT * FROM turno");
		// creamos un array para almacenar los resultados de la consulta
		$lista_turno = array();
		$primero = true;
		// cargamos el resultado de la consutla en el array
		foreach($db_turno as $turno){
			if($turno['id_turno'] == $form["select_turno"]) {
				// forzamos a que el primer turno quede como seleccionado
				array_push($lista_turno, array('id' => $turno['id_turno'], 'valor' => $turno['turno'], 'selected' => 'selected="selected"'));
			}
			else array_push($lista_turno, array('id' => $turno['id_turno'], 'valor' => $turno['turno'], 'selected' => ''));
		}
		// cargamos el array en el bloque_turno de la plantilla nuevo_horario_atencion.tpl
		$tpl->set_loop('bloque_turno', $lista_turno);
		// fin turno
		// cargamos las horas de inicio y fin
		// creamos array para almacenar el listado de horas
		$lista_hora = array();
		// forzamos a que el primer valor diga HORA
		array_push($lista_hora, array('id' => '0', 'valor' => "Hora", 'selected' => ''));
		// generamos el listado de horas de 6 a 23
		for($i=6; $i<24; $i++){
			if($i == $form["select_hora_ini"]) array_push($lista_hora, array('id' => $i, 'valor' => $i, 'selected' => 'selected="selected"'));
			else array_push($lista_hora, array('id' => $i, 'valor' => $i, 'selected' => ''));
		}
		// cargamos el array con las horas en el bloque_hora_ini de la plantilla nuevo_horario_atencion.tpl
		$tpl->set_loop('bloque_hora_ini', $lista_hora);
		unset($lista_hora);
		// creamos array para almacenar el listado de horas
		$lista_hora = array();
		// forzamos a que el primer valor diga HORA
		array_push($lista_hora, array('id' => '0', 'valor' => "Hora", 'selected' => ''));
		// generamos el listado de horas de 6 a 23
		for($i=6; $i<24; $i++){
			if($i == $form["select_hora_fin"]) array_push($lista_hora, array('id' => $i, 'valor' => $i, 'selected' => 'selected="selected"'));
			else array_push($lista_hora, array('id' => $i, 'valor' => $i, 'selected' => ''));
		}
		// cargamos el array con las horas en el bloque_hora_fin de la plantilla nuevo_horario_atencion.tpl
		$tpl->set_loop('bloque_hora_fin', $lista_hora);
		// fin cargamos las horas de inicio y fin 
		// creamos array para almacenar el listado de minutos
		$lista_minuto = array();
		// forzamos a que el primer valor diga HORA
		array_push($lista_minuto, array('id' => '-1', 'valor' => "Minuto", 'selected' => ''));
		// generamos el listado de minutos saltando de a 5
		for($i=0; $i<60; $i=$i+5){
			if($i == $form["select_minuto_ini"]) array_push($lista_minuto, array('id' => $i, 'valor' => $i, 'selected' => 'selected="selected"'));
			array_push($lista_minuto, array('id' => $i, 'valor' => $i, 'selected' => ''));
		}
		// cargamos el array con los minutos en el bloque_minuto_ini de la plantilla nuevo_horario_atencion.tpl
		$tpl->set_loop('bloque_minuto_ini', $lista_minuto);
		// fin cargamos minuto de inicio		
		// consutamos y seteamos el tiempo de cada turno
		$db_turno = $conexion->GetRow("SELECT tiempo_turno FROM configuracion");
		$tpl->set_var('tiempo_turno', $db_turno["tiempo_turno"]);
		
		// cargamos el menu principal
		$tpl->set_var('menu', getMenu());
		// parseamos la plantilla
		$tpl->parse('nuevo_horario_atencion');
	}else{
		// no hay errores
		// preproceso del formulario
		$id_turno = $form["select_turno"];
		$hora_inicio = $form["select_hora_ini"].":".$form["select_minuto_ini"];
		$hora_fin = $form["select_hora_fin"].":".$form["select_minuto_fin"];
		// verificamos que no sea un duplicado
		$cantidad = $conexion->GetRow("SELECT Count(*) FROM horario_atencion WHERE turno_id_turno='$id_turno' AND hora_inicio='$hora_inicio' AND hora_fin='$hora_fin'");
		if($cantidad["Count(*)"] > 0) {
			// si cantidad es mayor a cero, entonces duplicado
			$duplicado = true;
			// inicializamos la plantilla de información
			$tpl = new tpleng;
			$tpl->set_file('info', 'info.tpl');
			// notificar error
			$tpl->set_var('mensaje', "Error al guardar el turno: Ya existe un horario de atención registrado para ese turno, hora de inicio y hora de finalización");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "nuevo_horario_atencion.php");
			$tpl->set_var('mensaje_std', "Volver a Intentarlo");
			// parseamos la plantilla
			$tpl->parse('info');
			// forzamos la detencion del script
			die();
		}	
		
		// guardamos el horario en la base
		$sql = "INSERT INTO horario_atencion(id_horario_atencion,hora_inicio,hora_fin,turno_id_turno) VALUES ('NULL','$hora_inicio', '$hora_fin', '$id_turno');";
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// ejecutamos el INSERT y verificamos el resultado del mismo
		if($conexion->Execute($sql)){
			// notificar que la persona fue creada
			$tpl->set_var('mensaje', "El horario de atención se ha sido registrado");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "index.php");
			$tpl->set_var('mensaje_std', "Volver al inicio");
			// parseamos la plantilla
			$tpl->parse('info');
		}else{
			// notificar error
			$tpl->set_var('mensaje', "Error al guardar el horario de atención, intentelo nuevamente");
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