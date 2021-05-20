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
<title>Psicomind - Listado de Personas</title>
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
  
  <div class="intro">Listado de Personas</div>
				
  <!-- begin article -->
  <article class="page hentry">
    <p style="color:red;">
    {error}
    </p>
    <!-- Begin Form -->
    <div class="form-container">
      <div class="response"></div>
      <form class="forms" action="listado_personas.php" method="post" enctype="multipart/form-data">
      	<p>Filtrar el listado de personas</p>
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
      <form class="forms" action="#" method="post" enctype="multipart/form-data">
      	<p>Seleccionar persona</p>
        <fieldset>
        <table class="table" width="100%" id="tpersona">
        	<tr>
            	<th width="25%" style="text-align:center;"><b>Documento</b></td>
            	<th width="25%" style="text-align:center;"><b>Apellido</b></td>
                <th width="25%" style="text-align:center;"><b>Nombres</b></td>
                <th width="25%" style="text-align:center;"><b>Operaciones</b></td>
            </tr>
        	<tpl loop="bloque_persona">
            <tr>
            	<td width="25%" style="text-align:center;">{bloque_persona.documento}</td>
                <td width="25%" style="text-align:center;">{bloque_persona.apellido}</td>
                <td width="25%" style="text-align:center;">{bloque_persona.nombre}</td>
        		<td width="25%" style="text-align:center;">
                	<a href="ver_datos_persona.php?id={bloque_persona.id_persona2}">
                    	<img alt="+" title="Ver datos completos" width="18px" height="18px" src="./style/images/mono-icons/pencil32.png">
                    </a>
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
            <td class="td" width="100%" style="text-align:center;">
              <input type="button" value="Volver" name="cancelar" id="cancelar" class="btn-submit" onClick="location.href='index.php'">
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