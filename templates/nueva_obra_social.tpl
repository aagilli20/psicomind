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
<title>Psicomind - Nueva Obra Social</title>
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
  
  <div class="intro">Nueva Obra Social</div>
				

  <!-- begin article -->
  <article class="page hentry">
    <p style="color:red;">
    {error}
    </p>
    <!-- Begin Form -->
    <div class="form-container">
      <div class="response"></div>
      <form class="forms" action="guardar_nueva_obra_social.php" method="post" enctype="multipart/form-data">
        <fieldset>
        <table class="table" width="100%">
			<tr class="tr">
            	<td class="td" width="50%">
					<input type="text" title="Nombre" name="nombre" id="nombre" value="{nombre}"
                    class="text-input defaultText required"/>*
                </td>
                <td class="td" width="50%">
					<input type="text" title="R.N.E.M.P." name="rnemp" id="rnemp" value="{rnemp}"
                    class="text-input defaultText required"/>
                </td>
            </tr>
			<tr class="tr">
            	<td class="td" width="50%">
                	<input type="text" title="Teléfono (0342-45011XX)" value="{telefono}" name="telefono" id="telefono"
					class="text-input defaultText required"/>
                </td>
                <td class="td" width="50%">
                	<input type="text" title="Dirección" value="{direccion}" name="direccion" id="direccion"
					class="text-input defaultText required"/>
                </td>
            </tr>
			<tr class="tr">
            	<td class="td" width="50%">
                	<input type="text" title="Localidad" name="localidad" value="{localidad}" id="localidad" 
                    class="text-input defaultText required"/>
                </td>
                <td class="td" width="50%">
					<input type="text" title="Provincia" name="provincia" id="provincia" value="{provincia}" 
                    class="text-input defaultText required"/>
                </td>
            </tr>
            <tr class="tr">
            	<td class="td" width="50%">
                	<input type="text" title="Código Postal" name="codigo_postal" value="{codigo_postal}" id="codigo_postal"
                    class="text-input defaultText"/>
                </td>
                <td class="td" width="50%">&nbsp;
					
                </td>
            </tr>
        </table>
        <br>
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