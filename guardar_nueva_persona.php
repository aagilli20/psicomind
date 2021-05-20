<?php
ini_set('default_charset','utf8');

require_once("seguridad.php");
require_once("conexion.php");
require_once("tpleng.class.php");
require_once("validacion.php");
require_once("fechas.php");
require_once("menu.php");

// inicializamos variables para el manejo de errores
$datos_obligatorios = true;
$formato_datos = true;
$error_obligatorios = "";
$error_formato = "";

// verificamos que haya ingresado por el botón guardar
if(isset($_REQUEST['guardar'])){
	// leemos los campos del formulario y los almacenamos en un array
	$form = array();
	foreach($_REQUEST as $key=>$val){
		$form[$key] = $val;
	}
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
				}if($form["select_tipo_doc"] == 0){
					// el tipo de documento es un dato obligatorio
					$datos_obligatorios = false;
					$error_obligatorios = $error_obligatorios."- Fatan datos obligatorios - Tipo de documento de la persona</br>";
					}if($form["select_sexo"] == 0){
						// el sexo es un dato obligatorio
						$datos_obligatorios = false;
						$error_obligatorios = $error_obligatorios."- Fatan datos obligatorios - Sexo de la persona</br>";
						}
	
	// antes de continuar, verificamos que esten los datos obligatorios cargados
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
		if((! contains_phone_number($form["telefono"])) && ($form["telefono"] != "Teléfono (0342-45011XX)")){
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El formato del teléfono, debe seguir el siguiente ejemplo: 0342-1000000</br>";
		}
		if((! contains_phone_number($form["celular"])) && ($form["celular"] != "Celular (34245677XX)")){
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El formato del celular, debe seguir el siguiente ejemplo: 0342-1000000</br>";
		}
		if((! is_email($form["email"])) && ($form["email"] != "Correo electrónico")){
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El formato del email, debe seguir el siguiente ejemplo: username@dominio.com</br>";
		}
		if((! is_alphanumeric($form["domicilio"], $min_length = 1, $max_length = 60)) && ($form["domicilio"] != "Domicilio")) {
			$formato_datos = false;
			$error_formato = $error_formato."- Error de formato - El domicilio debe contener solo caracteres alfanumericos con una longitud máxima de 40 caracteres</br>";
		}
		// si no hay errores en los datos, entonces subimos la foto
		if($formato_datos){
			// verifica si se subió un archivo
			if(is_uploaded_file($_FILES['url_foto']['tmp_name'])) {
	  			// preprocesa el archivo para luego almacenarlo
				$extension=explode(".",$_FILES['url_foto']['name'],2);
				$extension[1] = strtolower($extension[1]);
				// verificamos que la extención sea válida
	  			if(!strcmp($extension[1],"png") || !strcmp($extension[1],"jpg") || !strcmp($extension[1],"jpeg")){
					// obtenemos el tamaño del archivo
					$tamanio=$_FILES['url_foto']['size'];
					// verificamos que el tamaño de la foto no supere los 7 M
					if($tamanio < 7340032){
						// creamos un nombre para el archivo
						$id_sexo = $form["select_sexo"];
						$documento = $form["documento"];
						$user_path = "./person/".$documento.$id_sexo;
						// crear una carpeta para la persona
						mkdir($user_path);
						$img_path = $user_path."/"."foto.".$extension[1];
						unset($id_sexo); unset($documento);
						// copiamos la foto en la nueva carpeta y verificamos que la operación se realice correctamente
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
	// verificamos si se detectó algún error
	if(!$formato_datos || !$datos_obligatorios){
		// volvemos al form nueva persona informando el error
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('nueva_persona', 'nueva_persona.tpl');
		// llenamos los campos de la plantilla
		$tpl->set_var('error', $error_obligatorios.$error_formato);
		// obtenemos el tipo de documento seleccionado por el usuario
		$id_tipo_doc = $form["select_tipo_doc"];
		// consultamos el listado de tipos de documento
		$db_tipo_doc = $conexion->Execute("SELECT * FROM tipo_documento");
		// creamos un array para almacenar el resutlado de la consulta
		$lista_tipo_doc = array();
		// forzamos a que el primer valor se seleccione un tipo de documento
		array_push($lista_tipo_doc, array('id' => '0', 'valor' => 'Seleccione el Tipo de Documento', 'selected' => ''));
		// cargamos el resultado de la consulta en el array
		foreach($db_tipo_doc as $tipo_doc){
			if($id_tipo_doc == $tipo_doc['id_tipo_documento']){
				// si había un tipo seleccionado, mantenemos la selección
				array_push($lista_tipo_doc, array('id' => $tipo_doc['id_tipo_documento'], 'valor' => $tipo_doc['valor_tipo_documento'], 'selected' => 'selected="selected"'));
			}else{
				array_push($lista_tipo_doc, array('id' => $tipo_doc['id_tipo_documento'], 'valor' => $tipo_doc['valor_tipo_documento'], 'selected' => ''));
			}
		}
		// cargamos el array en el bloque_tipo_doc de la plantilla nueva_persona.tpl
		$tpl->set_loop('bloque_tipo_doc', $lista_tipo_doc);
		// fin lista tipo doc
		$tpl->set_var('documento', $form["documento"]);
		// obtenemos el sexo seleccionado en el formulario
		$id_sexo = $form["select_sexo"];
		// consultamos el listado de sexos
		$db_sexo = $conexion->Execute("SELECT * FROM sexo");
		// creamos un array para almacenar el resultado de la consulta
		$lista_sexo = array();
		// forzamos a que el primer valor sea seleccione un sexo
		array_push($lista_sexo, array('id' => '0', 'valor' => 'Seleccione el Sexo', 'selected' => ''));
		// cargamos el resultado de la consulta en el array
		foreach($db_sexo as $sexo){
			if($id_sexo == $sexo['id_sexo']){
				// si había un sexo seleccionado, mantenemos el registro
				array_push($lista_sexo, array('id' => $sexo['id_sexo'], 'valor' => $sexo['sexo'], 'selected' => 'selected="selected"'));
			}else{
				array_push($lista_sexo, array('id' => $sexo['id_sexo'], 'valor' => $sexo['sexo'], 'selected' => ''));
			}
		}
		// cargamos el array en el bloque_sexo de la plantilla nueva_persona.tpl
		$tpl->set_loop('bloque_sexo', $lista_sexo);
		// fin lista sexo
		// seteamos el resto de las variables		
		$tpl->set_var('fenac', $form["fe_nac"]);
		$tpl->set_var('nombre', $form["nombre"]);
		$tpl->set_var('apellido', $form["apellido"]);
		$tpl->set_var('telefono', $form["telefono"]);
		$tpl->set_var('celular', $form["celular"]);
		$tpl->set_var('email', $form["email"]);
		$tpl->set_var('domicilio', $form["domicilio"]);
		
		// cargamos el menu principal
		$tpl->set_var('menu', getMenu());
		// parseamos la plantilla
		$tpl->parse('nueva_persona');
	}else{
		// no hay errores
		// preproceso del formulario
		$id_sexo = $form["select_sexo"];
		$id_tipo_doc = $form["select_tipo_doc"];
		$documento = $form["documento"];
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
		// verificamos que no sea un duplicado
		$cantidad = $conexion->GetRow("SELECT Count(*) FROM persona WHERE nro_documento='$documento' AND sexo_id_sexo='$id_sexo' 
																	AND tipo_documento_id_tipo_documento='$id_tipo_doc'");
		if($cantidad["Count(*)"] > 0) {
			// si la cantidad es mayor a cero, entonces ya existe en la db
			$duplicado = true;
			// inicializamos la plantilla para informar el error
			$tpl = new tpleng;
			$tpl->set_file('info', 'info.tpl');
			// notificar error
			$tpl->set_var('mensaje', "Error al guardar la persona: Ya existe una persona registrada con mismo sexo, tipo y número de documento");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "nueva_persona.php");
			$tpl->set_var('mensaje_std', "Volver a Intentarlo");
			// parseamos la plantilla
			$tpl->parse('info');
			// forzamos la detención del script
			die();
		}
		// guardamos la persona en la base
		// verificamos si subió o no una foto para escribir el INSERT
		if(is_null($url_foto)){
			// no subió foto
			$user_path = "./person/".$documento.$id_sexo;
			mkdir($user_path);	
			// escribimos la consulta
		  $sql = "INSERT INTO persona(nro_documento,nombre_persona,apellido_persona,fecha_nacimiento, 														
				  domicilio_persona,telefono_persona,celular_persona,url_foto_persona,email_persona,sexo_id_sexo, 
				  tipo_documento_id_tipo_documento) VALUES ('$documento', '$nombre', '$apellido', '$fe_nac', '$domicilio', '$telefono', 
				  '$celular', NULL, '$email', '$id_sexo', '$id_tipo_doc');";
		} else {	
			// subio foto
			// escribimos la consulta
			$sql = "INSERT INTO persona(nro_documento,nombre_persona,apellido_persona,fecha_nacimiento, 														
				  domicilio_persona,telefono_persona,celular_persona,url_foto_persona,email_persona,sexo_id_sexo, 
				  tipo_documento_id_tipo_documento) VALUES ('$documento', '$nombre', '$apellido', '$fe_nac', '$domicilio', '$telefono', 
				  '$celular', '$url_foto', '$email', '$id_sexo', '$id_tipo_doc');";
		}
		// inicializamos la plantilla
		$tpl = new tpleng;
		$tpl->set_file('info', 'info.tpl');
		// ejecutamos el INSERT y verificamos su resultado
		if($conexion->Execute($sql)){
			// notificar que la persona fue creada
			$tpl->set_var('mensaje', "La persona fue creada correctamente");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "index.php");
			$tpl->set_var('mensaje_std', "Volver al inicio");
			// parseamos la plantilla
			$tpl->parse('info');
		}else{
			// notificar error
			$tpl->set_var('mensaje', "Error al guardar la persona en la base de datos, intentelo nuevamente");
			$tpl->set_var('html_adicional', "");
			$tpl->set_var('enlace_std', "nueva_persona.php");
			$tpl->set_var('mensaje_std', "Volver a Intentarlo");
			// parseamos la plantilla
			$tpl->parse('info');
		}	
	}	
}else{
	// intento de acceso no válido
	header("location:info.php?id=2");
}

?>