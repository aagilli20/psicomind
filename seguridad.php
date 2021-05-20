<?php
  // antes de permitir el acceso a la página que la invoque, verifica que el usuario esté logueado
  // inicializamos la variable de session para prevenir errores
  session_start();
  // verificamos si el usuario no está logueado
  if(! $_SESSION["logueado"]){
    // no esta logueado
	// redireccionamos la pantalla principal hacia el login.php
    header("location:login.php");
	// forzamos la detención del script
    die();
  }
  // si el usuario está logueado la clase no realiza ninguna acción, por lo cual, el sistema continúa con su flujo normal
?>