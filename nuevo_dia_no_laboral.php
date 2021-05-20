<?php  
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once("validacion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('ndnl','nuevo_dia_no_laboral.tpl');
// seteamos las variables
$tpl->set_var('fecha', "");
$tpl->set_var('motivo', "");

// cargamos el menu principal
$tpl->set_var('menu', getMenu());

// parseamos la plantilla
$tpl->parse('ndnl');

?>