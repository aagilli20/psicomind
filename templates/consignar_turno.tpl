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
<title>Psicomind - Consignar turno</title>
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
	$( "#fe_turno" ).datepicker( "option", "changeMonth", false );
	$( "#fe_turno" ).datepicker( "option", "changeYear", false );
	$( "#fe_turno" ).datepicker( "option", "showOtherMonths", false );
	$( "#fe_turno" ).datepicker( "option", "dateFormat", "dd/mm/yy" );
	$( "#fe_turno" ).datepicker( "option", "minDate", "dateToday" );
	$( "#fe_turno" ).datepicker( "option", "numberOfMonths", 3 );
	$( "#fe_turno" ).datepicker( "option", "beforeShowDay", $.datepicker.noWeekends )
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
  
  <div class="intro">Consignar turno - Paso 3</div>
				
  <!-- begin article -->
  <article class="page hentry">
    <p style="color:red;">
    {error}
    </p>
    <!-- Begin Form -->
    <div class="form-container">
      <div class="response"></div>
      <form class="forms" action="confirmar_turno.php" method="post" enctype="multipart/form-data">
		<p>Paciente seleccionado: {nombre_paciente}</p>
        <p>Profesional seleccionado: {nombre_profesional}</p>
      	<p>Horarios de atención del Profesional</p>
        <fieldset>
        <table class="table" width="100%" id="tpersona">
        	<tr>
            	<th width="25%" style="text-align:center;"><b>Día</b></td>
                <th width="25%" style="text-align:center;"><b>Turno</b></td>
                <th width="25%" style="text-align:center;"><b>Hora Inicio</b></td>
                <th width="25%" style="text-align:center;"><b>Hora Fin</b></td>
            </tr>
        	<tpl loop="bloque_persona">
            <tr>
                <td width="25%" style="text-align:center;">{bloque_persona.dia}</td>
                <td width="25%" style="text-align:center;">{bloque_persona.turno}</td>
        		<td width="25%" style="text-align:center;">{bloque_persona.hora_ini}</td>
                <td width="25%" style="text-align:center;">{bloque_persona.hora_fin}</td>
			</tr>
            </tpl loop="bloque_persona">
        </table>
        <br>
        <p>Seleccione la fecha y el turno deseado</p>
        <table class="table" width="100%">
          <tr class="tr">
            <td class="td" width="50%">
              Seleccione el turno
            </td>
             <td class="td" width="50%">
              <select name="select_turno" id="select_turno" class="select">
              	<tpl loop="bloque_turno">
                 	<option value="{bloque_turno.id}" {bloque_turno.selected} >{bloque_turno.valor}</option>
                </tpl loop="bloque_turno">
              </select>*
            </td>
          </tr>
          <tr class="tr">
            <td class="td" width="50%">
              Ingrese la fecha deseada
            </td>
             <td class="td" width="50%">
              <input type="text" title="DD/MM/AAAA" value="{fe_turno}" name="fe_turno" id="fe_turno"
					class="text-input defaultText required"/>*
            </td>
          </tr>
        </table>
        <p>Los campos marcados con * son obligatorios</p>
        <br>
        <table class="table" width="100%">
          <tr class="tr">
            <td class="td" width="50%" style="text-align:center;">
              <input type="button" value="Volver" name="cancelar" id="cancelar" class="btn-submit" 
              onClick="location.href='elegir_profesional_consignar_turno.php?id={id_paciente2}'">
            </td>
             <td class="td" width="50%" style="text-align:center;">
              <input type="submit" value="Siguiente" name="seleccionar" id="seleccionar" class="btn-submit" {sin_horarios} />
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