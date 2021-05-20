<?php  
ini_set('default_charset','utf8');
require_once("seguridad.php");
require_once('tpleng.class.php');
require_once('menu.php');

// inicializamos la plantilla
$tpl = new tpleng;
$tpl->set_file('index','index.tpl');
// obtenemos el nombre de usaurio de la variable de sesión
$usuario = $_SESSION["usuario"];
// seteamos las variables del index
$tpl->set_var('id_usuario', $usuario);
// cargamos el menú principal
$tpl->set_var('menu', getMenu());
// parseamos la plantilla
$tpl->parse('index');

?>