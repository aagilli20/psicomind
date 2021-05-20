<?php 
ini_set('default_charset','utf8');
require_once('tpleng.class.php');

// listado de posibles errores frecuentes a notificar
$message = array(
				"1" => "Error al conectar con la base de datos, por favor intentelo nuevamente",
				"2" => "Intento de acceso no autorizado, presionar el botón guardar",
				"3" => "Intento de acceso no autorizado, debe seleccionar un profesional válido",
				"4" => "Intento de acceso no autorizado, debe seleccionar un paciente válido",
				"5" => "Intento de acceso no autorizado, debe seleccionar una persona válida",
				"6" => "Intento de acceso no autorizado, debe seleccionar un usuario válido",
				"7" => "Intento de acceso no autorizado",
				"8" => "El mes y el año son datos obligatiros"
				);

// inicialización de la plantilla
$tpl = new tpleng;
$tpl->set_file('info', 'info.tpl');
// seteamos las variables
// verificamos si se recibió algún identificador por GET
if(! empty($_GET["id"])) {
	// si se recibió un identificador, mostramos el mensaje de error correspondiente a ese ID
	$id_msg = $_GET["id"];
	$tpl->set_var('mensaje', utf8_encode($message[$id_msg]));
}
else $tpl->set_var('mensaje', "Error desconocido"); // sino, error por defecto
// seteamos el resto de las variables
$tpl->set_var('html_adicional', "");
$tpl->set_var('enlace_std', "index.php");
$tpl->set_var('mensaje_std', "Volver a Intentarlo");
// parseamos la plantilla
$tpl->parse('info');
?>