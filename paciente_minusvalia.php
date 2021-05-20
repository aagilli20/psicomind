<?php 
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// verificamos que el ingreso sea a partir del botón seleccionar
if(isset($_REQUEST['seleccionar'])){
	// verificamos que haya elegido un paciente
	if(empty($_REQUEST["id_persona"])){
		// no seleccionó ningún paciente
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// seteamos las variables
		// notificar error
		$tpl->set_var('mensaje', "Antes de registrar una minusvalía debe seleccionar un paciente");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "elegir_paciente_minusvalia.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
		die();
	}
	
	// ingresó por a través del botón seleccionar y ha elegido un paciente
	$tpl = new tpleng;
	$tpl->set_file('paciente_minusvalia', 'paciente_minusvalia.tpl');
	// leemos el id paciente del formulario
	$id_paciente = $_REQUEST["id_persona"];
	// llenamos los campos de la plantilla
	$tpl->set_var('id_paciente', $id_paciente);
	// consultar nombre y apellido
	$persona = $conexion->GetRow("SELECT p.nombre_persona,p.apellido_persona FROM persona p,paciente pa 
										WHERE p.sexo_id_sexo=pa.persona_sexo_id_sexo 
										AND tipo_documento_id_tipo_documento=pa.persona_tipo_documento_id_tipo_documento 
										AND nro_documento=pa.persona_nro_documento
										AND pa.id_paciente='$id_paciente'");
	$tpl->set_var('error', ""); 
	$tpl->set_var('nombre', $persona['nombre_persona']);
	$tpl->set_var('apellido', $persona['apellido_persona']);
	// cargamos el listado de minusvalias
	// si la persona tenia minusvalias registradas las levantamos
	$db_rel_pac_minus = $conexion->Execute("SELECT minusvalia_id_minusvalia FROM paciente_minusvalia
											WHERE paciente_id_paciente='$id_paciente'");
	// si la persona no tiene minusvalias registradas levantamos la lista limpia
	$db_minusvalia = $conexion->Execute("SELECT * FROM minusvalia");
	$db_cantidad = $conexion->GetRow("SELECT Count(*) FROM minusvalia");
	$cant_minus = $db_cantidad["Count(*)"];
	// creamos un array para almacenar los resultados de la consulta
	$lista_minusvalia = array();
	$contador = 1;
	$aux_id_minus = "";
	$aux_minus = "";
	// dividimos la carga de opciones en dos columnas
	// procesamos los resultados de la consulta y los vamos almacenando en el array recientemente creado
	foreach($db_minusvalia as $key =>$minusvalia){
		if($key%2){ 
   			// Es impar
			$chequeado1 = "";
			$chequeado2 = "";
			foreach($db_rel_pac_minus as $pac_minus){
				if($minusvalia['id_minusvalia'] == $pac_minus['minusvalia_id_minusvalia']) $chequeado2 = "checked";
				if($aux_id_minus == $pac_minus['minusvalia_id_minusvalia']) $chequeado1 = "checked";		
			}
			
			array_push($lista_minusvalia, array('id_minusvalia1' => $aux_id_minus,
												'descripcion1' => $aux_minus, 
												'chequeado1' => $chequeado1,
												'id_minusvalia2' => $minusvalia['id_minusvalia'],
												'descripcion2' => $minusvalia['desc_minusvalia'], 
												'chequeado2' => $chequeado2,
												'deshabilitado' => ''));	
		}else{ 
   			// Es par 
			$aux_id_minus = $minusvalia['id_minusvalia'];
			$aux_minus = $minusvalia['desc_minusvalia'];
			// si es último
			if($cant_minus == $contador){
				$chequeado1 = "";
				foreach($db_rel_pac_minus as $pac_minus){
					if($aux_id_minus == $pac_minus['minusvalia_id_minusvalia']) $chequeado1 = "checked";
				}
				array_push($lista_minusvalia, array('id_minusvalia1' => $aux_id_minus,
												'descripcion1' => $aux_minus, 
												'chequeado1' => $chequeado1,
												'id_minusvalia2' => '',
												'descripcion2' => '', 
												'chequeado2' => '',
												'deshabilitado' => 'disabled'));	
			}		
		}
	$contador++;
	}
	
	// cargamos el array en el bloque_minusvalia de la plantilla paciente_minusvalia.tpl
	$tpl->set_loop('bloque_minusvalia', $lista_minusvalia);
	
	// cargamos el menú principal
	$tpl->set_var('menu', getMenu());
	// parseamos la plantilla
	$tpl->parse('paciente_minusvalia');
} else {
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>