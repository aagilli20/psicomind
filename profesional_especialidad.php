<?php 
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// verificamos que ingresó por el botón seleccionar
if(isset($_REQUEST['seleccionar'])){
	// verificamos que haya elegido un profesional
	if(empty($_REQUEST["matricula"])){
		// no seleccionó ningun profesional
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// seteamos las variables
		// notificar error
		$tpl->set_var('mensaje', "Antes de asociar una especialidad debe seleccionar un profesional");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "elegir_profesional_especialidad.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
		// forzamos la detención del script
		die();
	}
	// ingresó por el botón seleccionar y ha elegido un profesional
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('especialidad_profesional', 'profesional_especialidad.tpl');
	// leemos el id persona del formulario
	$matricula = $_REQUEST["matricula"];
	// llenamos los campos de la plantilla
	$tpl->set_var('matricula', $matricula);
	// consultar nombre y apellido
	$persona = $conexion->GetRow("SELECT p.nombre_persona,p.apellido_persona FROM persona p,profesional pr 
										WHERE p.sexo_id_sexo=pr.persona_sexo_id_sexo 
										AND tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento 
										AND nro_documento=pr.persona_nro_documento
										AND pr.matricula='$matricula'");
	$tpl->set_var('error', ""); 
	$tpl->set_var('nombre', $persona['nombre_persona']);
	$tpl->set_var('apellido', $persona['apellido_persona']);
	// cargamos el listado de especialidades
	// si la persona tenia especialidades registradas las levantamos
	$db_rel_prof_espe = $conexion->Execute("SELECT especialidad_id_especialidad FROM profesional_especialidad
											WHERE profesional_matricula='$matricula'");
	// si la persona no tiene especialidades registradas levantamos la lista limpia
	$db_especialidad = $conexion->Execute("SELECT * FROM especialidad");
	$db_cantidad = $conexion->GetRow("SELECT Count(*) FROM especialidad");
	$cant_espe = $db_cantidad["Count(*)"];
	// creamos un array para almacenar los resultados de la consulta
	$lista_especialidad = array();
	$contador = 1;
	$aux_id_espe = "";
	$aux_espe = "";
	// dividimos la carga de opciones en dos columnas
	// las opciones ya procesadas serán cargadas en el array creado anteriormente
	foreach($db_especialidad as $key =>$especialidad){
		if($key%2){ 
   			// Es impar
			$chequeado1 = "";
			$chequeado2 = "";
			foreach($db_rel_prof_espe as $prof_espe){
				if($especialidad['id_especialidad'] == $prof_espe['especialidad_id_especialidad']) $chequeado2 = "checked";
				if($aux_id_espe == $prof_espe['especialidad_id_especialidad']) $chequeado1 = "checked";		
			}
			
			array_push($lista_especialidad, array('id_especialidad1' => $aux_id_espe,
												'descripcion1' => $aux_espe, 
												'chequeado1' => $chequeado1,
												'id_especialidad2' => $especialidad['id_especialidad'],
												'descripcion2' => $especialidad['especialidad'], 
												'chequeado2' => $chequeado2,
												'deshabilitado' => ''));	
		}else{ 
   			// Es par 
			$aux_id_espe = $especialidad['id_especialidad'];
			$aux_espe = $especialidad['especialidad'];
			// si es último
			if($cant_espe == $contador){
				$chequeado1 = "";
				$chequeado2 = "";
				foreach($db_rel_prof_espe as $prof_espe){
					if($aux_id_espe == $prof_espe['especialidad_id_especialidad']) $chequeado1 = "checked";
				}
				array_push($lista_especialidad, array('id_especialidad1' => $aux_id_espe,
												'descripcion1' => $aux_espe, 
												'chequeado1' => $chequeado1,
												'id_especialidad2' => '',
												'descripcion2' => '', 
												'chequeado2' => '',
												'deshabilitado' => 'disabled'));	
			}		
		}
	$contador++;
	}
	// cargamos el array en el bloque_especialidad de la plantilla profesional_especialidad.tpl
	$tpl->set_loop('bloque_especialidad', $lista_especialidad);
	
	// cargamos el menú principal
	$tpl->set_var('menu', getMenu());
	// parseamos la plantilla
	$tpl->parse('especialidad_profesional');
} else {
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>