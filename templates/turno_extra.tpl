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
<title>Psicomind - Asiganr Turno Extra</title>
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
    $( "#fe_turno" ).datepicker();
    $( "#fe_turno" ).datepicker( "option", "showAnim", "show" );
	$( "#fe_turno" ).datepicker( "option", "changeMonth", true );
	$( "#fe_turno" ).datepicker( "option", "changeYear", true );
	$( "#fe_turno" ).datepicker( "option", "showOtherMonths", true );
	$( "#fe_turno" ).datepicker( "option", "dateFormat", "dd/mm/yy" );
	$( "#fe_turno" ).datepicker( "option", "minDate", "dateToday" );
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
<!-- tablas -->

</head>

<body class="singular page">

<div id="page" class="hfeed">
<div id="wrapper">
<header id="branding" role="banner">
  <h1 id="site-title"> 
  	<a href="index.php" title="Serendipity" rel="home">
    		<img src="style/images/logo.png" alt="Serendipity" />
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
  
  <div class="intro">Asignar Turno Extra</div>
				
  <!-- begin article -->
  <article class="page hentry">
    <p style="color:red;">
    ¡Atención! Recuerde que está forzando la asignación de un turno, notifique al profesional respecto de esta asignación
    <br>
    {error}
    </p>
    <!-- Begin Form -->
    <div class="form-container">
      <div class="response"></div>
      <form class="forms" action="guardar_turno_extra.php" method="post" enctype="multipart/form-data">
		<p>Paciente seleccionado: {nombre_paciente}</p>
        <p>Profesional seleccionado: {nombre_profesional}</p>
        <p>Día del turno: {dia_turno}</p>
        <p>Turno elegido: {turno_elegido}</p>
        <input type="hidden" name="dia_elegido" id="dia_elegido" value="{dia_turno2}">
        <input type="hidden" name="id_turno" id="id_turno" value="{id_turno}">
        <fieldset>
        <table class="table" width="100%">
        <tr class="tr">
                <td class="td" width="33%">Horario del turno</td>
                <td class="td" width="33%">
                	<select name="select_hora_ini" id="select_hora_ini" class="select">
                    	<tpl loop="bloque_hora_ini">
                    	<option value="{bloque_hora_ini.id}" {bloque_hora_ini.selected} >{bloque_hora_ini.valor}</option>
                        </tpl loop="bloque_hora_ini">
                    </select>*
                </td>
                <td class="td" width="33%">
                	<select name="select_minuto_ini" id="select_minuto_ini" class="select">
                    	<tpl loop="bloque_minuto_ini">
                    	<option value="{bloque_minuto_ini.id}" {bloque_minuto_ini.selected} >{bloque_minuto_ini.valor}</option>
                        </tpl loop="bloque_minuto_ini">
                    </select>*
                </td>
            </tr>
        </table>
        <table class="table" width="100%">
          <tr class="tr">
            <td class="td" width="33%" style="text-align:center;">
              <input type="button" value="Volver" name="cancelar" id="cancelar" class="btn-submit" 
              onClick="location.href='consignar_turno.php?id1={id_paciente2}&id2={id_profesional2}'" />
            </td>
             <td class="td" width="33%" style="text-align:center;">
              <input type="submit" value="Confirmar" name="guardar" id="guardar" class="btn-submit" />
            </td>
          </tr>
        </table>
        </fieldset>
        <input type="hidden" name="id_paciente" id="id_paciente" value="{id_paciente}" />
        <input type="hidden" name="id_profesional" id="id_profesional" value="{id_profesional}" />
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