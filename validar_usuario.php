<?php
ini_set('default_charset','utf8');
require_once("conexion.php");

// verificamos que el acceso provenga del botón ingresar
if(isset($_REQUEST['ingresar'])){
  // recuperamos los datos ingresados por el usuario en el formulario de login	
  $nick = $_REQUEST['username'];
  // recuperamos la contraseña y la codificamos
  $pass = sha1($_REQUEST['password']);
  // consultamos en la db cuantos usuario y contraseñas coinciden con los ingresados por el usuario
  $sql = "SELECT count(*) FROM usuario where id_usuario='$nick' and password='$pass'";
  $ok = $conexion->GetOne($sql);
  // verificamos si los datos ingresados son correctos
  if($ok){
      // usuario logueado correctamente
	  // inicializamos la variable de sesión
      session_start();
	  // asignamos nuevos registros a la variable de sesión con datos del usuario logueado
      $_SESSION["logueado"] = true;
      $_SESSION["usuario"] = $nick;
	  // $_SESSION["tipo"] = $tipo_usuario['IdTipoUsuario'];
	  // redirecciona la pantalla hacia el index.php
      header("location:index.php");
  } else {
    // error de usuario o contraseña
	// notifica el error
    header("location:login.php?id=1&user=$nick");
  }
} else{
	// intento de acceso no válido
	header("location:login.php?id=2&user=$nick");
}
?>