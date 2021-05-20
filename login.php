<?php  
ini_set('default_charset','utf8');
require_once('tpleng.class.php');

// inicializa la plantilla
$tpl = new tpleng;
$tpl->set_file('login','login.tpl');

// array con los posibles mensajes de error
$message = array(
"1" => "El usuario o la contraseña son incorrectos, si olvido su contraseña comuniquese con un administrador",
"2" => "Se produjo un error inesperado, intente ingresar nuevamente"
);

// si se recibió un id, significa que el script se invocó en forma recursiva y necesita informar un error
if(! empty($_GET["id"])) {
	// a partir del id recibido, cargamos el código de error correspondiente
	$error = $message[$_GET["id"]];
	$user = $_GET["user"];
	$tpl->set_var('error', $error);
	$tpl->set_var('id_usuario', $user);
} else {
	// caso contrario, está ingresando por primera vez, por lo cual, no se informan errores
	$tpl->set_var('error', '');
	$tpl->set_var('id_usuario', '');
}

$tpl->parse("login");
?>