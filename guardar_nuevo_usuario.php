<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");

// validar que haya entrado por guardar y haya elgido una persona
if(isset($_REQUEST['guardar'])){
	// verificamos que haya elegido una persona
	if(empty($_REQUEST["persona"])){
		// no seleccionó ninguna persona
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// seteamos las variables
		// notificar error
		$tpl->set_var('mensaje', "Antes de guardar un nuevo usuario debe seleccionar una persona");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "nuevo_usuario.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
		// forzamos la detención del script
		die();
	}
	// preproceso del formulario
	$id_persona = $_REQUEST["persona"];
	$id_sexo = substr($id_persona, 0, 1);
	$id_tipo_doc = substr($id_persona, 1, 1);
	$fin = strlen($id_persona) - 1;
	$nro_documento = substr($id_persona, 2, $fin);	
	// verificamos que el usuario no exista
	$cantidad = $conexion->GetRow("SELECT Count(*) FROM usuario WHERE persona_nro_documento='$nro_documento' 
																AND sexo_id_sexo='$id_sexo' 
																AND tipo_documento_id_tipo_documento='$id_tipo_doc'");
	if($cantidad["Count(*)"] > 0) {
		// si la cantidad de resultados es mayor a cero, ya existe un usuario con esos datos
		$duplicado = true;
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// notificar error
		$tpl->set_var('mensaje', "Error al guardar el usuario: Ya existe un usuario registrado con el mismo sexo, tipo y número de documento");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "nuevo_usuario.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
		// forzamos la detención del script
		die();
	}
	// recuperamos los datos del formulario
	$nick = $_REQUEST["nick"];
	$pass = $_REQUEST["password"];
	// validar usuario y contraseña
	$error = true;
	if(is_alphanumeric($pass,5,20)){
    	$pass = sha1($pass);
        // verificamos que el nick sea valido
        if(is_alphanumeric($nick,5,20)){
			$error = false;
		}
	}
	// verificamos si existen errores
	if($error){
		// error en el usuario o contraseña
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// notificar error
		$tpl->set_var('mensaje', "Error al guardar el usuario: El usuario y la contraseña deben contener entre 5 y 20 caracteres alfanuméricos");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "nuevo_usuario.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
		// forzamos la detención del script
		die();
	}
	// verificamos que no exista otro usuairo con el mismo nick
    $cant = $conexion->GetRow("SELECT Count(*) FROM usuario WHERE id_usuario='$nick'");
    // si la cantidad de resultados es mayor a cero, ya existe un usuario con el mismo nick
	if($cant['Count(*)'] > 0){
		// el nombre de usuario ya existe
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// notificar error
		$tpl->set_var('mensaje', "Error al guardar el usuario: El nombre de usuario elegido ya se encuentra registrado, intente con un nuevo usuario");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "nuevo_usuario.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
		// forzamos la detención del script
		die();
	}
	
	// guardamos el usuario en la base
	$sql = "INSERT INTO usuario(id_usuario,password,activo_usuario,persona_nro_documento,sexo_id_sexo,tipo_documento_id_tipo_documento) 
	VALUES ('$nick', '$pass', '1','$nro_documento', '$id_sexo', '$id_tipo_doc');";
	// inicializamos la plantilla
	$tpl = new tpleng;
	$tpl->set_file('info', 'info.tpl');
	// ejecutamos el INSERT y verificamos el resultado
	if($conexion->Execute($sql)){
		// notificar que la persona fue creada
		$tpl->set_var('mensaje', "El usuario fue registrado correctamente");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "index.php");
		$tpl->set_var('mensaje_std', "Volver al inicio");
		// parseamos la plantilla
		$tpl->parse('info');
	}else{
		// notificar error
		$tpl->set_var('mensaje', "Error al guardar el usuario en la base de datos, intentelo nuevamente");
		$tpl->set_var('html_adicional', "");
		$tpl->set_var('enlace_std', "nuevo_usuario.php");
		$tpl->set_var('mensaje_std', "Volver a Intentarlo");
		// parseamos la plantilla
		$tpl->parse('info');
	}
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>