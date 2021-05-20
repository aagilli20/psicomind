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

// verificamos que el acceso provenga del botón seleccionar
if(isset($_REQUEST['seleccionar'])){
	// ingreso válido
	// creamos un array para almacenar los campos de formulario
	$form = array();
	// leemos los campos del formulario y los pasamos al array
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('buscar_turno_optimo','buscar_turno_optimo.tpl');
	// no hay errores, entonces parseamos la plantilla
	$tpl->set_var('id_paciente2', base64_encode($form["id_paciente"]));
	// verificar que haya seleccionado un profesional
	if(empty($_REQUEST["matricula"])){
		// no seleccionó ningún profesional
		// notificamos error
		header("location:info.php?id=3");
		// forzamos la detención del script
		die();
	}
	$id_paciente = $form["id_paciente"];
	$matricula = $form["matricula"];
	$tpl->set_var('id_profesional2', base64_encode($form["matricula"]));
	$tpl->set_var('id_paciente', $id_paciente);
	$tpl->set_var('id_profesional', $matricula);
	$tpl->set_var('error', "");
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
	/////////////////////////////
	// calculamos el turno optimo
	/////////////////////////////
	// dia actual
	$dia_actual = fecha_mysql(date("d/m/Y"));
	// calculamos el id del día, 1 lunes ... 6 sabado
	$dia_semana = date('N', strtotime($dia_actual));
	// verificamos si tildó la opción turno doble
	$turno_doble = "";
	if(isset($form["doble"])) $turno_doble = 1;
	else $turno_doble = 0;
	// consultamos el tiempo de cada turno
	$sql = "SELECT tiempo_turno FROM configuracion";
	$tiempo_turno = $conexion->GetRow($sql);
	// inicializamos variable para buscar un turno optimo hasta encontrarlo
	$continuar = true;
	$fecha_turno_encontrado = "";
	$hora_ini_turno_encontrado = "";
	$hora_ini_segundo_turno = "";
	$id_turno_encontrado = "";
	while($continuar){
		// si el id dia es menor a 5, es decir, menor que viernes, le agrega uno
		// caso contrario, vuelve al lunes
		if($dia_semana<5) $dia_semana++;
		else $dia_semana = 1;
		// vamos sumando un día por vuelta
		// la primer vuelta arranca desde el día posterior al actual
		$nuevafecha = strtotime ( '+1 day' , strtotime ( $dia_actual ) ) ;
		$dia_actual = date ( 'Y-m-d' , $nuevafecha );	
		// verificar que la fecha no este registrada como un dia no laboral
		$sql = "SELECT Count(*) FROM dia_no_laboral WHERE fecha='$dia_actual'";
		$cantidad = $conexion->GetRow($sql);
		// si cantidad es cero, entonces es un día laboral
		if($cantidad["Count(*)"] == 0){
			// es un dia laboral
			// verificar que el profesional atienda en el dia de la semana calculado
			$sql = "SELECT Count(*) FROM horario_atencion_profesional 
					WHERE matricula_profesional LIKE '$matricula' AND id_dia='$dia_semana';";
			$cantidad = $conexion->GetRow($sql);
			if($cantidad["Count(*)"] > 0){
				// el profesional atiende en el dia calculado
				// obtenemos el horario de atencion del profesional para el proximo dia habil
				$sql = "SELECT ha.hora_inicio,ha.hora_fin,ha.turno_id_turno FROM horario_atencion_profesional hap, horario_atencion ha 
				WHERE hap.matricula_profesional='$matricula' AND hap.id_dia='$dia_semana' AND hap.id_horario_atencion=ha.id_horario_atencion;";
				$horario_atencion = $conexion->Execute($sql);
				foreach($horario_atencion as $un_horario){
					// hora de inicio y fin de los turnos
					$hora_ini = $un_horario["hora_inicio"];
					$hora_fin = $un_horario["hora_fin"];
					$id_turno = $un_horario["turno_id_turno"];
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
							WHERE fecha_turno='$dia_actual' AND id_profesional='$matricula' AND id_turno='$id_turno';";
					$lista_turnos_otorgados = $conexion->Execute($sql);
					// cargamos el listado de turnos disponibles			
					// nos aseguramos que otorgue el primer turno libre encontrado
					$primero = false;
					$segundo = false;
					while($hora_ini_time < $hora_fin_time){
						$hora_ini_table = date("H:i", $hora_ini_time);
						// verificamos si el turno esta dado
						$turno_otorgado = false;
						foreach($lista_turnos_otorgados as $un_turno){
							if($un_turno["horario"] == $hora_ini_table) $turno_otorgado = true;
						}
						// si el turno no fue otorgado, lo ofrecemos
						if(! $turno_otorgado){
							// si se necesita turno doble, verificamos que el siguiente turno este vacío
							if($primero && ($turno_doble == 1) && (! $segundo)){
								$diff_horario = $hora_ini_time - strtotime($hora_ini_turno_encontrado);
								if($diff_horario == 1800) {
									$hora_ini_segundo_turno = $hora_ini_table;
									$segundo = true;
									$continuar = false;
								} else {
									// eliminamos el primer turno encontrado
									$primero = false;
									$fecha_turno_encontrado = "";
									$hora_ini_turno_encontrado = "";
									$id_turno_encontrado = "";	
								}
							}
							
							// buscarmos el primer turno libre
							if(! $primero) {
								$primero = true;
								$fecha_turno_encontrado = $dia_actual;
								$hora_ini_turno_encontrado = $hora_ini_table;
								$id_turno_encontrado = $id_turno;
								// si no esta marcado turno doble, paramos la búsqueda
								// if($turno_doble == 0) $continuar = false;
								$continuar = false;
							}
						}
						// sumamos el tiempo de un turno
						$hora_ini_time = $hora_ini_time + $tiempo_turno_time;
					}
				}
			}
		}
	}
	/////////////////////////////
	// fin calculamos el turno optimo
	/////////////////////////////
	// seteamos el turno optimo
	$tpl->set_var("hora_ini", $hora_ini_turno_encontrado);
	$tpl->set_var("hora_ini2", $hora_ini_segundo_turno);
	$tpl->set_var("turno_doble", $turno_doble);
	$tpl->set_var("dia_turno", fecha_normal($fecha_turno_encontrado));
	$tpl->set_var("id_turno", $id_turno_encontrado);
	// consultamos el turno
	$db_turno = $conexion->GetRow("SELECT turno FROM turno WHERE id_turno='$id_turno_encontrado';");
	$tpl->set_var("turno", $db_turno["turno"]);
	// cargamos el menu principal
	$tpl->set_var('menu', getMenu());
	// parseamos la planitlla
	$tpl->parse('buscar_turno_optimo');

} else {
	// intento de acceso no válido
	header("location:info.php?id=2");
}
?>