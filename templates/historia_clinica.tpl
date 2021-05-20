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
<title>Psicomind - Historia Clínica</title>
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
        <div class="intro">Historia Clínica</div>
    </header>
    <!-- .entry-header -->
    <!-- Begin Form -->
    <div class="form-container">
      <div class="response"></div>
      <table class="table" width="100%" id="tpersona">
        	<tr>
            	<td width="50%" style="text-align:left;">Paciente seleccionado: <a href="ver_datos_paciente.php?id={id_paciente2}">{nombre_paciente}</a></td>
                <td width="40%" style="text-align:left;">Sexo: {sexo}</td>
                <td width="10%" rowspan="4" style="text-align:center; vertical-align:bottom">
                	<img src="{url_img}" width="80px" height="80px" />
                </td>
			</tr>
            <tr >
            	<td width="50%" style="text-align:left;">Fecha de nacimiento: {fe_nac}</td>
                <td width="40%" style="text-align:left;">Edad: {edad} años</td>
			</tr>
            <tr >
            	<td width="90%" colspan="2" style="text-align:left;">Fecha desde que es paciente: {fecha_desde}</td>
			</tr>
            <tr><td></td><td></td></tr>
            <tr >
            	<td width="50%" style="text-align:left;"><b>Fichas de la historia clínica</b></td>
            	<td width="40%" style="text-align:left;"><a href="{url_enlace}">{enlace}</a></td>
			</tr>
       	</table>
       <form class="forms" action="#" method="post" enctype="multipart/form-data">
      	<fieldset>
    <table id="data_table" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Motivo</th>
                <th>Registrado por</th>
                <th>Ver Ficha</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Motivo</th>
                <th>Registrado por</th>
                <th>Ver Ficha</th>
            </tr>
        </tfoot>
        <tbody>
           	<tpl loop="bloque_ficha_hc">
            <tr>
                <td>{bloque_ficha_hc.id}</td>
                <td>{bloque_ficha_hc.fecha}</td>
                <td>{bloque_ficha_hc.motivo}</td>
                <td>{bloque_ficha_hc.usuario}</td>
                <td>
                	<a href="ver_datos_ficha_historia_clinica.php?id={bloque_ficha_hc.id2}">
                    	<img alt="+" title="Ver ficha completa" width="18px" height="18px" src="./style/images/mono-icons/note32.png">
                    </a>
                </td>
            </tr>
            </tpl loop="bloque_ficha_hc">
	    </tbody>
    </table>
 
        <table class="table" width="100%">
			<tr class="tr">
                <td class="td" width="33%" style="text-align:center">
					<input type="button" name="nueva_ficha" id="nueva_ficha" value="Nueva Ficha Simple" class="btn-submit" />
                </td>
                 <td class="td" width="33%" style="text-align:center">
					<input type="button" name="nueva_sesion" id="nueva_sesion" value="Nueva Sesión" class="btn-submit" />
                </td>
                <td class="td" width="33%" style="text-align:center">
              		<input type="button" name="nuevo_test" id="nuevo_test" value="Nuevo Test" class="btn-submit" />
            	</td>
            </tr>
        </table>
        <p><b>Historial Farmacológico</b></p>
        <table id="data_table2" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Medicamento</th>
                <th>Droga</th>
                <th>Fecha prescripción</th>
                <th>Dosis</th>
                <th>Registrado por</th>
                <th>Ver Observación</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Medicamento</th>
                <th>Droga</th>
                <th>Fecha prescripción</th>
                <th>Dosis</th>
                <th>Registrado por</th>
                <th>Ver Observación</th>
            </tr>
        </tfoot>
        <tbody>
           	<tpl loop="bloque_hist_farm">
            <tr>
                <td>{bloque_hist_farm.medicamento}</td>
                <td>{bloque_hist_farm.droga}</td>
                <td>{bloque_hist_farm.fecha}</td>
                <td>{bloque_hist_farm.dosis}</td>
                <td>{bloque_hist_farm.usuario}</td>
                <td>
                	<a href="ver_datos_hist_farmacologico?id={bloque_hist_farm.id2}">
                    	<img alt="+" title="Ver observación" width="18px" height="18px" src="./style/images/mono-icons/note32.png">
                    </a>
                </td>
            </tr>
            </tpl loop="bloque_hist_farm">
	    </tbody>
    </table>
    <table class="table" width="100%">
			<tr class="tr">
                <td class="td" width="100%" style="text-align:center">
					<input type="button" name="nueva_prescripcion" id="nueva_prescripcion" value="Nueva prescripción" class="btn-submit" />
                </td>
            </tr>
        </table>
        <br><p><b>Minusvalías del paciente:</b>{minusvalias}</p>
        <table class="table" width="100%" id="tpersona">
	        <tr >
            	<td width="50%" style="text-align:left;">Obra Social: {obra_social}</td>
                <td width="50%" style="text-align:left;">Plan: {plan_os}</td>
			</tr>
        </table>
        <table class="table" width="100%">
			<tr class="tr">
                <td class="td" width="100%" style="text-align:center">
					<input type="button" name="volver" id="volver" value="Volver" class="btn-submit"
                    onClick="location.href='elegir_paciente_historia_clinica.php'" />
                </td>
            </tr>
        </table>
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