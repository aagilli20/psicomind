<?php
// +--------------------------------------------------------------------------------+
// | Funciones que define:                                                          |
// |  - is_digito()                                                                 |
// |        comprueba si $digito es un digito o no                                  |
// |  - is_vacio()                                                                  |
// |        chequea si es un string vacio                                           |
// |  - is_valid()                                                                  |
// |        chequea si es un string valido                                          |
// |  - is_alpha()                                                                  |
// |        is_alpha(string un_string, int min_long, int max_long)                  |
// |        chequea si un_string esta compuesto por caracteres alfabeticos unic.    |
// |        chequea si posee una longitud entre min_long y max_long                 |
// |  - is_numerico()                                                               |
// |        is_numerico(string un_string, int min_long, int max_long)               |
// |        chequea si un_string esta compuesto por caracteres numericos unicamente |
// |        chequea si posee una longitud entre min_long y max_long                 |
// |  - is_alphanumeric()                                                           |
// |        is_numerico(string un_string, int min_long, int max_long)               |
// |        chequea si un_string esta compuesto por caracteres alfa_numericos       |
// |        chequea si posee una longitud entre min_long y max_long                 |
// |  - is_email()                                                                  |
// |        comprueba que la entrada sea una direcci�n de e-mail valida             |
// |  - is_clean_text()                                                             |
// |        is_clean_text(string un_string, int min_long, int max_long)             |
// |        chequea si un_string esta compuesto por  una linea de texto limpio      |
// |        chequea si posee una longitud entre min_long y max_long                 |
// |  - contains_bad_words()                                                        |
// |        comprueba que la entrada no contenga alguna palabra no deseada          |
// |  - contains_phone_number()                                                     |
// |        comprueba que la entrada contenga alg�n numero telef�nico               |
// |                                                                                |
// | Autor: Andrés Gilli		                                                    |
// +--------------------------------------------------------------------------------+

ini_set('default_charset','utf8');

function is_digito($value)
{ // Comprueba si $value es un dígito o no
  $value = trim($value);
  if (preg_match('/^[0-9]{1}$/', $value))
  {
	return true;
  }
  else
  {
	return false;
  }
}

function is_vacio($value)
{ // Chequea si $value es un string vacio
  $value = trim($value);
  if (empty($value))
  {
	return true;
  }
  else
  {
	return false;
  }
}

function is_email($value)
{ // Comprueba que la entrada sea una direccion de e-mail valida
  $value = trim($value);
  if (preg_match('/^[^0-9.\-_]([.-]{0,1}[a-zA-Z0-9_]+)*[@]([.-]{0,1}[a-zA-Z0-9\-]+)+[.][a-zA-Z]{2,4}$/', $value))
  {
	return true;
  }
  else
  {
	return false;
  }
}

function _is_valid($value, $min_length, $max_length, $regex)
{
  // Chequea si es un string vacio
  $value = trim($value);
  if (empty($value))
  {
    return false;
  }
  // Chequea si es un string con caracteres enteramente de tipos
  if (!preg_match("/^$regex$/", $value))
  {
    return false;
  }
  // Chequea por la entrada opcional de longitud
  $strlen = strlen($value);
  if (($min_length != 0 && $strlen < $min_length) || ($max_length != 0 && $strlen > $max_length))
  {
    return false;
  }
  // OK
  return true;
}


function is_alphanumeric($value, $min_length = 0, $max_length = 0)
{
  // is_numerico(string un_string, int min_long, int max_long)
  // Chequea si un_string esta compuesto por caracteres alfa_numericos
  // chequea si posee una longitud entre min_long y max_long
  return _is_valid($value, $min_length, $max_length, "[[:alnum:][:space:]ñÑáéíóúüÁÉÍÓÚ(),.:!¡¿?]+");
  
}


function is_numerico($value, $min_length = 0, $max_length = 0)
{
  // is_numerico(string un_string, int min_long, int max_long)
  // Chequea si un_string esta compuesto por caracteres numericos unicamente
  // chequea si posee una longitud entre min_long y max_long
  return _is_valid($value, $min_length, $max_length, "[[:digit:]]+");
}

/*
function is_alphanumeric($value, $min_length = 0, $max_length = 0)
{
  // is_numerico(string un_string, int min_long, int max_long)
  // Chequea si un_string esta compuesto por caracteres alfa_numericos
  // chequea si posee una longitud entre min_long y max_long
  return _is_valid($value, $min_length, $max_length, "[[:alnum:],.ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑ(){}=?¡¿!-_/ÒÓÔÕÖØÙÚÛÜÝÞßàáâãäæçèéêëìíîïðñòóôõöøùúûüýþ[:space:]]+");
}
*/


function is_clean_text($value, $min_length = 0, $max_length = 0)
{
  // is_clean_text(string un_string, int min_long, int max_long)
  // chequea si un_string esta compuesto por  una linea de texto limpio
  // chequea si posee una longitud entre min_long y max_long
  return _is_valid($value, $min_length, $max_length, "[[:alpha:][:space:]ñÑáéíóúü,.:!¡¿?]+");
}

/*
function contains_bad_words($string)
//          comprueba que la entrada no contenga alguna palabra no deseada
{
    $bad_words = array(
                    'anal',           'ass',        'bastard',       'puta',
                    'bitch',          'blow',       'butt',          'trolo',
                    'cock',           'clit',       'cock',          'pija',
                    'cornh',          'cum',        'cunnil',        'verga',
                    'cunt',           'dago',       'defecat',       'cajeta',
                    'dick',           'dildo',      'douche',        'choto',
                    'erotic',         'fag',        'fart',          'trola',
                    'felch',          'fellat',     'fuck',          'puto',
                    'gay',            'genital',    'gosh',          'pajero',
                    'hate',           'homo',       'honkey',        'pajera',
                    'horny',          'vibrador',   'jew',           'lesbiana',
                    'jiz',            'kike',       'kill',          'eyaculacion',
                    'lesbian',        'masoc',      'masturba',      'anal',
                    'nazi',           'nigger',     'nude',          'mamada',
                    'nudity',         'oral',       'pecker',        'teta',
                    'penis',          'potty',      'pussy',         'culo',
                    'rape',           'rimjob',     'satan',         'mierda',
                    'screw',          'semen',      'sex',           'bastardo',
                    'shit',           'slut',       'snot',
                    'spew',           'suck',       'tit',
                    'twat',           'urinat',     'vagina',
                    'viag',           'vibrator',   'whore',
                    'xxx'
    );

    //      verifica
    for($i=0; $i<count($bad_words); $i++)
    {
        if(strstr(strtoupper($string), strtoupper($bad_words[$i])))
        {
            return(true);
        }
    }

    //      OK
    return(false);
}
*/

function contains_phone_number($value)
{
  // comprueba que la entrada contenga algun numero telefonico
  if(preg_match("/^[\(]*[[:digit:]]{4,5}[. \-\)]*[[:digit:]]{5,10}$/", $value))
  {
    return true;
  }
  else
  {
    return false;
  }
}
?>