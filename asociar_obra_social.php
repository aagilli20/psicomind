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
		$tpl->set_var('mensaje', "Debe seleccionar un paciente previamente");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "elegir_paciente_obra_social.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
		die();
	}
	// ingresó por a través del botón seleccionar y ha elegido un paciente
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('asociar_obra_social','asociar_obra_social.tpl');
	// seteamos las variables de la plantilla
	$id_paciente = $_REQUEST["id_persona"];
	$tpl->set_var('id_paciente', $id_paciente);
	$tpl->set_var('id_paciente2', base64_encode($id_paciente));
	// consultar nombre y apellido
	$persona = $conexion->GetRow("SELECT p.nombre_persona,p.apellido_persona,pa.id_plan_obra_social 
										FROM persona p,paciente pa 
										WHERE p.sexo_id_sexo=pa.persona_sexo_id_sexo 
										AND tipo_documento_id_tipo_documento=pa.persona_tipo_documento_id_tipo_documento 
										AND nro_documento=pa.persona_nro_documento
										AND pa.id_paciente='$id_paciente'");
										
	$tpl->set_var('nombre_paciente', $persona['apellido_persona'].", ".$persona['nombre_persona']);
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
		$tpl->set_var('eliminar_os', "<a href='eliminar_paciente_obra_social.php?id=".base64_encode($id_paciente)."'>Eliminar asociación</a>");
	} else {
		$tpl->set_var('obra_social', "Sin obra social");
		$tpl->set_var('plan_os', "");
		$tpl->set_var('eliminar_os', "");
	}
	// cargar todas las obras sociales y planes disponibles
	$sql = "SELECT os.nombre,pos.id_plan,pos.plan FROM obra_social os,plan_obra_social pos
			WHERE pos.id_obra_social=os.id_obra_social;";
	$obra_social_disponibles = $conexion->Execute($sql);
	// consultamso la cantidad
	$sql = "SELECT Count(*) FROM plan_obra_social;";
	$db_cant = $conexion->GetRow($sql);
	// creamos un array para almacenar los resultados
	$lista_os = array();
	if($db_cant["Count(*)"] == 0){
		// sin resultados
		// no hay resultados para mostrar
		// pasamos el array vacío al bloque_os
		$tpl->set_loop('bloque_os', array());
	} else {
		// cargamos los resultados de la consulta en el array
		foreach($obra_social_disponibles as $una_os){
			array_push($lista_os, array('nombre_os' => $una_os['nombre'], 'nombre_plan_os' => $una_os['plan'],
			'id_os' => $una_os['id_plan']));
		}
		// pasamos el array completo al bloque_ficha_hc
		$tpl->set_loop('bloque_os', $lista_os);
	}
	// cargamos el menú principal
	$tpl->set_var('menu', getMenu());
	// parseamos la plantilla
	$tpl->parse('asociar_obra_social');

} else {
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>