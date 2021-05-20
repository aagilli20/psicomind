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
<title>Psicomind - Datos del Paciente</title>
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
  
  <div class="intro">Datos del Paciente</div>
				

  <!-- begin article -->
  <article class="page hentry">
    <p style="color:red;">
    {error}
    </p>
    <!-- Begin Form -->
    <div class="form-container">
      <div class="response"></div>
      <form class="forms" action="#" method="post" enctype="multipart/form-data">
        <fieldset>
        <table class="table" width="100%">
        	<tr class="tr">
            	<td class="td" width="50%">
                	Nombres:
                </td>
            	<td class="td" width="50%">
                	<input type="text" name="nombre" value="{nombre}" id="nombre"
                    readonly class="text-input defaultText required" />
                </td>
             </tr>
             <tr class="tr">
             	<td class="td" width="50%">
                	Apellido:
                </td>
                <td class="td" width="50%">
					<input type="text" name="apellido" id="apellido" value="{apellido}"
                    readonly class="text-input defaultText required"/>
                </td>
            </tr>
            <tr class="tr">
            	<td class="td" width="50%">
                	Código:
                </td>
            	<td class="td" width="50%">
                	<input type="text" name="código" value="{codigo}" 
                    id="codigo" readonly class="text-input defaultText required"/>
                </td>
            </tr>
            <tr class="tr">
               	<td class="td" width="50%">
                	Domicilio:
                </td>
                <td class="td" width="50%">
					<input type="text" name="domicilio" id="domicilio" value="{domicilio}"
                    readonly class="text-input defaultText required"/>
                </td>
            </tr>
            <tr class="tr">
            	<td class="td" width="50%">
                	Teléfono (0342-45011XX):
                </td>
            	<td class="td" width="50%">
                	<input type="text" name="telefono" value="{telefono}" id="telefono"
                    readonly class="text-input defaultText"/>
                </td>
            </tr>
            <tr class="tr">
            	<td class="td" width="50%">
                	Celular (34245677XX):
                </td>
                <td class="td" width="50%">
					<input type="text" name="celular" id="celular" value="{celular}"
                    readonly class="text-input defaultText"/>
                </td>
            </tr>
            <tr class="tr">
            	<td class="td" width="50%">
                	Correo electrónico:
                </td>
            	<td class="td" width="50%">
                	<input type="text" name="email" value="{email}" id="email"
                    readonly class="text-input defaultText"/>
                </td>
             </tr>
             <tr class="tr">
            	<td class="td" width="50%">
                	Fecha de alta en el sistema:
                </td>
            	<td class="td" width="50%">
                	<input type="text" name="fecha_desde" value="{fecha_desde}" id="fecha_desde"
                    readonly class="text-input defaultText" />
                </td>
             </tr>
        </table>
        <br>
        <table class="table" width="100%">
          <tr class="tr">
            <td class="td" width="34%" style="text-align:center;">
              <input type="button" value="Modificar datos personales" name="mdp" id="mdp" class="btn-submit"
               onClick="location.href='ver_datos_persona.php?id={id_persona}'" />
            </td>
             <td class="td" width="33%" style="text-align:center;">
              <input type="button" value="Imprimir" name="imprimir" id="imprimir" class="btn-submit" />
            </td>
            <td class="td" width="33" style="text-align:center;">
              <input type="button" value="Volver" name="volver" id="volver" class="btn-submit" onClick="location.href='listado_pacientes.php'">
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