<?php 
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('nuevo_horario_atencion', 'nuevo_horario_atencion.tpl');

// llenamos los campos de la plantilla
$tpl->set_var('error', "");
// consultamos la lista de turnos
$db_turno = $conexion->Execute("SELECT * FROM turno");
// creamos un array para almacenar el resultado de la consulta
$lista_turno = array();
// cargamos el resultado de la consulta en el array
$primero = true;
foreach($db_turno as $turno){
	if($primero) {
		// de esta forma el primer valor que se muestre en el combo estará seleccionado, de esta forma, si realmente se necesita
		// elegir el primer valor, no hay necesidad de tocar el combo
		array_push($lista_turno, array('id' => $turno['id_turno'], 'valor' => $turno['turno'], 'selected' => 'selected="selected"'));
		$primero = false;	
	}
	else array_push($lista_turno, array('id' => $turno['id_turno'], 'valor' => $turno['turno'], 'selected' => ''));
}
// cargamos el array en el bloque_turno de la plantilla nuevo_horario_atencion.tpl
$tpl->set_loop('bloque_turno', $lista_turno);
// cargamos las horas de inicio y fin
// valores desde 6 am a 23 pm
$lista_hora = array();
array_push($lista_hora, array('id' => '0', 'valor' => "Hora", 'selected' => ''));
for($i=6; $i<24; $i++){
	array_push($lista_hora, array('id' => $i, 'valor' => $i, 'selected' => ''));
}
$tpl->set_loop('bloque_hora_ini', $lista_hora);
$tpl->set_loop('bloque_hora_fin', $lista_hora);
// fin cargamos las horas de inicio y fin 
// cargamos los minutos salteando de a 5, es decir, 0-5-10-15...
// esto es porque el horario de atención nunca va a ser a partir de las 6.03, será a las 6 o 6.05
// de esta forma reducimos las opciones del combo y queda menos saturado
$lista_minuto = array();
array_push($lista_minuto, array('id' => '-1', 'valor' => "Minuto", 'selected' => ''));
for($i=0; $i<60; $i=$i+5){
	array_push($lista_minuto, array('id' => $i, 'valor' => $i, 'selected' => ''));
}
$tpl->set_loop('bloque_minuto_ini', $lista_minuto);
// fin cargamos minuto de inicio
// consultamos el tiempo de cada turno
$db_turno = $conexion->GetRow("SELECT tiempo_turno FROM configuracion");
// seteamos el tiempo de cada turno
$tpl->set_var('tiempo_turno', $db_turno["tiempo_turno"]);

// cargamos el menu principal
$tpl->set_var('menu', getMenu());

// parseamos la plantilla
$tpl->parse('nuevo_horario_atencion');

?>