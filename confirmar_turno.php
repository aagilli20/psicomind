<?php  
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once("fechas.php");
require_once("validacion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// inicializamos variables para el manejo de errores
$hay_error = false;
$error_msg = "";

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('confirmar_turno','confirmar_turno.tpl');

// verificamos si ingreso por el botón seleccionar
if(isset($_REQUEST['seleccionar'])){
	$matricula = $_REQUEST["id_profesional"];
	$id_paciente = $_REQUEST["id_paciente"];
	// verificamos que se haya elegido una fecha para el turno
	if(empty($_REQUEST["fe_turno"]) || ($_REQUEST["fe_turno"] == "DD/MM/AAAA")){
		// no hay fecha
		// inicializamos la plantilla para informarlo
		$tpl2 = new tpleng;
		$tpl2->set_file('info', 'info.tpl');
		// seteamos las variables
		// notificar que debe seleccionar una fecha
		$tpl2->set_var('mensaje', "Debe seleccionar una fecha válida");
		$tpl2->set_var('html_adicional', "");
		// codificamos el ID paicente
		$id_paciente2 = base64_encode($id_paciente);
		// codificamos el numero de matricula
		$matricula2 = base64_encode($matricula);
		// habilitamos un enlace para volver a la pantalla anterior
		// los parametros de matrícula e id paciente se pasan por GET
		$tpl2->set_var('enlace_std', "consignar_turno.php?id1=".$id_paciente2."&id2=".$matricula2);
		$tpl2->set_var('mensaje_std', "Volver a intentarlo");
		// parseamos la plantilla
		$tpl2->parse('info');
		// forzamos la detención del script
		die();
	}
	// ha seleccionado una fecha para el turno
	// calculamos el día
	$dia_semana = date('N', strtotime(fecha_mysql($_REQUEST["fe_turno"])));
	// obtenemos el nombre del día
	$valor_dia =  $conexion->GetRow("SELECT valor_dia FROM dia WHERE id_dia='$dia_semana'");
	$tpl->set_var("dia_turno", $valor_dia["valor_dia"]." - ".$_REQUEST["fe_turno"]);
	$tpl->set_var("dia_turno2", $_REQUEST["fe_turno"]);
	// mostramos el turno que ha seleccionado antes
	// obtenemos el nombre del turno elegido
	$id_turno = $_REQUEST["select_turno"];
	$tpl->set_var("id_turno", $id_turno);
	$turno =  $conexion->GetRow("SELECT turno FROM turno WHERE id_turno='$id_turno'");
	$tpl->set_var("turno_elegido", $turno["turno"]);
	// configuramos el botón volver
	// codificamos el ID paicente
	$id_paciente2 = base64_encode($id_paciente);
	// codificamos el numero de matricula
	$matricula2 = base64_encode($matricula);
	$tpl->set_var("id_paciente2", $id_paciente2);
	$tpl->set_var("id_profesional2", $matricula2);
	$tpl->set_var("id_paciente", $id_paciente);
	$tpl->set_var("id_profesional", $matricula);
	// verificamos que el profesional atienda en el día y turno seleccionados
	// primero consultamos el listado de horarios de atención del profesional
	$sql = "SELECT id_horario_atencion,id_dia FROM horario_atencion_profesional WHERE matricula_profesional LIKE '$matricula'";
	$horarios_profesional = $conexion->Execute($sql);
	// la verificación se hará por horario
	$id_horario_atencion_coincidente = -1;
	foreach($horarios_profesional as $un_horario){
		// verificamos que atienda en el día seleccionado
		if($dia_semana == $un_horario["id_dia"]){
			// atiende en el dia seleccionado
			$id_horario_atencion_coincidente = $un_horario["id_horario_atencion"];
		}
			// no atiende el día seleccionado
			$hay_error = true;
			$error_msg = "El profesional no atiende el día seleccionado";
	}
	// si se encontro un día coincidente, entonces no hay error hasta aca
	if($id_horario_atencion_coincidente != -1) $hay_error = false;
	// verificar que no haya errores
	if(! $hay_error){
		// verificamos que atienda en el turno seleccionado
		$sql = "SELECT hora_inicio, turno_id_turno, hora_fin FROM horario_atencion WHERE id_horario_atencion='$id_horario_atencion_coincidente'";		
		$horario_atencion = $conexion->GetRow($sql);
		if($id_turno == $horario_atencion["turno_id_turno"]){
			// el profesional atiende en el turno y horario elegidos
			// verificar que la fecha seleccionada no sea un día no laboral
			$fecha_turno_sql = fecha_mysql($_REQUEST["fe_turno"]);
			$sql = "SELECT Count(*) FROM dia_no_laboral WHERE fecha='$fecha_turno_sql'";
			$cantidad = $conexion->GetRow($sql);
			if($cantidad["Count(*)"] == 0){
				// no es día no laboral
				// consultamos el tiempo de cada turno
				$sql = "SELECT tiempo_turno FROM configuracion";
				$tiempo_turno = $conexion->GetRow($sql);
				// hora de inicio de los turnos
				$hora_ini = $horario_atencion["hora_inicio"];
				$hora_fin = $horario_atencion["hora_fin"];
				// generacion de turnos
				// creamos array para almacenar los horarios
				$turnos_disponibles = array();
				// asignamos el tiempo de cada turno a una variable común
				$tiempo_turno_time = 0;
				// seteamos el timpo del turno en segundos
				if($tiempo_turno["tiempo_turno"] == 30) $tiempo_turno_time= 1800;
				else $tiempo_turno_time = 3600;
				// pasamos la hora de inicio a variables de tipo temporales
				$hora_ini_time = strtotime($hora_ini);
				// pasamos la hora de fin a variables de tipo temporales
				$hora_fin_time = strtotime($hora_fin);
				// consultamos el listado de turnos otorgados por el profesional seleccionado para el día elegido
				// esto es para deshabilitar los ya otorgados para ese día
				$sql = "SELECT horario FROM turno_otorgado 
						WHERE fecha_turno='$fecha_turno_sql' AND id_profesional='$matricula' AND id_turno='$id_turno';";
				$lista_turnos_otorgados = $conexion->Execute($sql);
				// cargamos el listado de turnos disponibles			
				while($hora_ini_time < $hora_fin_time){
					$hora_ini_table = date("H:i", $hora_ini_time);
					// verificamos si el turno esta dado
					$turno_otorgado = false;
					foreach($lista_turnos_otorgados as $un_turno){
						if($un_turno["horario"] == $hora_ini_table) $turno_otorgado = true;
					}
					if($turno_otorgado){
						array_push($turnos_disponibles, array('hora_ini' => $hora_ini_table, 'color' => "color:red;",
						'disabled' => "disabled=disabled"));
					} else {
						array_push($turnos_disponibles, array('hora_ini' => $hora_ini_table, 'color' => "",'disabled' => ""));
					}
					// sumamos el tiempo de un turno
					$hora_ini_time = $hora_ini_time + $tiempo_turno_time;
				}
				$tpl->set_loop('bloque_turnos_disp', $turnos_disponibles);
				
				// consultamos el nombre y apellido del paciente seleccionado
				$sql = "SELECT p.nombre_persona, p.apellido_persona FROM persona p, paciente pa 
						WHERE p.nro_documento=pa.persona_nro_documento AND p.sexo_id_sexo=pa.persona_sexo_id_sexo
						AND p.tipo_documento_id_tipo_documento=pa.persona_tipo_documento_id_tipo_documento
						AND pa.id_paciente='$id_paciente';";
				$db_paciente = $conexion->GetRow($sql);
				// consultamos el nombre y apellido del profesional seleccionado
				$sql = "SELECT p.nombre_persona, p.apellido_persona FROM persona p, profesional pr 
				WHERE p.nro_documento=pr.persona_nro_documento AND p.sexo_id_sexo=pr.persona_sexo_id_sexo
				AND p.tipo_documento_id_tipo_documento=pr.persona_tipo_documento_id_tipo_documento
				AND pr.matricula='$matricula';";
				$db_profesional = $conexion->GetRow($sql);
				// seteamos las variables del template
				$tpl->set_var("nombre_paciente", $db_paciente["apellido_persona"].", ".$db_paciente["nombre_persona"]);
				$tpl->set_var("nombre_profesional", $db_profesional["apellido_persona"].", ".$db_profesional["nombre_persona"]);				
				$tpl->set_var('error', "");
			} else {
				$hay_error = true;
				$error_msg = "El día elegido está registrado como día no laboral para el profesional seleccionado";	
			}
		} else {
			$hay_error = true;
			$error_msg = "El profesional no atiende en el turno seleccionado los días: ".$valor_dia["valor_dia"];		
		}
	}
		
	if($hay_error){
		$turnos_disponibles = array();
		array_push($turnos_disponibles, array('hora_ini' => $error_msg, 'disabled' => "disabled=disabled"));
		$tpl->set_loop('bloque_turnos_disp', $turnos_disponibles);
	}

}
// cargamos el menu principal
$tpl->set_var('menu', getMenu());
// parseamos la planitlla
$tpl->parse('confirmar_turno');

?>