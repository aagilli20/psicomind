<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");
require_once("menu.php");

// inicializamos las variables para el manejo de errores
$datos_obligatorios = true;
$formato_datos = true;
$error_obligatorios = "";
$error_formato = "";

// verificamos que haya ingresado a través del botón guardar
if(isset($_REQUEST['guardar'])){
	// presionó guardar
	// leemos todos los datos del formulario y los almacenamos en un array
	$form = array();
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
	$id_persona = $form["id_persona"];
	$id_sexo = substr($id_persona, 0, 1);
	$id_tipo_doc = substr($id_persona, 1, 1);
	$fin = strlen($id_persona) - 1;
	$nro_documento = substr($id_persona, 2, $fin);
	
	// validamos que esten completos los datos obligatorios
	if((empty($form["nombre"]))||($form["nombre"]=="Nombres")){
		// El nombre de la persona es un dato obligatorio
		$datos_obligatorios = false;
		$error_obligatorios = "- Fatan datos obligatorios - Nombre de la persona</br>";
		}if((empty($form["apellido"]))||($form["apellido"]=="Apellido")){
			// El apellido de la persona es un dato obligatorio
			$datos_obligatorios = false;
			$error_obligatorios = $error_obligatorios."- Fatan datos obligatorios - Apellido de la persona</br>";
			}if((empty($form["documento"]))||($form["documento"]=="Número de Documento")){
				// El número de documento es un dato obligatorio
				$datos_obligatorios = false;
				$error_obligatorios = $error_obligatorios."- Fatan datos obligatorios - Documento de la persona</br>";
				}
	
	// verificamos que los datos obligatorios hayan sido cargados
	if($datos_obligatorios){
		// validamos el formato de los datos ingresados
		if(! is_alphanumeric($form["nombre"], $min_length = 1, $max_length = 50)) {
			$formato_datos = false;
			$error_formato = "- Error de formato - El nombre debe contener solo caracteres alfanumericos con una longitud máxima de 50 caracteres</br>";
		}
		if(! is_alphanumeric($form["apellido"], $min_length = 1, $max_length = 40)) {
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El apellido debe contener solo caracteres alfanumericos con una longitud máxima de 40 caracteres</br>";
		}
		if(! is_alphanumeric($form["documento"], $min_length = 6, $max_length = 16)) {
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El documento debe contener solo caracteres alfanumericos con una longitud entre los 6 y los 16 caracteres</br>";
		}
		if((! fecha_valida($form["fe_nac"])) && ($form["fe_nac"] != "Fecha de Nacimiento (DD/MM/AAAA)")) {
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El formato de la fecha, debe seguir el siguiente ejemplo: dd/mm/aaaa</br>";
		}
		if((! contains_phone_number($form["telefono"])) && ($form["telefono"] != "")){
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El formato del teléfono, debe seguir el siguiente ejemplo: 0342-1000000</br>";
		}
		if((! contains_phone_number($form["celular"])) && ($form["celular"] != "")){
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El formato del celular, debe seguir el siguiente ejemplo: 0342-1000000</br>";
		}
		if((! is_email($form["email"])) && ($form["email"] != "")){
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El formato del email, debe seguir el siguiente ejemplo: username@dominio.com</br>";
		}
		if((! is_alphanumeric($form["domicilio"], $min_length = 1, $max_length = 60)) && ($form["domicilio"] != "")) {
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El domicilio debe contener solo caracteres alfanumericos con una longitud máxima de 40 caracteres</br>";
		}
		// si no hay errores subimos la foto
		if($formato_datos){
			if(is_uploaded_file($_FILES['url_foto']['tmp_name'])) {
	  			$extension=explode(".",$_FILES['url_foto']['name'],2);
				$extension[1] = strtolower($extension[1]);
	  			if(!strcmp($extension[1],"png") || !strcmp($extension[1],"jpg") || !strcmp($extension[1],"jpeg")){
					$tamanio=$_FILES['url_foto']['size'];
					if($tamanio < 7340032){
						$user_path = "./person/".$nro_documento.$id_sexo;
						$img_path = $user_path."/"."foto.".$extension[1];
						if(!copy($_FILES['url_foto']['tmp_name'],$img_path)){
							// no se pudo guardar la foto
							$formato_datos = false;
							$error_formato = $error_formato."- Error de formato - Error al guardar la foto, vuelva a intentarlo</br>";
						}else{
							// la foto se guardo correctamente
							$url_foto = $img_path;
						}
					}else{
						// excede los 7 M
						$formato_datos = false;
						$error_formato = $error_formato."- Error de formato - El tamaño de la foto debe ser menor a 7 M</br>";
					}
	  			}else{
					// El formato de la foto debe ser JPG, JPEG o PNG
					$formato_datos = false;
					$error_formato = $error_formato."- Error de formato - El formato de la foto debe ser JPG, JPEG o PNG</br>";
	  			}
			}else{
	  			$url_foto = NULL;
			}
		}
	}
	
	// verificamos la existencia de errores
	if(!$formato_datos || !$datos_obligatorios){
		// se detectaron errores
		// volvemos al form nueva persona informando el error
		$tpl = new tpleng;
		$tpl->set_file('modificar_persona', 'modificar_persona.tpl');
		// llenamos los campos de la plantilla
		$tpl->set_var('error', $error_obligatorios.$error_formato);
		$tpl->set_var('id_persona', $id_persona);
		$tipo_doc_valor = $form["tipo_doc_valor"];
		$tpl->set_var('documento', $form["documento"]);
		$sexo_valor = $form["sexo_valor"];
		$tpl->set_var('fenac', $form["fe_nac"]);
		$tpl->set_var('nombre', $form["nombre"]);
		$tpl->set_var('apellido', $form["apellido"]);
		$tpl->set_var('telefono', $form["telefono"]);
		$tpl->set_var('celular', $form["celular"]);
		$tpl->set_var('email', $form["email"]);
		$tpl->set_var('domicilio', $form["domicilio"]);
		
		// cargamos el menú principal
		$tpl->set_var('menu', getMenu());

		// parseamos la plantilla
		$tpl->parse('modificar_persona');
	}else{
		// no se detectaron errores
		// preproceso del formulario
		$nombre = $form["nombre"];
		$apellido = $form["apellido"];
		if($form["fe_nac"] == "Fecha de Nacimiento (DD/MM/AAAA)") $fe_nac = "";
		else $fe_nac = fecha_mysql($form["fe_nac"]);
		if($form["telefono"] == "Teléfono (0342-45011XX)") $telefono = "";
		else $telefono = $form["telefono"];
		if($form["celular"] == "Celular (34245677XX)") $celular = "";
		else $celular = $form["celular"];
		if($form["email"] == "Correo electrónico") $email = "";
		else $email = $form["email"];
		if($form["domicilio"] == "Domicilio") $domicilio = "";
		else $domicilio = $form["domicilio"];
		// verificamos si el usuario cargo una foto
		if(is_null($url_foto)){
			// escribimos la consulta base sin foto
			$sql = "UPDATE persona SET nombre_persona='$nombre',apellido_persona='$apellido',
					fecha_nacimiento='$fe_nac',domicilio_persona='$domicilio',telefono_persona='$telefono',
					celular_persona='$celular',email_persona='$email'
					WHERE nro_documento='$nro_documento' AND sexo_id_sexo='$id_sexo' 
					AND tipo_documento_id_tipo_documento='$id_tipo_doc';";
		} else {	
			// escribimos la consulta base con foto
			$sql = "UPDATE persona SET nombre_persona='$nombre',apellido_persona='$apellido',
					fecha_nacimiento='$fe_nac',domicilio_persona='$domicilio',telefono_persona='$telefono',
					celular_persona='$celular',email_persona='$email',url_foto_persona='$url_foto'
					WHERE nro_documento='$nro_documento' AND sexo_id_sexo='$id_sexo' 
					AND tipo_documento_id_tipo_documento='$id_tipo_doc';";
		}
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('ver_datos_persona', 'ver_datos_persona.tpl');
		// seteamos las variables de acuerdo al éxito de la ejecución de la consusta base
		if($conexion->Execute($sql)){
			// notificar que la persona fue modificada
			$tpl->set_var('error', "La persona fue modificada con éxito");
		}else{
			$tpl->set_var('error', "Se produjo un error al modificar la persona, intentelo nuevamente");
		}
		// consultamos los datos de la persona seleccionada con el objetivo de acutalizar la pantalla mostrando los nuevos datos de la db
		$persona = $conexion->GetRow("SELECT * FROM persona WHERE nro_documento='$nro_documento' 
																	AND sexo_id_sexo='$id_sexo' 
																	AND tipo_documento_id_tipo_documento='$id_tipo_doc';");
		// consultamos valor tipo doc
		$tipo_doc = $conexion->GetRow("SELECT tipo_documento FROM tipo_documento WHERE id_tipo_documento='$id_tipo_doc';");
		// consultamos valor sexo
		$sexo = $conexion->GetRow("SELECT sexo FROM sexo WHERE id_sexo='$id_sexo';");
		// seteamos las variables de la plantilla
		if($persona["url_foto_persona"] == NULL) $tpl->set_var('url_foto', "./style/images/sin_imagen_usuario.jpg");
		else $tpl->set_var('url_foto', $persona["url_foto_persona"]);	
		$tpl->set_var('id_persona', $id_persona);
		$tpl->set_var('tipo_doc_valor', $tipo_doc["tipo_documento"]);
		$tpl->set_var('documento', $persona["nro_documento"]);
		$tpl->set_var('sexo_valor', $sexo["sexo"]);
		$tpl->set_var('fenac', fecha_datepicker($persona["fecha_nacimiento"]));
		$tpl->set_var('nombre', $persona["nombre_persona"]);
		$tpl->set_var('apellido', $persona["apellido_persona"]);
		$tpl->set_var('telefono', $persona["telefono_persona"]);
		$tpl->set_var('celular', $persona["celular_persona"]);
		$tpl->set_var('email', $persona["email_persona"]);
		$tpl->set_var('domicilio', $persona["domicilio_persona"]);
		
		// cargamos el menú principal
		$tpl->set_var('menu', getMenu());
		// parseamos la plantilla
		$tpl->parse('ver_datos_persona');
	}
	
	// informamos por error o por guardado exitoso
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>