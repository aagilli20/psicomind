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
<title>Psicomind - Datos personales</title>
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
  <div class="intro">
  	<img name="img_usuario" src="{url_foto}" style="width:110px; height:110px;" 
    align="right" alt="Foto de la persona">	
 	Datos personales
  </div>
  <!-- begin article -->
  <article class="page hentry">
    <p style="color:red;">
    {error}
    </p>
    <!-- Begin Form -->
    <div class="form-container">
      <div class="response"></div>
      <form class="forms" action="modificar_persona.php" method="post" enctype="multipart/form-data">
        <fieldset>
        <input type="hidden" id="id_persona" name="id_persona" value="{id_persona}">
        <table class="table" width="100%">
			<tr class="tr">
            	<td class="td" width="50%">
                	Tipo de documento:
                </td>
            	<td class="td" width="50%">
                	<input type="text" value="{tipo_doc_valor}" name="tipo_doc_valor" id="tipo_doc_valor"
				    readonly class="text-input defaultText required"/>
                </td>
            </tr>
            <tr class="tr">
                <td class="td" width="50%">
                	Número de documento:
                </td>
                <td class="td" width="50%">
					<input type="text" name="documento" id="documento" value="{documento}"
                    readonly class="text-input defaultText required"/>
                </td>
            </tr>
			<tr class="tr">
            	<td class="td" width="50%">
                	Sexo:
                </td>
                <td class="td" width="50%">
					<input type="text" name="sexo_valor" id="sexo_valor" value="{sexo_valor}"
                    readonly class="text-input defaultText required"/>
                </td>
           	</tr>
            <tr class="tr">
            	<td class="td" width="50%">
                	Fecha de Nacimiento (DD/MM/AAAA):
                </td>
                <td class="td" width="50%">
                	<input type="text" value="{fenac}" name="fe_nac" id="fe_nac"
					class="text-input defaultText required"/>
                </td>
            </tr>
			<tr class="tr">
            	<td class="td" width="50%">
                	Nombres:
                </td>
            	<td class="td" width="50%">
                	<input type="text" name="nombre" value="{nombre}" id="nombre" class="text-input defaultText required"/>*
                </td>
            </tr>
            <tr class="tr">
            	<td class="td" width="50%">
                	Apellido:
                </td>
                <td class="td" width="50%">
					<input type="text" name="apellido" id="apellido" value="{apellido}" class="text-input defaultText required"/>*
                </td>
            </tr>
            <tr class="tr">
            	<td class="td" width="50%">
                	Teléfono (0342-45011XX):
                </td>
            	<td class="td" width="50%">
                	<input type="text" name="telefono" value="{telefono}" id="telefono"
                    class="text-input defaultText"/>
                </td>
            </tr>
            <tr class="tr">
            	<td class="td" width="50%">
                	Celular (34245677XX):
                </td>
                <td class="td" width="50%">
					<input type="text" name="celular" id="celular" value="{celular}"
                    class="text-input defaultText"/>
                </td>
            </tr>
            <tr class="tr">
            <td class="td" width="50%">
                	Correo electrónico:
                </td>
            	<td class="td" width="50%">
                	<input type="text" name="email" value="{email}" id="email"
                    class="text-input defaultText"/>
                </td>
            </tr>
            <tr class="tr">
            <td class="td" width="50%">
                	Domicilio:
                </td>
                <td class="td" width="50%">
					<input type="text" name="domicilio" id="domicilio" value="{domicilio}"
                    class="text-input defaultText"/>
                </td>
             </tr>
        </table>
     	<table class="table" width="80%">
            <tr class="tr">
                <td class="td" width="1%">
                	<input type="file" accept="image/*" name="url_foto" id="url_foto" style="display:none"
                    onChange="document.getElementById('url_show').value=this.value;"/>
                </td>
            	<td class="td" width="24%">
                	<input type="button" value="Cargar una foto" name="btn_upload" id="btn_upload" class="btn-submit"
                    onclick="document.getElementById('url_foto').click();" />
                </td>
                <td class="td" width="75%">
                	<input type="text" value="" name="url_show" id="url_show" class="text-input" />
                </td>
            </tr>
        </table>
        <br>
        <table class="table" width="100%">
          <tr class="tr">
            <td class="td" width="15%">
              <input type="submit" value="Modificar" name="guardar" id="guardar" class="btn-submit" />
            </td>
             <td class="td" width="15%">
              <input type="button" value="Imprimir" name="imprimir" id="imprimir" class="btn-submit" />
            </td>
            <td class="td" width="15%">
              <input type="button" value="Volver" name="cancelar" id="cancelar" class="btn-submit" 
              onClick="location.href='listado_personas.php'">
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