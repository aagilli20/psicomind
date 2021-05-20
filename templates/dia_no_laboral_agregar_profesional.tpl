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
<title>Psicomind - Agregar profesional a un día no laboral</title>
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
<script type="text/javascript" src="style/js/jquery-1.7.1.min.js"></script>
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
  
  <div class="intro">Agregar profesional a un día no laboral</div>
				
  <!-- begin article -->
  <article class="page hentry">
    <p style="color:red;">
    {error}
    </p>
    <!-- Begin Form -->
    <div class="form-container">
      <div class="response"></div>
      <form class="forms" action="dia_no_laboral_agregar_profesional.php" method="post" enctype="multipart/form-data">
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
        <input type="hidden" name="id_dia" id="id_dia" value="{id_dia_no_laboral}" />
        </fieldset>
      </form>
      <br>
      <form class="forms" action="guardar_dia_no_laboral_profesional.php" method="post" enctype="multipart/form-data">
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
                	<input type="checkbox" name="matricula[]" id="{bloque_persona.id_persona}" value="{bloque_persona.id_persona}"
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
            <td class="td" width="30%" style="text-align:center;">
              <input type="button" value="Cancelar" name="cancelar" id="cancelar" class="btn-submit" 
              onClick="location.href='listado_dias_no_laborales.php'">
            </td>
             <td class="td" width="30%" style="text-align:center;">
              <input type="submit" value="Guardar" name="guardar" id="guardar" class="btn-submit" />
            </td>
            <td class="td" width="40%" style="text-align:center;">
              <input type="button" value="Asignar a Todos" name="guardar_todos" id="guardar_todos" class="btn-submit" 
              onClick="location.href='guardar_dia_no_laboral_todo_profesional.php?id={id_dia_no_laboral}'">
            </td>
          </tr>
        </table>
        <br>
        <table class="table" width="70%">
        	<td class="td" width="25%">Día no laboral:</td>
        	<td class="td" width="75%">
            	<input type="text" name="fecha_no_laboral" id="fecha_no_laboral" value="{fecha_no_laboral}" 
                class="text-input defaultText required" readonly />
            </td>
        </table>
        <input type="hidden" id="id_dia_no_laboral" name="id_dia_no_laboral" value="{id_dia_no_laboral}" />
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