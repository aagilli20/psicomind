<?php
/*
* Esta clase inicializa la conexión a la base de datos utilizando la librería
* ADODB
* Hecho esto, a través de esta clase se podrán realizar en forma directa acciones de tipo SELECT, INSERT, UPDATE Y DELETE
* contra la base de datos
*/
require_once("./adodb5/adodb.inc.php");
// configuramos el fetch mode para que al realizar las consultas los indices del arreglo
// tengan los mismos nombres que las columnas de la base de datos
$ADODB_FETCH_MODE=ADODB_FETCH_ASSOC;
// definimos la interfaz para bases de datos MySQL
$conexion=ADONewConnection("mysqli");
if(! $conexion->Connect("127.0.0.1","root","","db_psicomind")){
  // si no conecta enviamos un mensaje de error
  header("Location:info.php?id=1");
  // se fuerza la finalización del script
  die;
}
// establecemos la codificación de los caracteres a UTF8 para no tener inconvenientes con acentos, apostrofes, entre otros
$acentos = $conexion->query("SET NAMES 'utf8'");
if(! $acentos){
  // si falla la configuracion anterior mostramos un mensaje de error
  header("Location:info.php?id=1");
  die;
}
?>
