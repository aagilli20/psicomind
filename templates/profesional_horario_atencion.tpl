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
<title>Psicomind - Horario de atención del Profesional</title>
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
  
  <div class="intro">Asociar horario de atención al profesional - Paso 2</div>
				

  <!-- begin article -->
  <article class="page hentry">
    <p style="color:red;">
    {error}
    </p>
    <!-- Begin Form -->
    <div class="form-container">
      	<div class="response"></div>
      	<table class="table" width="100%">
        	<tr class="tr">
            	<td class="td" width="20%">
                	Profesional:
                </td>
            	<td class="td" width="40%">
                	<input type="text" title="Nombres" name="nombre" value="{nombre}" id="nombre"
                    readonly class="text-input defaultText required" />
                </td>
                <td class="td" width="40%">
					<input type="text" title="Apellido" name="apellido" id="apellido" value="{apellido}"
                    readonly class="text-input defaultText required"/>
                </td>
            </tr>
     	</table>
        <hr>
        <p>Horarios de atención actuales del profesional:</p>
        <table class="table" width="100%" id="thorarios_actual">
        	<tr>
            	<th width="20%" style="text-align:center;"><b>Día</b></td>
            	<th width="20%" style="text-align:center;"><b>Turno</b></td>
            	<th width="20%" style="text-align:center;"><b>Hora inicio</b></td>
                <th width="20%" style="text-align:center;"><b>Hora fin</b></td>
                <th width="20%" style="text-align:center;"><b>Eliminar</b></td>
            </tr>
        	<tpl loop="bloque_horario_actual">
            <tr>
            	<td width="20%" style="text-align:center;">{bloque_horario_actual.dia}</td>
            	<td width="20%" style="text-align:center;">{bloque_horario_actual.turno}</td>
                <td width="20%" style="text-align:center;">{bloque_horario_actual.hora_ini}</td>
                <td width="20%" style="text-align:center;">{bloque_horario_actual.hora_fin}</td>
        		<td width="20%" style="text-align:center;">
                	<a href="eliminar_profesional_horario_atencion.php?{bloque_horario_actual.id_horario}">
                    	<img alt="-" title="Eliminar horario de atención" width="18px" height="18px" src="./style/images/mono-icons/minus32.png">
                    </a>
                </td>
			</tr>
            </tpl loop="bloque_horario_actual">
        </table>
        <hr>
      	<form class="forms" action="profesional_horario_atencion.php" method="post" enctype="multipart/form-data">
        <fieldset>
        <input type="hidden" id="matricula" name="matricula" value="{matricula}" >
        <table class="table" width="100%">
			<tr class="tr">
                <td class="td" width="40%">
					Filtrar el listado de horarios de atención
                </td>
                 <td class="td" width="40%">
					<select name="select_turno" id="select_turno" class="select">
                    	<tpl loop="bloque_turnos">
                    	<option value="{bloque_turnos.id}" >{bloque_turnos.valor}</option>
                        </tpl loop="bloque_turnos">
                    </select>
                </td>
                <td class="td" width="20%">
              		<input type="submit" value="Filtrar" name="filtrar" id="filtrar" class="btn-submit">
            	</td>
            </tr>
        </table>
        </fieldset>
      	</form>
      	<form class="forms" action="guardar_profesional_horario_atencion.php" method="post" enctype="multipart/form-data">
        <fieldset>
        <input type="hidden" id="matricula" name="matricula" value="{matricula}" >
        <p>Asignar nuevo horario atención al profesional:</p>
        <table class="table" width="100%" id="thorarios">
        	<tr>
            	<th width="25%" style="text-align:center;"><b>Turno</b></td>
            	<th width="25%" style="text-align:center;"><b>Hora inicio</b></td>
                <th width="25%" style="text-align:center;"><b>Hora fin</b></td>
                <th width="25%" style="text-align:center;"><b>Seleccionar</b></td>
            </tr>
        	<tpl loop="bloque_horario">
            <tr>
            	<td width="25%" style="text-align:center;">{bloque_horario.turno}</td>
                <td width="25%" style="text-align:center;">{bloque_horario.hora_ini}</td>
                <td width="25%" style="text-align:center;">{bloque_horario.hora_fin}</td>
        		<td width="25%" style="text-align:center;">
                	<input type="radio" name="id_horario" id="{bloque_horario.id_horario}" value="{bloque_horario.id_horario}"
                    {bloque_horario.disabled} >
                </td>
			</tr>
            </tpl loop="bloque_horario">
        </table>
		<p>Seleccione el día de atención:</p>
        <table class="table" width="100%">
			<tr class="tr">
            	<td class="td" width="16%">
                	<input type="checkbox" name="dias[]" value="1"/> Lunes
                </td>
                <td class="td" width="16%">
    				<input type="checkbox" name="dias[]" value="2"/> Martes
                </td>
                <td class="td" width="16%">
                    <input type="checkbox" name="dias[]" value="3"/> Miércoles
                </td>
                <td class="td" width="16">
                	<input type="checkbox" name="dias[]" value="4"/> Jueves   
                </td>
                <td class="td" width="16%">    
                    <input type="checkbox" name="dias[]" value="5"/> Viernes
                </td>
                <td class="td" width="20%">
                    <input type="checkbox" name="dias[]" value="6"/> Sábado
                </td>
            </tr>
        </table>
        <p>Debe seleccionar al menos un día antes de guardar</p>
        <table class="table" width="100%">
          <tr class="tr">
            <td class="td" width="15%">
              <input type="submit" value="Guardar" name="guardar" id="guardar" class="btn-submit" />
            </td>
             <td class="td" width="15%">
              <input type="reset" value="Limpiar" name="limpiar" id="limpiar" class="btn-submit" />
            </td>
            <td class="td" width="15%">
              <input type="button" value="Cancelar" name="cancelar" id="cancelar" class="btn-submit" onClick="location.href='index.php'">
            </td>
            <td class="td" width="%55">
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