<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("fechas.php");
require_once("menu.php");

// inicializamos variables para el manejo de errores
$hay_error = false;
$msg_error = "";

// verificamos que se reciba el parametro ID por GET
if(isset($_GET['id'])){
	// verificamos que el ID no sea nulo
	if($_GET["id"] == ""){
		// ID nulo
		// inicializamos la plantilla para notificar el error
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// seteamos las variables
		$tpl->set_var('mensaje', "Debe elegir un día no laboral válido");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "listado_dias_no_laborales.php");
		$tpl->set_var('mensaje_std', "Volver a intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');	
		// forzamos la detención del script
		die();
	}
	// se recibió el ID
	// hay que decodificar el id
	$id_dia_no_laboral = base64_decode($_GET['id']);
	
	// verificamos que hasta aca no haya error
	if(! $hay_error){
		// eliminamos el horario de atención
		$sql = "DELETE FROM dia_no_laboral WHERE id_dia_no_laboral='$id_dia_no_laboral';";
		// ejecutamos el DELETE y verificamos su resultado
		if(! $conexion->Execute($sql)) {
			// error
			$hay_error = true;
			$msg_error = "Se produjo un error inesperado al eliminar el día no laboral, intentelo nuevamente".$conexion->ErrorMsg();		
		}
	}
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('listado_dias_no_laborales','listado_dias_no_laborales.tpl');
	// inicializamos contador
	$cantidad = 0;
	// mostramos el listado sin filtrar
	// consultamos el listado de días no laborales y la cantidad de resultados
	$sql = "SELECT * FROM dia_no_laboral WHERE fecha>=CURDATE() ORDER BY fecha ASC;";
	$sql_count = "SELECT Count(*) FROM dia_no_laboral WHERE fecha>=CURDATE();";
	$db_dia_no_laboral = $conexion->Execute($sql);
	$db_cant = $conexion->GetRow($sql_count);
	// creamos un array para almacenar los resultados
	$lista_dias = array();
	// verificamos la existencia de resultados
	if($db_cant["Count(*)"] == 0){
		// no hay resultados para mostrar
		array_push($lista_dias, array('fecha' => "Sin resultados, intente una nueva búsqueda o registre el día no laboral", 'motivo' => "",
		'id_dia_no_laboral' => ""));
	}else{
		// cargamos el resultado de la consulta en el array
		foreach($db_dia_no_laboral as $dia){
			$id_dia_no_laboral = $dia["id_dia_no_laboral"];
			$fecha = $dia["fecha"];
			$motivo = $dia["motivo"];
			array_push($lista_dias, array('fecha' => fecha_normal($fecha), 'motivo' => $motivo, 
			'id_dia_no_laboral' => base64_encode($id_dia_no_laboral)));
		}
	}
	// cargamos el array en el bloque_dias de la plantilla listado_dias_no_laborales.tpl
	$tpl->set_loop('bloque_dias', $lista_dias);
	// fin lista dia no laboral
	if($hay_error) $tpl->set_var('error', $msg_error);
	else $tpl->set_var('error', "El día no laboral ha sido eliminado exitosamente");

	// cargammos el menú principal
	$tpl->set_var('menu', getMenu());

	// parseamos la plantilla
	$tpl->parse('listado_dias_no_laborales');	
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>