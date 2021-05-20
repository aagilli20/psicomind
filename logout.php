<?php
require_once("seguridad.php");
// inizializa la variables de sesión para evitar errores por su no existencia
session_start();
// resetea los valores que indican que el usuario está logueado
$_SESSION["logueado"]=false;
$_SESSION["usuario"]=null;
// redirecciona la pantalla hacía el login
header("location:login.php");
die();
?>
