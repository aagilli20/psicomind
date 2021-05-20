<?php 
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once("conexion.php");
require_once('tpleng.class.php');
require_once("menu.php");

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('nueva_obra_social', 'nueva_obra_social.tpl');

// llenamos los campos de la plantilla
$tpl->set_var('error', "");

$tpl->set_var('nombre', "");
$tpl->set_var('rnemp', "");
$tpl->set_var('telefono', "");
$tpl->set_var('direccion', "");
$tpl->set_var('localidad', "");
$tpl->set_var('provincia', "");
$tpl->set_var('codigo_postal', "");

// cargamos el menu principal
$tpl->set_var('menu', getMenu());

// parseamos la plantilla
$tpl->parse('nueva_obra_social');

?>