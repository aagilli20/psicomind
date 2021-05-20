<?php  
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once("validacion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('listado_horarios_atencion','listado_horarios_atencion.tpl');
// inicializamos contador
$cantidad = 0;

// verificamos si ingreso por el botón filtrar
if(isset($_REQUEST['filtrar'])){
	// filtramos la consulta
	$id_turno = $_REQUEST["select_turno"];
	// consultados la lista de turnos
	$db_turno = $conexion->Execute("SELECT * FROM turno");
	// creamos un array para almacenar los resultados de la consulta
	$lista_turno = array();
	// cargamos el resutlado de la consutla en el array
	foreach($db_turno as $turno){
		if($turno['id_turno'] == $id_turno) {
			array_push($lista_turno, array('id' => $turno['id_turno'], 'valor' => $turno['turno'], 'selected' => 'selected="selected"'));
		} else array_push($lista_turno, array('id' => $turno['id_turno'], 'valor' => $turno['turno'], 'selected' => ''));
	}
	// cargamos el array el bloque_turno de la plantilla listado_horarios_atencion.tpl
	$tpl->set_loop('bloque_turno', $lista_turno);
	// fin turno
	// cargamos listado de horarios de atencion filtrado
	$sql = "SELECT * FROM horario_atencion WHERE turno_id_turno='$id_turno' ORDER BY id_horario_atencion ASC;";
	$sql_count = "SELECT Count(*) FROM horario_atencion WHERE turno_id_turno='$id_turno';";
	// ejecutamos la consulta
	$db_horario_atencion = $conexion->Execute($sql);
	// verificamos la cantidad de resultados
	$db_cant = $conexion->GetRow($sql_count);
	// creamos el array donde almacenaremos los resultados
	$lista_horarios = array();
	// verificamos la existencia de resultados
	if($db_cant["Count(*)"] == 0){
		// no hay resultados para mostrar
		array_push($lista_horarios, array('turno' => "Sin resultados, intente una nueva búsqueda o registre el horario", 'hora_ini' => "",
		'hora_fin' => "", 'id_horario' => ""));
	} else {
		// cargamos los resultados de la consulta en el array
		$db_turno = $conexion->GetRow("SELECT turno FROM turno WHERE id_turno=$id_turno");
		$turno = $db_turno["turno"];
		foreach($db_horario_atencion as $horario){
			array_push($lista_horarios, array('turno' => $turno, 'hora_ini' => $horario['hora_inicio'],
			'hora_fin' => $horario['hora_fin'], 'id_horario' => base64_encode($horario["id_horario_atencion"])));
		}
	}
	// cargamos el array en el bloque_horarios de la plantilla listado_horarios_atencion.tpl
	$tpl->set_loop('bloque_horarios', $lista_horarios);
	// fin lista horarios
} else {
	// mostramos el listado sin filtrar
	// consultamos la lista de turnos
	$db_turno = $conexion->Execute("SELECT * FROM turno");
	// creamos un array para almacenar el resultado de la consulta
	$lista_turno = array();
	$primero = true;
	// cargamos los resutados de la consulta en el array
	foreach($db_turno as $turno){
		if($primero) {
			// si es el primero lo tomamos como seleccionado
			array_push($lista_turno, array('id' => $turno['id_turno'], 'valor' => $turno['turno'], 'selected' => 'selected="selected"'));
			$primero = false;	
		}
		else array_push($lista_turno, array('id' => $turno['id_turno'], 'valor' => $turno['turno'], 'selected' => ''));
	}
	// cargamos el array en el bloque_turno de la plantilla listado_horarios_atencion.tpl
	$tpl->set_loop('bloque_turno', $lista_turno);
	// fin turno
	// consultamos listado de horarios de atencion
	$sql = "SELECT * FROM horario_atencion ORDER BY turno_id_turno, id_horario_atencion ASC;";
	$sql_count = "SELECT Count(*) FROM horario_atencion";
	$db_horario_atencion = $conexion->Execute($sql);
	// obtenemos la cantidad de resultados
	$db_cant = $conexion->GetRow($sql_count);
	// creamos un array para almacenar el resultado del array
	$lista_horarios = array();
	// verificamos la existencia de resultados
	if($db_cant["Count(*)"] == 0){
		// no hay resultados para mostrar
		array_push($lista_horarios, array('turno' => "Sin resultados, intente una nueva búsqueda o registre el horario", 'hora_ini' => "",
		'hora_fin' => "", 'id_horario' => ""));
	}else{
		// cargamos el resultado de la consulta en el array
		foreach($db_horario_atencion as $horario){
			$id_turno = $horario["turno_id_turno"];
			$db_turno = $conexion->GetRow("SELECT turno FROM turno WHERE id_turno=$id_turno");
			$turno = $db_turno["turno"];
			array_push($lista_horarios, array('turno' => $turno, 'hora_ini' => $horario['hora_inicio'],
			'hora_fin' => $horario['hora_fin'], 'id_horario' => base64_encode($horario["id_horario_atencion"])));
		}
	}
	// cargamos el array en el bloque_horarios de la plantilla listado_horarios_atencion.tpl
	$tpl->set_loop('bloque_horarios', $lista_horarios);
	// fin lista horarios
}

// cargamos el menu principal
$tpl->set_var('menu', getMenu());

// parseamos la plantilla
$tpl->parse('listado_horarios_atencion');

?>