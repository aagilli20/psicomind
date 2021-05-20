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
<title>Psicomind - Turnos por Día</title>
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
    $( "#fecha" ).datepicker();
    $( "#fecha" ).datepicker( "option", "showAnim", "show" );
	$( "#fecha" ).datepicker( "option", "changeMonth", false );
	$( "#fecha" ).datepicker( "option", "changeYear", false );
	$( "#fecha" ).datepicker( "option", "showOtherMonths", false );
	$( "#fecha" ).datepicker( "option", "dateFormat", "dd/mm/yy" );
	$( "#fecha" ).datepicker( "option", "minDate", "dateToday" );
	$( "#fecha" ).datepicker( "option", "numberOfMonths", 3 );
	$( "#fecha" ).datepicker( "option", "beforeShowDay", $.datepicker.noWeekends )
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
  
  <div class="intro">Turnos por Día - Paso 1</div>
				
  <!-- begin article -->
  <article class="page hentry">
    <p style="color:red;">
    {error}
    </p>
    <!-- Begin Form -->
    <div class="form-container">
      <div class="response"></div>
      <form class="forms" action="elegir_profesional_turnos.php" method="post" enctype="multipart/form-data"> 
        <p>Filtrar el listado de profesionales</p>
        <fieldset>
        <table class="table" width="100%">
			<tr class="tr">
                <td class="td" width="40%">
					<input type="text" title="Número de documento" name="documento" id="documento" value="{documento}"
                    class="text-input defaultText required"/>
                </td>
                 <td class="td" width="40%">
					<input type="text" title="Apellido" name="apellido" id="apellido" value="{apellido}" class="text-input defaultText required"/>
                </td>
                <td class="td" width="20%">
              		<input type="submit" value="Filtrar" name="filtrar" id="filtrar" class="btn-submit">
            	</td>
            </tr>
        </table>
        </fieldset>
      </form>
      <br>
      <form class="forms" action="listado_turnos_por_dia.php" method="post" enctype="multipart/form-data">
      	<p>Seleccionar profesional</p>
        <fieldset>
        <table class="table" width="100%" id="tpersona">
        	<tr>
            	<th width="25%" style="text-align:center;"><b>Documento</b></td>
            	<th width="25%" style="text-align:center;"><b>Apellido</b></td>
                <th width="25%" style="text-align:center;"><b>Nombres</b></td>
                <th width="25%" style="text-align:center;"><b>Seleccionar</b></td>
            </tr>
        	<tpl loop="bloque_persona">
            <tr>
            	<td width="25%" style="text-align:center;">{bloque_persona.documento}</td>
                <td width="25%" style="text-align:center;">{bloque_persona.apellido}</td>
                <td width="25%" style="text-align:center;">{bloque_persona.nombre}</td>
        		<td width="25%" style="text-align:center;">
                	<input type="radio" name="matricula" id="{bloque_persona.id_persona}" value="{bloque_persona.id_persona}"
                    {bloque_persona.disabled} >
                </td>
			</tr>
            </tpl loop="bloque_persona">
        </table>

        <!-- Begin Page-navi -->
    	<ul class="page-navi">
    		<li><a href="{enlace_pagina_prev}" >{pagina_prev}</a></li>
    		<li><a href="#" class="current">{pagina}</a></li>
    		<li><a href="{enlace_pagina_prox}">{pagina_prox}</a></li>
    	</ul>
    	<!-- End Page-navi -->
        <br>
        <table class="table" width="100%">
          <tr class="tr">
            <td class="td" width="50%">
              Ingrese la fecha deseada
            </td>
             <td class="td" width="50%">
              <input type="text" title="DD/MM/AAAA" value="{fecha}" name="fecha" id="fecha"
					class="text-input defaultText required"/>*
            </td>
          </tr>
        </table>
        
        <br>
        <table class="table" width="100%">
          <tr class="tr">
            <td class="td" width="50%" style="text-align:center;">
              <input type="button" value="Cancelar" name="cancelar" id="cancelar" class="btn-submit" onClick="location.href='index.php'">
            </td>
             <td class="td" width="50%" style="text-align:center;">
              <input type="submit" value="Ver Turnos" name="seleccionar" id="seleccionar" class="btn-submit" />
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