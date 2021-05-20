<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<!-- content -->
<meta name="description" content="sistema para la gestión de consultorios psicológicos" />
<meta name="keywords" content="psicólogo, psicología, psicológico, problemas, atención, crisis, vertigo, psychologist, psychology, psychological issues, health, crisis, dizziness" />
<meta name="author" content="Guillermo Ordíz, Lorena Doello" />
<!-- end content -->
<title>Psicomind - Nuevo Horario de Atención</title>
<link rel="shortcut icon" href="style/images/favicon.png"/>
<link rel="stylesheet" type="text/css" href="style.css" media="all" />
<link rel="stylesheet" type="text/css" href="style/css/view.css" media="all" />
<link rel="stylesheet" type="text/css" href="style/type/marketdeco.css" media="all" />
<link rel="stylesheet" type="text/css" href="style/type/merriweather.css" media="all" />
<link rel="stylesheet" type="text/css" href="style/css/queries.css" media="all" />
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="style/css/ie8.css" media="all" />
<![endif]-->
<!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="style/css/ie9.css" media="all" />
<![endif]-->
<!-- Inicio datepicker --> 
<link href="style/js/datepicker/css/ui-lightness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
<script src="style/js/datepicker/js/jquery-1.9.1.js"></script>
<script src="style/js/datepicker/js/jquery-ui-1.10.3.custom.js"></script>
<script> 
  $(function() {
    $( "#fe_nac" ).datepicker();
    $( "#fe_nac" ).datepicker( "option", "showAnim", "show" );
	$( "#fe_nac" ).datepicker( "option", "changeMonth", true );
	$( "#fe_nac" ).datepicker( "option", "changeYear", true );
	$( "#fe_nac" ).datepicker( "option", "showOtherMonths", true );
	$( "#fe_nac" ).datepicker( "option", "dateFormat", "dd/mm/yy" );
  });
</script>
<style type="text/css">
    .ui-datepicker {
        font-size: 12px;
		margin-top:0px;
     }
	 .ui-datepicker td a{    
  		padding: 0.0em;
		text-align:center;
	 }
	 .ui-datepicker td span{    
  		padding: 0.0em;
		text-align:center;
	 }
</style>
<!-- Fin datepicker --> 

<script type="text/javascript" src="style/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript">jQuery.noConflict();</script>
<script type="text/javascript" src="style/js/ddsmoothmenu.js"></script>
<script type="text/javascript" src="style/js/html5.js"></script>
<script type="text/javascript" src="style/js/jquery.fitvids.js"></script>
<script type="text/javascript" src="style/js/selectnav.js"></script>
<script type="text/javascript" src="style/js/twitter.min.js"></script>
<script type="text/javascript">
function minuto_ini_change() {
	while(document.getElementById("select_minuto_fin").options.length > 0) document.getElementById("select_minuto_fin").remove(0);
	var tiempoTurno = parseInt(document.getElementById("tiempo_turno").value);
	var valor = parseInt(document.getElementById("select_minuto_ini").value);
	if(tiempoTurno == 0) document.getElementById("select_minuto_fin").add(new Option(valor, valor));
	valor = valor + tiempoTurno;
	if(valor > 59) valor = valor - 60;
	var valor_aux = valor;
	valor = valor + tiempoTurno;
	if(valor > 59) valor = valor - 60;
	if(tiempoTurno > 0) document.getElementById("select_minuto_fin").add(new Option(valor, valor));
	if(tiempoTurno > 0) document.getElementById("select_minuto_fin").add(new Option(valor_aux, valor_aux));
}
</script>
<!-- 
minuto_ini_changhe, sirve para setear los valores posibles del minuto en el cual termina el horario
de atención. El cálculo lo hace en base a la variable tiempo_turno, que se obtiene de la db
-->

</head>

<body class="singular page">

