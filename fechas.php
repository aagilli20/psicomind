<?php
/*
*
* Esta librería se utiliza para realizar diversas conversiones y operaciones con fechas
* Autor: Andrés Gilli
*
*/

////////////////////////////////////////////////////
//Convierte fecha de mysql a normal
////////////////////////////////////////////////////

function fecha_normal($fecha){
    // toma los primeros 4 números separados por un guion, deben ser entre 2 y 4
    // después busca el siguiente par de números separados por un guion, pueden ser 1 o 2
    // por ultimo toma el ultimo par de números, pueden ser 1 o 2
    // ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
	preg_match("/(\d{4})\-(\d{2})\-(\d{2})/", $fecha, $mifecha);
    $lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1];
    return $lafecha;
}

////////////////////////////////////////////////////
//Convierte fecha de mysql a datepicker
////////////////////////////////////////////////////

function fecha_datepicker($fecha){
    // toma los primeros 4 números separados por un guion, deben ser entre 2 y 4
    // después busca el siguiente par de números separados por un guion, pueden ser 1 o 2
    // por ultimo toma el ultimo par de números, pueden ser 1 o 2
    // ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
	preg_match("/(\d{4})\-(\d{2})\-(\d{2})/", $fecha, $mifecha);
    $lafecha=$mifecha[2]."/".$mifecha[3]."/".$mifecha[1];
    return $lafecha;
}


////////////////////////////////////////////////////
//Convierte fecha de normal a mysql
////////////////////////////////////////////////////

function fecha_mysql($fecha){
    // toma el primer par de números separados por una barra, deben ser entre 1 y 2
    // después busca el siguiente par de números separados por una barra, pueden ser 1 o 2
    // por ultimo toma los ultimos 4 números, pueden ser 2 o 4
    // ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
    // ereg( "([0-9]{2})/([0-9]{2})/([0-9]{4})", $fecha, $mifecha);
	preg_match("/(\d{2})\/(\d{2})\/(\d{4})/", $fecha, $mifecha);
	$lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1];
    return $lafecha;
}

////////////////////////////////////////////////////
// Devuelve el año
////////////////////////////////////////////////////

function get_anio($fecha){
    // parametro en formato mysql
    // ereg("([0-9]{4})-([0-9]{2})-([0-9]{2})", $fecha, $mifecha);
	preg_match("/(\d{4})\-(\d{2})\-(\d{2})/", $fecha, $mifecha);
	$anio = $mifecha[1];
    return $anio;
}

////////////////////////////////////////////////////
//valida la fecha
////////////////////////////////////////////////////

function fecha_valida($fecha){
  if(preg_match("/(\d{2})\/(\d{2})\/(\d{4})/", $fecha, $mifecha)){
    $ok = checkdate($mifecha[2],$mifecha[1],$mifecha[3]);
    if($ok){
      $actual = date("Y-m-d");
      $older = "1900-01-01";
      $dtime = strtotime(fecha_mysql($fecha));
      $dtime_ac = strtotime($actual);
      $dtime_ol = strtotime($older);
      if($dtime>$dtime_ac || $dtime<$dtime_ol){
        return false;
      }else{
        return true;
      }
    }else{
      // fecha fuera del calendario
      return false;
    }  
  }else{
    // formato no valido
    return false;
  }
}


////////////////////////////////////////////////////
//valida la fecha futura
////////////////////////////////////////////////////

function fecha_valida_futura($fecha){
  if(preg_match("/(\d{2})\/(\d{2})\/(\d{4})/", $fecha, $mifecha)){
    $ok = checkdate($mifecha[2],$mifecha[1],$mifecha[3]);
    if($ok){
      $older = "2000-01-01";
      $dtime = strtotime(fecha_mysql($fecha));
      $dtime_ol = strtotime($older);
      if($dtime<$dtime_ol){
        return false;
      }else{
        return true;
      }
    }else{
      // fecha fuera del calendario
      return false;
    }  
  }else{
    // formato no valido
    return false;
  }
}

////////////////////////////////////////////////////
// fecha 1 menor a fecha 2
////////////////////////////////////////////////////

function is_fecha1_menor($fecha1,$fecha2){
  $dtime1 = strtotime(fecha_mysql($fecha1));
  $dtime2 = strtotime(fecha_mysql($fecha2));
  if($dtime1<$dtime2){
    return true;
  }{
    return false;
  }
}

////////////////////////////////////////////////////
//valida la fecha para turnos
////////////////////////////////////////////////////

function fecha_valida_turno($fecha){
  if(preg_match("/(\d{2})\/(\d{2})\/(\d{4})/", $fecha, $mifecha)){
    $ok = checkdate($mifecha[2],$mifecha[1],$mifecha[3]);
    if($ok){
      $older = date("Y-m-d");
      $dtime = strtotime(fecha_mysql($fecha));
      $dtime_ol = strtotime($older);
      if($dtime < $dtime_ol){
        return false;
      }else{
        return true;
      }
    }else{
      // fecha fuera del calendario
      return false;
    }  
  }else{
    // formato no valido
    return false;
  }
}

////////////////////////////////////////////////////
// calcula la edad a partir de la fecha de nacimiento
////////////////////////////////////////////////////

function calcular_edad($fecha){
	// php 5.3 o superior
	$from = new DateTime($fecha);
	$to   = new DateTime('today');
	return $from->diff($to)->y;	
}

?>