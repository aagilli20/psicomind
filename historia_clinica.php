<?php  
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once("fechas.php");
require_once('tpleng.class.php');
require_once('menu.php');

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
		$tpl->set_var('mensaje', "Antes de visualizar la historia clínica debe seleccionar un paciente");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "elegir_paciente_historia_clinica.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
		die();
	}
	// ingresó por a través del botón seleccionar y ha elegido un paciente
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('historia_clinica','historia_clinica.tpl');
	// obtenemos el nombre de usaurio de la variable de sesión
	$usuario = $_SESSION["usuario"];
	// seteamos las variables de la plantilla
	$id_paciente = $_REQUEST["id_persona"];
	$tpl->set_var('id_paciente', $id_paciente);
	$tpl->set_var('id_paciente2', base64_encode($id_paciente));
	// consultar nombre y apellido
	$persona = $conexion->GetRow("SELECT p.nombre_persona,p.apellido_persona,p.sexo_id_sexo,p.fecha_nacimiento,pa.id_plan_obra_social 
										FROM persona p,paciente pa 
										WHERE p.sexo_id_sexo=pa.persona_sexo_id_sexo 
										AND tipo_documento_id_tipo_documento=pa.persona_tipo_documento_id_tipo_documento 
										AND nro_documento=pa.persona_nro_documento
										AND pa.id_paciente='$id_paciente'");
										
	$tpl->set_var('nombre_paciente', $persona['apellido_persona'].", ".$persona['nombre_persona']);
	$tpl->set_var('url_img', "./style/images/sin_imagen_usuario.jpg");
	if($persona["sexo_id_sexo"] = "1") $tpl->set_var('sexo', "F");
	else $tpl->set_var('sexo', "M");
	$tpl->set_var('fe_nac', fecha_normal($persona['fecha_nacimiento']));
	$tpl->set_var('edad', calcular_edad($persona['fecha_nacimiento']));
	// verificamos que el paciente tenga historia clínica
	$tiene_historia_clinica = false;
	$db_cant = $conexion->GetRow("SELECT Count(*) FROM historia_clinica WHERE id_paciente='$id_paciente'");
	if($db_cant["Count(*)"] == 0) {
		$tpl->set_var('fecha_desde', "<b>El paciente no registra historia clínica</b>");
		$tpl->set_var('enlace', "Cargar una historia clínica");
		$tpl->set_var('url_enlace', "#");
	} else {
		// consultamos los datos de la historia clinica
		$tiene_historia_clinica = true;
		$historia_clinica = $conexion->GetRow("SELECT id_historia_clinica,fecha_desde FROM historia_clinica WHERE id_paciente='$id_paciente'");
		$tpl->set_var('fecha_desde', fecha_normal($historia_clinica['fecha_desde']));
		$tpl->set_var('enlace', "");
		$tpl->set_var('url_enlace', "#");
	}
	// fichas de la historia clínica
	if($tiene_historia_clinica){
		$id_historia_clinica = $historia_clinica["id_historia_clinica"];
		// consultamos las fichas de la historia clínica
		$db_fichas_hc = $conexion->Execute("SELECT id_ficha,fecha_ficha,motivo_consulta,id_usuario FROM ficha_historia_clinica 
											WHERE id_historia_clinica='$id_historia_clinica'");
		// obtenemos la cantidad de resultados													
		$db_cant = $conexion->GetRow("SELECT Count(*) FROM ficha_historia_clinica WHERE id_historia_clinica='$id_historia_clinica'");		
	} else {
		// estas operaciones son para que no tire error por no existir las variables
		// consultamos las fichas de la historia clínica
		$db_fichas_hc = $conexion->Execute("");
		// obtenemos la cantidad de resultados
		$db_cant = $conexion->GetRow("");
	}
	// creamos el array donde almacenaremos los resultados
	$lista_fichas_hc = array();
	// verificamos la existencia de resultados
	if($db_cant["Count(*)"] == 0){
		// no hay resultados para mostrar
		// pasamos el array vacío al bloque_ficha_hc
		$tpl->set_loop('bloque_ficha_hc', array());
	} else {
		// cargamos los resultados de la consulta en el array
		foreach($db_fichas_hc as $una_ficha){
			array_push($lista_fichas_hc, array('id' => $una_ficha['id_ficha'], 'fecha' => fecha_normal($una_ficha['fecha_ficha']),
			'motivo' => $una_ficha['motivo_consulta'], 'usuario' => $una_ficha['id_usuario'],
			'id2' => base64_encode($una_ficha['id_ficha'])));
		}
		// pasamos el array completo al bloque_ficha_hc
		$tpl->set_loop('bloque_ficha_hc', $lista_fichas_hc);
	}
	
	// historial farmacologico
	if($tiene_historia_clinica){
		$id_historia_clinica = $historia_clinica["id_historia_clinica"];
		// consultamos los registros del historial farmacologico
		$db_hist_farm = $conexion->Execute("SELECT m.nombre,m.droga,hf.id_aplicacion,hf.fecha_aplicacion,hf.dosis,hf.id_usuario 
											FROM historial_farmacologico hf, medicamento m
											WHERE hf.id_historia_clinica='$id_historia_clinica'");
		// obtenemos la cantidad de resultados													
		$db_cant = $conexion->GetRow("SELECT Count(*) FROM historial_farmacologico WHERE id_historia_clinica='$id_historia_clinica'");		
	} else {
		// estas operaciones son para que no tire error por no existir las variables
		// consultamos los registros del historial farmacologico
		$db_hist_farm = $conexion->Execute("");
		// obtenemos la cantidad de resultados
		$db_cant = $conexion->GetRow("");
	}
	// creamos el array donde almacenaremos los resultados
	$lista_hist_farm = array();
	// verificamos la existencia de resultados
	if($db_cant["Count(*)"] == 0){
		// no hay resultados para mostrar
		// pasamos el array vacío al bloque_hist_farm
		$tpl->set_loop('bloque_hist_farm', array());
	} else {
		// cargamos los resultados de la consulta en el array
		foreach($db_hist_farm as $un_medicamento){
			array_push($lista_hist_farm, array('medicamento' => $un_medicamento['nombre'], 'droga' => $un_medicamento['droga'],
			'fecha' => fecha_normal($un_medicamento['fecha_aplicacion']), 'dosis' => $un_medicamento['dosis'],'usuario' => $un_medicamento['id_usuario'],
			'id2' => base64_encode($un_medicamento['id_aplicacion'])));
		}
		// pasamos el array completo al bloque_hist_farm
		$tpl->set_loop('bloque_hist_farm', $lista_hist_farm);
	}
	// minusvalias del paciente
	// consultamos las minusvalias del paciente
	$db_minusvalias = $conexion->Execute("SELECT desc_minusvalia FROM paciente_minusvalia pm, minusvalia m 
										  WHERE pm.paciente_id_paciente=$id_paciente AND pm.minusvalia_id_minusvalia=m.id_minusvalia");
	// obtenemos la cantidad de resultados
	$db_cant = $conexion->GetRow("SELECT Count(*) FROM paciente_minusvalia WHERE paciente_id_paciente=$id_paciente");
	$str_minusvalias = "";
	if($db_cant["Count(*)"] == 0){
		// no hay resultados para mostrar
		// informamos que el paciente no tiene minusvalías
		$str_minusvalias = " - Ninguna";
	} else {
		// cargamos los resultados de la consulta en el array
		foreach($db_minusvalias as $minusvalia){
			$str_minusvalias = $str_minusvalias." - ".$minusvalia["desc_minusvalia"];
		}
	}
	$tpl->set_var('minusvalias', $str_minusvalias);
	// cargamos los datos de la obra social
	$id_plan_os = $persona["id_plan_obra_social"];
	// consultamos los datos de la obra social y plan seleccionados
	// siempre que no sea nulo el id_plan del paciente
	if($id_plan_os != NULL){
		$sql = "SELECT os.nombre,pos.plan FROM obra_social os,plan_obra_social pos
				WHERE pos.id_obra_social=os.id_obra_social AND id_plan='$id_plan_os'";
		$obra_social_paciente = $conexion->GetRow($sql);
		$tpl->set_var('obra_social', $obra_social_paciente["nombre"]);
		$tpl->set_var('plan_os', $obra_social_paciente["plan"]); 
	} else {
		$tpl->set_var('obra_social', "Sin obra social");
		$tpl->set_var('plan_os', "");
	}
	// cargamos el menú principal
	$tpl->set_var('menu', getMenu());
	// parseamos la plantilla
	$tpl->parse('historia_clinica');

} else {
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>