<div id="page" class="hfeed">
<div id="wrapper">
<header id="branding" role="banner">
  <h1 id="site-title"> 
  	<a href="index.php" title="Psicomind" rel="home">
    		<img src="style/images/logo.png" alt="Psicomind" />
    </a> 
  </h1>
  <div class="social">
    <ul>
      <li><a href="logout.php">Cerrar Sesión</a></li>
    </ul>
  </div>
  <nav id="access" class="access" role="navigation">
       {menu}
		<div class="triangle-l"></div>
		<div class="triangle-r"></div>
  </nav>
  <!-- #access --> 
</header>
<!-- #branding -->

<div id="main">

<div id="primary">
  <div id="content" role="main">
  <div class="intro">
 	Nuevo Horario de Atención
  </div>
  <!-- begin article -->
  <article class="page hentry">
    <p style="color:red;">
    {error}
    </p>
    <!-- Begin Form -->
    <div class="form-container">
      <div class="response"></div>
      <form class="forms" action="guardar_nuevo_horario_atencion.php" method="post" enctype="multipart/form-data">
        <fieldset>
    	<table class="table" width="100%">
            <tr class="tr">
                <td class="td" width="33%">Seleccione el turno</td>
                <td class="td" width="33%">
                	<select name="select_turno" id="select_turno" class="select">
                    	<tpl loop="bloque_turno">
                    	<option value="{bloque_turno.id}" {bloque_turno.selected} >{bloque_turno.valor}</option>
                        </tpl loop="bloque_turno">
                    </select>*
                </td>
                <td class="td" width="33%">&nbsp;</td>
            </tr>
            <tr class="tr">
                <td class="td" width="33%">Hora de Inicio</td>
                <td class="td" width="33%">
                	<select name="select_hora_ini" id="select_hora_ini" class="select">
                    	<tpl loop="bloque_hora_ini">
                    	<option value="{bloque_hora_ini.id}" {bloque_hora_ini.selected} >{bloque_hora_ini.valor}</option>
                        </tpl loop="bloque_hora_ini">
                    </select>*
                </td>
                <td class="td" width="33%">
                	<select name="select_minuto_ini" id="select_minuto_ini" class="select" onChange="minuto_ini_change();">
                    	<tpl loop="bloque_minuto_ini">
                    	<option value="{bloque_minuto_ini.id}" {bloque_minuto_ini.selected} >{bloque_minuto_ini.valor}</option>
                        </tpl loop="bloque_minuto_ini">
                    </select>*
                </td>
            </tr>
            <tr class="tr">
                <td class="td" width="33%">Hora de Fin</td>
                <td class="td" width="33%">
                	<select name="select_hora_fin" id="select_hora_fin" class="select">
                    	<tpl loop="bloque_hora_fin">
                    	<option value="{bloque_hora_fin.id}" {bloque_hora_fin.selected} >{bloque_hora_fin.valor}</option>
                        </tpl loop="bloque_hora_fin">
                    </select>*
                </td>
                <td class="td" width="33%">
                	<select name="select_minuto_fin" id="select_minuto_fin" class="select">
                    </select>*
                </td>
            </tr>
        </table>
        <input type="hidden" id="tiempo_turno" name="tiempo_turno" value="{tiempo_turno}">
        <br>
        <table class="table" width="100%">
          <tr class="tr">
            <td class="td" width="25%">
              <input type="submit" value="Guardar" name="guardar" id="guardar" class="btn-submit" />
            </td>
            <td class="td" width="25%">
              <input type="button" value="Volver" name="cancelar" id="cancelar" class="btn-submit" 
              onClick="location.href='index.php'">
            </td>
            <td class="td" width="%50">
            	<span>Los campos marcados con * son obligatorios</span>
            </td>
          </tr>
        </table>
        </fieldset>
      </form>
    </div>
    <!-- End Form -->
    
  </article>
  <!-- end article -->
  

  
  </div><!-- #content -->
</div><!-- #primary -->


</div><!-- #main -->

	<footer id="colophon" role="contentinfo">
		<div id="site-generator">
			Copyright 2014 - Guillermo Ordíz & Lorena Doello
		</div>

	</footer><!-- #colophon -->
	</div><!-- #wrapper -->
</div><!-- #page -->

<script type="text/javascript" src="style/js/scripts.js"></script>
</body>
</html>