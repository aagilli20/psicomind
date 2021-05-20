<!DOCTYPE HTML>
<html lang="es-AR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<!-- content -->
<meta name="description" content="sistema para la gestión de consultorios psicológicos" />
<meta name="keywords" content="psicólogo, psicología, psicológico, problemas, atención, crisis, vertigo, psychologist, psychology, psychological issues, health, crisis, dizziness" />
<meta name="author" content="Guillermo Ordíz, Lorena Doello" />
<!-- end content -->
<title>Psicomind - Asociar Obra Social</title>
<link rel="shortcut icon" href="style/images/favicon.png"/>
<!--Inicio data_table -->
<link rel="stylesheet" type="text/css" href="./data_table/css/jquery.dataTables.css">
<!--Fin data_table -->
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
<!--Inicio data_table -->
<script type="text/javascript" language="javascript" src="./data_table/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="./data_table/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
	$('#data_table').DataTable();
	$('#data_table2').DataTable();
} );
</script>
<!--Fin data_table -->
<script type="text/javascript" src="style/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript">jQuery.noConflict();</script>
<script type="text/javascript" src="style/js/ddsmoothmenu.js"></script>
<script type="text/javascript" src="style/js/html5.js"></script>
<script type="text/javascript" src="style/js/jquery.fitvids.js"></script>
<script type="text/javascript" src="style/js/	selectnav.js"></script>
<script type="text/javascript" src="style/js/twitter.min.js"></script>
</head>

<body>
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
  
  <!-- begin article -->
  <article class="post hentry format-gallery">
  	<header class="entry-header">
    	<!-- .entry-meta -->
        <div class="intro">Asociar Obra Social - Paso 2</div>
    </header>
    <!-- .entry-header -->
    <!-- Begin Form -->
    <div class="form-container">
      <div class="response"></div>
      <table class="table" width="100%" id="tpersona">
        	<tr>
            	<td width="100%" colspan="3" style="text-align:left;">
                	Paciente seleccionado: <a href="ver_datos_paciente.php?id={id_paciente2}">{nombre_paciente}</a>
                </td>
            </tr>
            <tr>
               	<td width="40%" style="text-align:left;">Obra Social: {obra_social}</td>
                <td width="40%" style="text-align:left;">Plan: {plan_os}</td>
                <td width="20%" style="text-align:center">{eliminar_os}</td>
            </tr>
            <tr>
            	<td width="100%" colspan="3" style="text-align:left; color:red;">
                	Recuerde que el paciente solamente puede tener asociada una obra social y plan
                </td>
            </tr>
       	</table>
       <form class="forms" action="guardar_paciente_obra_social.php" method="post" enctype="multipart/form-data">
      	<fieldset>
        <p>Seleccione la obra social y plan del paciente</p>
    <table id="data_table" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Obra Social</th>
                <th>Plan</th>
                <th>Seleccionar</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Obra Social</th>
                <th>Plan</th>
                <th>Seleccionar</th>
            </tr>
        </tfoot>
        <tbody>
           	<tpl loop="bloque_os">
            <tr>
                <td>{bloque_os.nombre_os}</td>
                <td>{bloque_os.nombre_plan_os}</td>
                <td>
                	<input type="radio" name="id_os" id="{bloque_os.id_os}" value="{bloque_os.id_os}" >
                </td>
            </tr>
            </tpl loop="bloque_os">
	    </tbody>
    </table>
        <table class="table" width="100%">
			<tr class="tr">
                <td class="td" width="50%" style="text-align:center">
					<input type="button" name="volver" id="volver" value="Volver" class="btn-submit"
                    onClick="location.href='elegir_paciente_obra_social.php'" />
                </td>
                <td class="td" width="50%" style="text-align:center">
					<input type="submit" name="guardar" id="guardar" value="Guardar" class="btn-submit" />
                </td>
            </tr>
        </table>
        <input type="hidden" name="id_paciente" id="id_paciente" value="{id_paciente}" />
        </fieldset>
      </form>
    </div>
    <!-- End Form -->
    
    <!-- .entry-content -->
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