<?php
session_start();
if (isset($_SESSION['usuario']) && (empty($_SESSION['fotoAlum']) || empty($_SESSION['correo'])) )
{
    echo "<script type='text/javascript'>                                
        window.location='consulta.php?complet';
      </script>";
}
$url_actual =  $_SERVER["SERVER_NAME"];
if($url_actual!='localhost')
{ ?>
	<script type="text/javascript"> 
		if (screen.width<768) { window.location='https://uepeltorodecolores.jesistemas.com.ve/indexm.php'; }
	</script><?php
}else 
{ ?>
	<script type="text/javascript"> 
		if (screen.width<768) { window.location='indexm.php'; }
	</script><?php
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("inicia.php"); 
include_once("includes/funciones.php");
include_once("conexion.php");
$anioFunda=$fechahoy-date("Y-m-d",strtotime(FUNDADA));
$link = conectarse();
if(isset($_SESSION["usuario"])) 
{
	$idAlum=$_SESSION['idAlum'];
	$periodoAlum=$_SESSION['periodoAlum'];
	$grado=$_SESSION['grado'];
	$seccion=$_SESSION['seccion'];
	$morosida=$_SESSION['morosida'];
	$pagado=$_SESSION['pagado'];
	$lapsoActivo=$_SESSION['lapsoActivo'];
}else
{
	if(isset($_COOKIE['usuarioCol']) && isset($_COOKIE['passwordCol']))
  {
  	include_once("includes/reconectar.php");
  }
}
if (isset($_SESSION['usuario']) && (empty($_SESSION['fotoAlum']) || empty($_SESSION['correo']) || empty($_SESSION['ced_papa']) || empty($_SESSION['ced_mama']) ))
{
    echo "<script type='text/javascript'>                                
        window.location='consulta.php?complet';
      </script>";
}
if(isset($_SESSION["usuario"]) && isset($_GET['ingreso'])) 
{
    $pendiente='';
    $sinVer=1;
    $videos_query=mysqli_query($link,"SELECT idVideo FROM videopri".$periodoAlum." where codGrado='$grado' and codSecci='$seccion' and '$fechahoy'>=fechaPublica and lapsoVideo='$lapsoActivo' ");
    while($row=mysqli_fetch_array($videos_query))
    {
        $idVideo=$row['idVideo'];
        $videoPend_query=mysqli_query($link,"SELECT id_vio FROM vio_video where idVideo='$idVideo' and idAlum='$idAlum'");
        if(mysqli_num_rows($videoPend_query)==0)
        {
            $sinVer=2;
            break;
        }
    }
	$comenta=0;
}
$habi=2; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  	<meta charset="utf-8">
  	<meta content="width=device-width, initial-scale=1.0" name="viewport">
  	<title><?= EKKS ?></title>
  	<meta content="<?=NKXS.' '.EKKS ?> ubicado en <?= DIRECCM.' '.CIUDADM.' tlf: '.TELEMPM ?>" name="description">
  	<meta content="colegio, Inscripcion, liceo, escuela, bachillerato, primaria, <?= CIUDADM ?>, educacion, ciencias, deportes,<?=NKXS.' '.EKKS ?>,jesistemas.com,control de estudios" name="keywords">

  	<!-- Favicons -->
  	<link href="assets/img/logo.png?4" rel="icon">
  	<link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  	<!-- Google Fonts -->
  	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  	<!-- Vendor CSS Files -->
  	<link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  	<link href="assets/vendor/aos/aos.css" rel="stylesheet">
  	<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  	<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  	<link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  	<link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  	<link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
  	<!-- Template Main CSS File -->
  	<link href="assets/css/style.css?1.4" rel="stylesheet">
  	<!-- Smartsupp Live Chat script -->
  	<script type="text/javascript">
        var hoy = new Date();
        var hor = hoy.getHours()
        var dia = hoy.getDay()
        if(hor>=8 && hor<=16 && dia>=1 && dia<=5  )
        {
            var _smartsupp = _smartsupp || {};
            _smartsupp.key = '204a52fe22cf0bc6a2946217a1dfa17ad91c8629';
            window.smartsupp||(function(d) {
              var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
              s=d.getElementsByTagName('script')[0];c=d.createElement('script');
              c.type='text/javascript';c.charset='utf-8';c.async=true;
              c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
            })(document);
        }
    </script>
</head>
<body>
  	<!-- ======= Header ======= -->
  	<header id="header" class="fixed-top">
	  	<div class="container d-flex align-items-center">

	      	<h4 class="logox me-auto"><a href="index.php"><img id="logoColegio" src="assets/img/logo.png?4" style="width:10%; height: auto; padding: 2px;  "><span id="colegio" style="color: #FFF;"> U.E.P. <?= EKKS ?></span></a></h4>
	      	<nav id="navbar" class="navbar order-last order-lg-0">
	        	<ul><?php
	      			if(!isset($_SESSION["usuario"])) 
	      			{ ?> 
	      				<li><a href="preinscripcion.php">Inscribirme</a></li><?php
	      			}?>
		          	<li><a href="#why-us">Procesos</a></li><?php
	      			if(isset($_SESSION["usuario"])) 
	      			{ ?> 
		          		<li><a href="#features">Reportes</a></li><?php 
		          	}?>
		          	<li><a href="#bancos">Bancos</a></li><?php
	      			if(isset($_SESSION["usuario"])) 
	      			{ 
	      				if ($_SESSION['nombre_periodo']==$_SESSION['periodoActivo']) { ?>
	      					<li><a href="#publica">Comunicados</a></li><?php 
	      				}?> 
		          		
		          	   	<li class="dropdown"><a href="#"><span>Opciones</span> <i class="bi bi-chevron-down"></i></a>
			            	<ul>
			              		<li class="dropdown"><a href="#"><span>Procesos</span> <i class="bi bi-chevron-right"></i></a>
				                    <ul>
				                        <li><a href="consulta.php">Perfil del Estudiante</a></li>
				                        <li><a href="#" data-bs-toggle="modal" style='cursor: pointer' data-bs-target="#requisitos">Requisitos</a></li>
				                        <li><a href="registraPago.php">Registrar Pago</a></li>
				                        <li><a href="pagos.php">Historial de Pagos</a></li>
				                    </ul>
				                </li><?php 
				                if ($habi==1) {?>
				              		<li class="dropdown"><a href="#"><span>Documentos</span> <i class="bi bi-chevron-right"></i></a>
					                	<ul>
					                  		<li><a href="planilla.php" target="_blank">Planilla de Inscrip.</a></li>
					                  		<li><a href="cons-ins.php" target="_blank">Const. de Inscrip.</a></li><?php 
					                  		if($morosida==0 && $pagado>0)
					                  		{ ?>
					                  			<li><a href="cons-est.php" target="_blank">Const. de Estudio</a></li><?php 
					                  		} ?>
					                  		<li><a href="motivo.php">Const. de Asistencia</a></li><?php 
					                  		if($morosida==0 && $pagado>0)
					                  		{ ?>
					                  			<li><a href="carnet.php" target="_blank">Carnet</a></li><?php 
					                  		}?>
					                	</ul>
				              		</li><?php 
			                  		if($morosida==0 && $pagado>0 )
			                  		{ ?>
					              		<li class="dropdown"><a href="#"><span>Notas</span> <i class="bi bi-chevron-right"></i></a>
					                		<ul>
						                  		<li><a href="boletas.php">Boletin de Calificaciones</a></li>
						                	</ul>
						              	</li><?php 
						            }
					        	}?>
				              	<li class="dropdown"><a href="#popular-courses"><span>Aula Virtual</span> <i class="bi bi-chevron-right"></i></a>
			                		<ul>
			                  			<li><a href="list-videos-pri.php">Video Aula</a></li>
				                  		<li><a href="list-tareas-pri.php">Materiales</a></li>
				                  		<li><a href="contactoPri.php">Mensaje al Docente</a></li>
				                	</ul>
				              	</li>
				              	<li><a href="docen-guias.php" >Atención al Representante</a></li>
				              	<li><a href="manual.php" target="_blank">Manual de Uso</a></li>
				              	<li><a href="elegir.php">Elecciones Internas</a></li>
				              	<li><a href="galeria.php">Galeria de Imagenes</a></li>
				              	<li><a href="articulos.php">Artículos Escolares</a></li>
			            	</ul>
		          		</li><?php
		          	} ?>
	          		<li><a href="contacto.php">Contacto</a></li>
	        	</ul>
	        	<i class="bi bi-list mobile-nav-toggle"></i>
	      	</nav><!-- .navbar -->
	      	<!--a href="#" class="get-started-btn">Ingresar</a--><?php
	      	if(!isset($_SESSION["usuario"])) 
	      	{ ?> 
	        	<button type="button" class="get-started-btn" data-bs-toggle="modal" data-bs-target="#login1" >Ingresar</button><?php
		    }else
		    { ?>
		    	<a href="cierra.php"><button type="button" id="btn_login" class="get-started-btn" >Salir</button></a><?php
		    } ?>
		</div>
  	</header><!-- End Header -->
  	<!-- ======= Hero Section ======= -->
  	<section id="hero" class="d-flex justify-content-center align-items-center">
    	<div class="container position-relative" data-aos="zoom-in" data-aos-delay="100">
      		<h1>U.E.P.<br><?= EKKS ?></h1>
      		<h2>Bienvenidos al nuevo periodo escolar <?= PROXANOE ?></h2><?php
  			if(!isset($_SESSION["usuario"])) 
  			{ ?> 
      			<a href="preinscripcion.php" class="btn-get-started">Inscribirme</a><?php 
      		}?>
    	</div>
  	</section><!-- End Hero -->
  	
  	<main id="main">

	    <!-- ======= Quienes somos Section ======= -->
	    <section id="about" class="about">
	      <div class="container" data-aos="fade-up">

	        <div class="row">
	        	<div class="col-lg-12 order-1 order-lg-2" data-aos="fade-left" data-aos-delay="100">
	        		<img style="float: right; width: 60%; padding: 10px;" class="img-fluid" src="assets/img/portada2.jpg" /> 
	        		<p class="fst-italic" style="text-align: justify; ">
	        			<strong style="font-size:18px;">La <?= NKXS.' '.EKKS ?> </strong>  es una Institución Escolar Inscrita en el Ministerio de educación bajo el código PD20750504, que imparte estudios de Inicial y Primaria según los programas oficiales del currículo para estos niveles, está inserta en la sociedad venezolana y sigue los principios y regulaciones que emanan del Ministerio del Poder Popular para la Educación de la que forman parte según Art. 55 y 56 de la Ley Orgánica de Educación. Esta institución en sus comienzos era Centro Educativo de Inicial Privado (C.E.I.P) “El Toro de Colores” la cual fue creada con carácter jurídico el 18-04-2011, se inició con los 3 niveles de Educación Inicial, en un local alquilado en la calle Independencia local A-01, en el sector Patrocinio Peñuela Ruiz, al correr de los años se trasladó a su sede propia en la Calle Lamas local N 18-02 Sector San Rafael, donde funciona actualmente. Los grados fueron incrementándose a medida que pasaban los años, lo que generó un cambio en su uso ante el Ministerio del Poder Popular para la Educación pasando a Unidad Educativa Privada (U.E.P) “El Toro de Colores” en el año 2019, actualmente cuenta con los tres niveles de Educación Inicial, dos secciones de primer grado, dos secciones de segundo grado y una sección de tercero a sexto grado de Educación Primaria. Cuenta con una matrícula de 176 estudiantes. La directora del plantel durante sus primeros años fue la Profesora Yancari Oliveros. Actualmente ejerce el cargo de Gerente de la institución por formar parte de la asociación mercantil junto al Abogado Richard Sanoja, actualmente el cargo de directora del plantel lo ejerce la MSc Lucy Hernández éste año escolar 2023 – 2024. La misión de la institución es crear un espacio de convivencia, que garantice el Derecho Social de la Educación de los niños, niñas y adolescentes para la formación de un individuo con los valores como la Igualdad, Justicia Social, Responsabilidad Moral y Ética, por lo que son los elementos fundamentales que garantizan el buen desenvolvimiento y seguridad para la vida del educando.
	        		</p>
	        		<p class="fst-italic" style="text-align: justify; ">
	        			En la Visión se plantea Elevar el Nivel de educación, a la formación integral del estudiante mediante el desarrollo de sus destrezas y de sus capacidades científicas, técnicas, humanísticas y artísticas cumpliendo funciones de exploración, orientación educativa y emocional para iniciarlos en el aprendizaje de disciplina y técnicas que le permitan el ejercicio de una función socialmente útil y se atenderá el proceso formativo de los y las estudiantes iniciadas desde el nivel de educación inicial hasta educación primaria; ampliando el desarrollo integral y su formación cultural para ofrecer oportunidades que definan su campo de estudio y de trabajo, es por ello que la Unidad Educativa Privada El Toro de colores ofrece un pensum integral que aborda lo académico acorde a el currículo vigente del Ministerio del Poder Popular para la Educación además ofrece áreas complementarias de inglés, portugués y arte, también aporta al crecimiento y estabilidad emocional de los educandos a través de los servicios de Psicología y Psicopedagogía como equipo multidisciplinario.
	        		</p>
	        		<p class="fst-italic" style="text-align: justify; ">
						La U. E. P el Toro de Colores se proyecta al futuro con una educación de calidad para las exigencias de la sociedad.
					</p>
	        	</div>
	        </div>
	      </div>
	    </section><!-- End About Section -->
	    <!-- ======= Contadores Section ======= -->
	    <section id="counts" class="counts section-bg">
	      <div class="container">
	        <div class="row counters">
	          <div class="col text-center">
	            <span data-purecounter-start="0" data-purecounter-end="<?= $anioFunda ?>" data-purecounter-duration="1" class="purecounter"></span>
	            <p>Años</p>
	          </div>

	          <div class="col text-center">
	            <span data-purecounter-start="0" data-purecounter-end="<?= $anioFunda ?>" data-purecounter-duration="1" class="purecounter"></span>
	            <p>Promociones</p>
	          </div>

	          <div class="col text-center">
	            <span data-purecounter-start="0" data-purecounter-end="<?= ($anioFunda)*27 ?>" data-purecounter-duration="1" class="purecounter"></span>
	            <p>Egresados</p>
	          </div>

	          <!--div class="col-lg-3 col-6 text-center">
	            <span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="1" class="purecounter"></span>
	            <p>Trainers</p>
	          </div-->

	        </div>

	      </div>
	    </section><!-- End Counts Section -->
	    <!-- ======= Trainers Section ======= -->
    
    </section--><!-- End Trainers Section -->
	    <!-- ======= Why Us Section ======= -->
	    <section id="why-us" class="why-us">
	      <div class="container" data-aos="fade-up">
	      	<div class="section-title">
	          <h2>Procesos</h2>
	        </div>
	        <div class="row"><?php
	  			if(!isset($_SESSION["usuario"])) 
	  			{ ?> 
					<div class="col-lg-4 d-flex align-items-stretch">
						<div class="content">
						  <h3>Inscribirme para el <?= PROXANOE ?></h3>
						  <p style="text-align: justify;">
						    El proceso de preinscripción le permite ingresar de manera online todos los datos requeridos por nuestra institución para formalizar su inscripción
						  </p>
						  <div class="text-center">
						    <a href="preinscripcion.php" class="more-btn">Inscribirme <i class="bx bx-chevron-right"></i></a>
						  </div>
						</div>
					</div><?php
				}
				if(isset($_SESSION["usuario"])){ $grande='12'; $peque='3';}else {$grande='8'; $peque='4';} ?>
		        <div class="col-lg-<?= $grande ?> d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
		            <div class="icon-boxes d-flex flex-column justify-content-center">
		              <div class="row"><?php
		  				if(isset($_SESSION["usuario"])) 
		  				{ ?>
			              	<div class="col-xl-3 col-md-3 d-flex align-items-stretch">
			                  <div class="icon-box mt-4 mt-xl-0">
			                  	<i class="bx bx-receipt"></i>
			                    <h4>Perfil</h4>
			                    <p>Datos personales del Estudiante</p>
			                    <a href="consulta.php"><button class="btn btn-primary" >Click aquí<br><i class="fas fa-hand-point-up"></i></button></a>
			                  </div>
			                </div><?php 
			            }?>
		                <div class="col-xl-<?= $peque ?> col-md-<?= $peque ?> d-flex align-items-stretch">
		                  <div class="icon-box mt-4 mt-xl-0">
		                  	<i class="bx bx-receipt"></i>
		                    <h4>Requisitos</h4>
		                    <p>Requisitos para la Inscripción</p>
		                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requisitos">Click aquí<br><i class="fas fa-hand-point-up"></i></button>
		                  </div>
		                </div>
		                <div class="col-xl-<?= $peque ?> col-md-<?= $peque ?> d-flex align-items-stretch">
		                  <div class="icon-box mt-4 mt-xl-0">
		                    <i class="bx bx-dollar-circle"></i>
		                    <h4>Pagos</h4>
		                    <p>Notifique aquí sus pagos electrónicos</p><?php 
												if(!isset($_SESSION["usuario"])) 
												{ ?>
													<a data-bs-toggle="modal" data-bs-target="#login1"><button class="btn btn-primary">Click aquí<br><i class="fas fa-hand-point-up"></i></button></a> <?php 
												} else 
												{ ?>
		                    	<a href="registraPago.php"><button class="btn btn-primary">Click aquí<br><i class="fas fa-hand-point-up"></i></button></a><?php
		                    } ?>
		                  </div>
		                </div>
		                <div class="col-xl-<?= $peque ?> col-md-<?= $peque ?> d-flex align-items-stretch">
		                  <div class="icon-box mt-4 mt-xl-0">
		                    <i class="bx bx-images"></i>
		                    <h4>Historial</h4>
		                    <p>Visualice los pagos realizados</p><?php 
							if(!isset($_SESSION["usuario"])) 
							{ ?>
								<a data-bs-toggle="modal" data-bs-target="#login1"><button class="btn btn-primary">Click aquí<br><i class="fas fa-hand-point-up"></i></button></a> <?php 
							} else 
							{ ?>
		                    	<a href="pagos.php"><button class="btn btn-primary">Click aquí<br><i class="fas fa-hand-point-up"></i></button></a><?php
		                    } ?>
		                  </div>
		                </div>
		              </div>
		            </div><!-- End .content-->
		        </div>
	        </div>

	      </div>
	    </section><!-- End Why Us Section -->

	    <!-- ======= Reportes Section ======= --><?php 
	    if(isset($_SESSION['usuario']))
	    {?>
		    <section id="features" class="features" >
		      <div class="container" data-aos="fade-up">
		      	<div class="section-title" style="margin-top:2%;">
		          <h2>Reportes</h2>
		          <p>Documentación</p>
		        </div>
		        <div class="row" data-aos="zoom-in" data-aos-delay="100">
		        	
		        	<div class="col-lg-4 col-md-4">
		          		<a href="utiles-lista.php">
		            	<div class="icon-box">
		              		<i class="ri-book-mark-line" style="color: #b2904f;"></i>
		              		<h3>Lista de Utiles</h3>
		            	</div>
		            	</a>
		          	</div>
		          	<div class="col-lg-4 col-md-4">
			          	<a href="encuesta-lista.php">
				            <div class="icon-box">
				              <i class="ri-price-tag-2-line" style="color: #4233ff;"></i>
				              <h3>Encuesta <?= PROXANOE ?></h3>
				            </div>
				        </a>
		          	</div>
					<div class="col-lg-4 col-md-4">
			            <a href="planilla.php" target="_blank">
				            <div class="icon-box">
				              <i class="ri-file-text-line" style="color: #ffbb2c;"></i>
				              <h3>Planilla de Inscripción</h3>
				            </div>
			            </a>
			        </div>
			        <div class="col-lg-4 col-md-4 mt-4">
			            <a href="cons-ins.php" target="_blank">
				            <div class="icon-box">
				              <i class="ri-newspaper-line" style="color: #5578ff;"></i>
				              <h3>Constancia de Inscripción</h3>
				            </div>
			            </a>
			        </div>
		          	<div class="col-lg-4 col-md-4 mt-4"><?php 
			          	if($morosida>0 || $pagado==0)
		              	{ ?> <a onclick="msjMoro()" style='cursor: pointer'><?php }else{?> <a href="cons-est.php" target="_blank"> <?php
		          		}?>
			            	<div class="icon-box">
			              		<i class="ri-file-mark-line" style="color: #e80368;"></i>
			              		<h3>Constancia de Estudio</h3>
			            	</div>
			            </a>
		          	</div>
		          	<div class="col-lg-4 col-md-4 mt-4">
			          	<a href="motivo.php">
				            <div class="icon-box">
				              <i class="ri-file-user-line" style="color: #e361ff;"></i>
				              <h3>Constancia de Asistencia</h3>
				            </div>
				        </a>
		          	</div>
		          	<div class="col-lg-4 col-md-4 mt-4"><?php
			          	if($morosida>0 || $pagado==0)
		              	{ ?> <a onclick="msjMoro()" style='cursor: pointer'><?php }else{?> <a href="carnet.php" target="_blank"> <?php
		          		}?>
				            <div class="icon-box">
				              <i class="ri-shield-user-line" style="color: #47aeff;"></i>
				              <h3>Carnet de Estudio</h3>
				            </div>
			        	</a>
		          	</div>
					<div class="col-lg-4 col-md-4 mt-4">
			          	<a href="calendario.php">
				            <div class="icon-box">
				              <i class="ri-calendar-check-fill" style="color: #4233ff;"></i>
				              <h3>Calendario de Actividades</h3>
				            </div>
				        </a>
		          	</div>
		          	<div class="col-lg-4 col-md-4 mt-4"><?php
			          	if($morosida>0 || $pagado==0)
		              	{ ?> <a onclick="msjMoro()" style='cursor: pointer'><?php }else{?> <a href="boletas.php" > <?php
		          		}?>
				            <div class="icon-box">
				              <i class="ri-file-list-3-line" style="color: #11dbcf;"></i>
				              <h3>Boletin de Calificaciones</h3>
				            </div>
			        	</a>
		          	</div>
		        </div>
		      </div>
		    </section><!-- End Features Section --><?php 
		} ?>
	    <!-- ======= Aula Virtual Section ======= -->
	    <section id="popular-courses" class="courses">
	      <div class="container" data-aos="fade-up">

	        <div class="section-title">
	          <h2>Online</h2>
	          <p>Aula virtual</p>
	        </div>

	        <div class="row" data-aos="zoom-in" data-aos-delay="100">
	          	<!-- VIDEOS -->
	          	<div class="col-lg-4 col-md-4 d-flex align-items-stretch">
	            	<div class="course-item"><?php 
		            	if(isset($_SESSION['usuario']))
		            	{?>
		              		<a href="list-videos-pri.php"><img src="assets/img/course-1.webp" class="img-fluid" alt="..."></a>
		              		<div class="course-content">
		                		<h3><a href="list-videos-pri.php">Video Aula</a></h3>
		                		<p>Visualice los videos asignados por sus docentes</p>
		              		</div><?php 
		              	}else 
		              	{ ?>
		              		<a data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;"><img src="assets/img/course-1.webp" class="img-fluid" alt="..."></a>
		              		<div class="course-content">
		                		<h3>Video Aula</h3>
		                		<p>Visualice los videos asignados por sus docentes</p>
		              		</div><?php 
		              	}?>
	            	</div>
	          	</div> <!-- End Course Item-->

	          	<!-- TAREAS -->
	          	<div class="col-lg-4 col-md-4 d-flex align-items-stretch mt-4 mt-md-0">
		            <div class="course-item"><?php 
		            	if(isset($_SESSION['usuario']))
		            	{?>
		              		<a href="list-tareas-pri.php">
		              		<img src="assets/img/events-2.webp" class="img-fluid" alt="..."></a>
		              		<div class="course-content">
		                		<h3><a href="list-tareas-pri.php">Material de Clases</a></h3>
		                		<p>Descargue los diferentes materiales de clases.</p>
		              		</div><?php
		              	}else
		              	{?>
		              		<a data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;"><img src="assets/img/events-2.webp" class="img-fluid" alt="..."></a>
		              		<div class="course-content">
		                		<h3>Tareas</h3>
		                		<p>Visualice y envíe al docentes sus tareas.</p>
		              		</div><?php
		              	}?>
		            </div>
	          	</div>
	          	<!-- MENSAJES -->
	          	<div class="col-lg-4 col-md-4 d-flex align-items-stretch mt-4 mt-md-0">
		            <div class="course-item"><?php 
		            	if(isset($_SESSION['usuario']))
		            	{?>
		              		<a href="contactoPri.php">
		              		<img src="assets/img/course-3.webp" class="img-fluid" alt="..."></a>
		              		<div class="course-content">
		                		<h3><a href="contactoPri.php">Mensajes</a></h3>
		                		<p>Visualice y envíe mensajes al docentes.</p>
		              		</div><?php
		              	}else
		              	{?>
		              		<a data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;"><img src="assets/img/course-3.webp" class="img-fluid" alt="..."></a>
		              		<div class="course-content">
		                		<h3>Mensajes</h3>
		                		<p>Visualice y envíe mensajes al docentes.</p>
		              		</div><?php
		              	}?>
		            </div>
	          	</div> <!-- End Course Item-->
	        </div>
	      </div>
	    </section><!-- End Popular Courses Section -->

	    <!-- ======= Bancos Section ======= -->
	    <section id="bancos" class="pricing" style="background-color:#EEEEEE;">
	      	<div class="container" data-aos="fade-up">
	      		<div class="section-title">
		          	<h2>Pagos</h2>
		          	<p>Cuentas Bancarias</p>
		        </div>
		        <div class="row">
		        	<div class="col-md-12 col-12 text-center"><?php 
		            	if(isset($_SESSION['usuario']))
		            	{?>
		          			<h5>Recuerde notificar cualquier pago electrónico realizado a una de nuestras cuentas en la sección de:<br><a href="registraPago.php">(PROCESOS / PAGOS)</a></h5><?php 
		          		}else{?>
		          			<h5>Recuerde notificar cualquier pago electrónico realizado a una de nuestras cuentas en la sección de:<br><a href="" data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;">(PROCESOS / PAGOS)</a></h5><?php 
		          		}?>
		          	</div>
		          	<div class="col-md-4 mt-4 mt-md-0">
		            	<div class="box">
		            		<span class="advanced">Transferenc.</span>
		            		<h3>Banco Provincial</h3>
		              		<ul style="line-height: 8px;">
		                		<li>Cuenta Corriente</li>
		                		<li>0108-0138-94-0100046831</li>
		                		<li>Rif: J-40076133-6</li>
		                		<li>C E I P EL TORO DE COLORES</li>
		              		</ul>
		              		<div class="btn-wrap">
		                		<img src="assets/img/bancos/provincial.png" style="width: 80%; height: 60px;">
		              		</div>
		            	</div>
		          	</div>
		          	<div class="col-md-4 mt-4 mt-md-0">
		            	<div class="box">
		            		<span class="advanced">Transferenc.</span>
		              		<h3>Banco Bancaribe</h3>
		              		<ul style="line-height: 8px;">
				                <li>Cuenta Corriente</li>
				                <li>0114-0205-43-2050055372</li>
		                		<li>Rif: J-40076133-6</li>
		                		<li>C E I P EL TORO DE COLORES</li>
		              		</ul>
		              		<div class="btn-wrap">
		                		<img src="assets/img/bancos/bancaribe.png" style="width: 60%; height: 60px;">
		              		</div>
		            	</div>
		          	</div>
		          	<div class="col-md-4 mt-4 mt-md-0">
		            	<div class="box">
		            		<span class="advanced">Pago Movil</span>
		              		<h3>Pago Movil</h3>
		              		<ul style="line-height: 8px;">
				                <li>Celular: 0424-309.30.41</li>
		                		<li>Rif: J-40076133-6</li>
		                		<li>Provincial (0108) </li>
		                		<li>&nbsp;</li>
		              		</ul>
		              		<div class="btn-wrap">
		                		<img src="assets/img/bancos/pagomovil.jpg" style="width: 60%; height: 60px;">
		              		</div>
		            	</div>
		          	</div>
		        </div>
	      	</div>
	    </section><!-- End Pricing Section -->

	    <?php
		if(isset($_SESSION['usuario']) && isset($_SESSION['password']) && $_SESSION['nombre_periodo']==$_SESSION['periodoActivo'] )
		{ ?>
			<section id="publica" class="about">
				<div class="container" data-aos="fade-up">
					<div class="section-title" style="margin-top:1px;">
			          <h2>Información</h2>
			          <p>Comunicados</p>
			          <a href="publicacion.php" ><button type="button" class="get-started-btn"  ><span class="fa fa-eye" aria-hidden="true" ></span> Ver Todas</button></a>
			        </div>
					<style type="text/css">
						.error
					    { 
					        background-color:#3D6EC9;
					        padding:5px;
					        border:#05348B 2px solid;
					        float: center;
					        margin-top: 3%;
					        font-size: 18px;
					        color: white; 
					    }
					    .embed-container {
						    position: relative;
						    height: 0;
						    overflow: hidden;
						    padding-bottom: 75%;
						    border:#05348B 2px solid;
						}
						
						.embed-container iframe {
						    position: absolute;
						    top:0;
						    left: 0;
						    width: 100%;
						    height: 100%;
						}
					</style><?php
					$query = mysqli_query($link,"SELECT * FROM tbl_documentos WHERE adultos is NULL and activo=1 and todos='S' or (activo=1 and ('$grado'>=gradoDesde and '$grado' <= gradoHasta) and (('$seccion'>=seccionDesde and '$seccion'<=seccionHasta) or seccionDesde is NULL )) ORDER BY fecha_doc DESC LIMIT 4");
					$son=0;
					if(mysqli_num_rows($query) > 0 )
				    {
						while($row=mysqli_fetch_array($query) ) 
					    {
					        $titulo=($row['titulo']);
					        $descripcion=($row['descripcion']);
					        $nom_doc=$row['nombre_archivo']; ?>
							<div align="center" class="error"><?= $titulo; ?><br><?= $descripcion?>
							</div>
							<div align="center" class="embed-container">
								<iframe src="archivos/<?= $nom_doc; ?>" frameborder="0" allowfullscreen width="560" height="315"  ></iframe>
				            </div><?php
							/*$son=$son+1;
					        if($son==2){ break; }*/
					    }
				    }?>
				</div>
			</section><?php 
		} ?>
	    <!-- ======= Verificar Section ======= -->
  	</main><!-- End #main -->
  	<style type="text/css">
      .service-desc
      {
        text-align: justify;
      }
      .btn-float{
        display:scroll;
        position:fixed;
        /*height: 45px;*/
        right: 125px;
        bottom: 12px;
        z-index: 1000;
        border-radius: 100px !important;
        background-color: #00B74A;
        align-items: center;
        display:flex;
    	justify-content: center;
      }
      
    </style><?php 
	if(isset($_SESSION['usuario']))
	{ ?>
	  	<button class="btn btn-success btn-float " onclick='location.href="contactoPri.php"' ><i class="fas fa-comments fa-2x"></i>&nbsp;&nbsp;Mensaje al Docente</button><?php 
	}?>
  	<!-- ======= Footer ======= -->
  	<footer id="footer">
	    <div class="footer-top">
	      <div class="container">
	        <div class="row">

	          <div class="col-lg-3 col-md-6 footer-contact">
	            <h3>Dirección</h3>
	            <p><?= DIRECCM .' '. CIUDADM.' - '.ESTADOM ?> <br>
	              <strong>Telefono:</strong> <?= TELEMPM ?><br>
	              <strong>Email:</strong> <?= SUCORREO ?><br>
	            </p>
	          </div>

	          <div class="col-lg-3 col-md-6 footer-links">
	            <h4>Links más usados</h4>
	            <ul>
	              <li><i class="bx bx-chevron-right"></i> <a href="#">Inicio</a></li><?php
	              if(isset($_SESSION['usuario']))
	              { ?>
	              	<li><i class="bx bx-chevron-right"></i> <a href="cierra.php">Salir</a></li><?php 
	              }else
	              {?>
	              	<li><i class="bx bx-chevron-right"></i> <a data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;">Ingresar</a></li><?php 
	              }?>
	              <li><i class="bx bx-chevron-right"></i> <a href="#why-us">Procesos</a></li><?php
	              if(isset($_SESSION['usuario']))
	              {?>
	              	<li><i class="bx bx-chevron-right"></i> <a href="#features">Reportes</a></li><?php
	              }else
	              {
	              	?>
	              	<li><i class="bx bx-chevron-right"></i><a data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;"> Reportes</a></li><?php
	              } ?>
	              <li><i class="bx bx-chevron-right"></i> <a href="#bancos">Bancos</a></li>
	            </ul>
	          </div>

	          <div class="col-lg-3 col-md-6 footer-links">
	            <h4>Otros Links</h4>
	            <ul>
	            	<li><i class="bx bx-chevron-right"></i> <a href="contacto.php">Contacto</a></li>
	              	<li><i class="bx bx-chevron-right"></i> <a href="preinscripcion.php">Inscribirme</a></li><?php 
		            if(isset($_SESSION['usuario']))
		            {?>
	              		<li><i class="bx bx-chevron-right"></i> <a href="list-tareas-pri.php">Materiales</a></li>
	              		<li><i class="bx bx-chevron-right"></i> <a href="list-videos-pri.php">Video Clases</a></li>
	              		<li><i class="bx bx-chevron-right"></i> <a href="planilla.php" target="_blank">Planilla de Inscripción</a></li><?php 
	              	}else
	              	{?>
	              		<li><i class="bx bx-chevron-right"></i><a data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;"> Materiales</a></li>
	              		<li><i class="bx bx-chevron-right"></i><a data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;"> Video Clases</a></li>
	              		<li><i class="bx bx-chevron-right"></i><a data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;"> Planilla de Inscripción</a></li><?php
	              	}?>
	            </ul>
	          </div>
	          <div class="col-lg-3 col-md-6 footer-links">
	            <h4>Otros Links</h4>
	            <ul>
	            	<li><i class="bx bx-chevron-right"></i> <a href="manual.php" target="_blank">Manual Usuario</a></li><?php
		            if(isset($_SESSION['usuario']))
		            { 
		            	if ($morosida==0 && $pagado>0) 
		            	{?>
		            		<li><i class="bx bx-chevron-right"></i> <a href="cons-est.php" target="_blank">Constancia Estudio</a></li>
	              			<li><i class="bx bx-chevron-right"></i> <a href="carnet.php" target="_blank">Carnet</a></li><?php
		            	}
	              }else
	              {?>
	              		<li><i class="bx bx-chevron-right"></i><a data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;"> Constancia Estudio</a></li>
	              		<li><i class="bx bx-chevron-right"></i><a data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;"> Carnet</a></li><?php 

	              }?>
	              	<li><i class="bx bx-chevron-right"></i> <a data-bs-toggle="modal" style='cursor: pointer' data-bs-target="#requisitos">Requisitos</a></li>
	              	<li><i class="bx bx-chevron-right"></i> <a href="contacto.php">Como llegar</a></li>
	              	
	            </ul>
	          </div>
	        </div>
	      </div>
	    </div>
	    <div class="container d-md-flex py-4">
	      <div class="me-md-auto text-center text-md-start">
	        <div class="copyright">
	          &copy; <strong><span><?= DOMINIO ?></span></strong>. 
	        </div>
	        <div class="credits">
	          	Sistema desarrollado por <a target="_BLANK" href="https://jesistemas.com">jesistemas.com</a>
	        </div>
	      </div>
	      <div class="social-links text-center text-md-right pt-3 pt-md-0"><?php 
	      	if(FACEBOOK!=""){?>
	      		<a href="<?= FACEBOOK ?>" class="facebook"><i class="bx bxl-facebook"></i></a><?php
	      	}
	        if(INSTAGRAM!=""){?>
	        	<a href="<?= INSTAGRAM ?>" target="_blank" class="instagram"><i class="bx bxl-instagram"></i></a><?php
	        }?>
	      </div>
	    </div>
  	</footer><!-- End Footer -->

  	<div id="preloader"></div>
  	<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  	<!-- MODAL -->
	<div class="modal fade" id="login1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-sm">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Iniciar Sesión</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <form method="post" action="login.php" >
	        	<center><img src="assets/img/logo.png?6" style="width:60%;"></center>
	          <div class="mb-3">
	            <label for="recipient-name" class="col-form-label"><i class="ri-user-follow-line"></i> Cedula ó Usuario del Estudiante:</label>
	            <input type="text" required class="form-control" title="Ingrese la cedula del estudiante solo numeros, sin puntos, ni letras, ni espacios en blanco" id="usuario" name="usuario">
	          </div>
	          <div class="mb-3">
	            <label for="message-text" class="col-form-label"><i class="ri-lock-unlock-fill"></i> Contraseña:</label>
	            <input type="password" required class="form-control" id="passwordalum" name="passwordalum">
	            <input type="hidden" name="dispo" value="desktop">
	          </div>
	          <div class="col-md-12">
	          	<button type="submit" style="width:100%;" class="btn btn-primary"><i class="ri-key-2-fill"></i> Ingresar</button>
	          </div>
	        </form>
	      </div>
	      <div class="modal-footer">
	        <div class="col-md-12" title="Para recuperar sus datos de acceso, debe colocar el nro. de cedula o usuario del estudiante en el formulario">
	        	<button type="button" onclick="olvidoClave()" style="width:100%;" class="btn btn-info"><i class="ri-lock-unlock-line"></i> Olvide Contraseña</button>
	        </div>
	        <div class="col-md-12">
	        	<a href="preinscripcion.php"><button type="button" style="width:100%;" class="btn btn-success"><i class="ri-check-line"></i> Inscribirme!</button></a>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
	<div class="modal fade" id="noingreso" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  	<div class="modal-dialog modal-dialog-scrollable">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<h5 class="modal-title" id="exampleModalLabel">Sesión Iniciada!</h5>
	        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      		</div>
	      		<div class="modal-body">
	        		<div class="col text-center">
			            <h3>Hola!</h3>
		              	<h4 id="nomAlumno" ></h4>
		              	<h2>Bienvenido(a)</h2>
		              	<img src="assets/img/logo.png?6" style="width:30%;">
		              	<h2 id="revisaTarea" style="background-color: #9FA8DA; color:#FFF;"></h2>
		              	<h2 id="revisaVideo" style="background-color: #AED581; color:#FFF;"></h2>
            			<h2 id="revisaComenta" style="background-color: #AB47BC; color:#FFF;"></h2>
			        </div>
	      		</div>
	      		<div class="modal-footer"><?php 
	      			if(isset($_GET['alNew']) && $_GET['alNew']=='1')
	      			{?>
	      				<a href="planilla.php" target="_blank" ><button type="button" class="btn btn-warning">Planilla de Inscripción</button></a><?php 
	      			}?>
	      			<a href="publicacion.php"><button type="button" class="btn btn-primary">Comunicados</button></a>
	        		<a href="list-tareas-pri.php" id="botonAula" style="display: none;"><button type="button" class="btn btn-info">Ver Materiales</button></a>
	        		<a href="list-videos-pri.php" id="botonVideo" style="display: none;"><button type="button" class="btn btn-success">Ver mis videos</button></a><?php 
	        		if($_SESSION['msjHay']>0){?>
	        			<a href="contactoPri.php" ><button type="button" class="btn btn-success">Ver mensajes</button></a><?php 
	        		}?>
	      		</div>
	    	</div>
	  	</div>
	</div>
	<div class="modal fade" id="verDatos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	    <div class="modal-dialog modal-lg" >
	        <div class="modal-content">
	            <div class="modal-header">
	                <h4 class="modal-title" id="exampleModalLongTitle">DATOS DEL ESTUDIANTE</h4>
	                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	            </div>
	            <form action="verificar.php" method="POST">
		            <div class="modal-body">
		            	<div class="row">
			            	<div class="col-md-4">
			            		<label>Periodo Escolar</label>
			                	<input type="text" readonly="" name="periCur" id="periCur" class="form-control form-control2" >	
			            	</div>
			            	<div class="col-md-5">
				                <label>Año/Grado</label>
				                <input type="text" readonly="" name="gradoAlum" id="gradoAlum" class="form-control form-control2">
				            </div>
				            <div class="col-md-3">
				                <label>Seccion</label>
				                <input type="text" readonly="" name="seccionAlum" id="seccionAlum" class="form-control form-control2">
				            </div>
			            	<div class="col-md-6">
			                	<label>Nombre</label>
			                	<input type="text" readonly="" name="nombreAlum" id="nombreAlum" class="form-control form-control2">
			                </div>
			                <div class="col-md-6">
				                <label>Apellido</label>
				                <input type="text" readonly="" name="apellidoAlum" id="apellidoAlum" class="form-control form-control2">
				            </div>
		            	</div>
		            </div>
		            <div class="modal-footer">
		                <button type="submit" class="btn btn-rectangular" style="background-color: #336D3A; color: white; text-align: center;">Aceptar</button>
		            </div>
		            <input type="hidden" name="cedulaAlum" id="cedulaAlum">
		            <input type="hidden" name="idAlum" id="idAlum">
		            <input type="hidden" id="gradoCursa" value="<?= $grado ?>">
		            <input type="hidden" id="morosida" value="<?= $morosida ?>">
		            <input type="hidden" name="tabla" id="tabla">
	            </form>
	        </div>
	    </div>
	</div>
	<div class="modal fade" id="requisitos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-scrollable modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="staticBackdropLabel">REQUISITOS NUEVOS INGRESOS<br> Año Escolar <?= PROXANOE ?></h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body" style='background-image: url("img/fondoagua.png"); background-repeat: no-repeat, repeat; background-size: cover; width: 100%; height: 100vh;'>
	        <center><h3>Inicial(I a III  nivel) y Primaria (1ero. a 6to. grado)</h3></center>
			<p >
				02 Fotos tipo carnet actualizadas.<br>
				01 Partida de nacimiento (original legible y fotocopia).<br>
				01 Constancia de Niño Sano.<br>
				01 Copia de la Tarjeta de Vacunas.<br>
				01 Sobre de Manila Tamaño Oficio.<br>
				En caso de venir de otra Institución.<br>
				01 Record SIGES.<br>
				01 Certificado de aprobación<br>
				01 Constancia de buena conducta<br>
				01 Solvencia administrativa<br>
			</p>
            <center><h3>Del Representante (por cada representado)</h3></center>
            <p >
            	01 Fotos tipo carnet actualizadas.<br>
            	02 Fotocopias de la cédula.<br>
            	01 Constancia de trabajo.<br>
            </p>
            
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
	      </div>
	    </div>
	  </div>
	</div>
	<div class="modal fade" id="lap2do" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-scrollable modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="staticBackdropLabel">FUNCIONES ESPECIALES <span style="color: red; font-size: 35; font-weight: bold; ">*</span></h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <p style="text-align: justify;" >
	        	Estimado estudiante y representante, esta función estará disponible para el 2do. lapso del periodo escolar <?= PROXANOE ?> <br><br>
		        Ante cualquier duda, contactar con los teléfonos del plantel (<a href="tel:<?= TELEMPM ?>"><?= TELEMPM ?></a> ) en horas de oficina.<br><br>
		        O a través del correo <a href="contacto.php"><?= SUCORREO ?></a> 
		      </p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
	      </div>
	    </div>
	  </div>
	</div>
	<input type="hidden" id="alumnox" value="<?= $_SESSION['nomuser'] ?>">
	<input type="hidden" id="tareax" value="<?= $_SESSION['tareaPend'] ?>">
	<input type="hidden" id="videox" value="<?= $sinVer ?>">
	<input type="hidden" id="comentax" value="<?= $comenta ?>">
	<!-- Vendor JS Files -->
	<script src="assets/vendor/aos/aos.js"></script>
	<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/vendor/php-email-form/validate.js"></script>
	<script src="assets/vendor/purecounter/purecounter.js"></script>
	<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
	<script src="assets/vendor/jquery/jquery.min.js"></script>
	<script src="assets/vendor/jquery/jquery.js"></script>
	<!-- Template Main JS File -->
	<script src="assets/js/main.js"></script>
	<script type="text/javascript">
		$(document).ready(function() { 
  		if (screen.width<768) { $('#colegio').hide(); $("#logoColegio").width(80); }else{ $('#colegio').show(); }
  	});
  	function msjMoro()
		{
			Swal.fire({
	            icon: 'info',
	            title: 'Informacion Importante!',
	            confirmButtonText:
	            '<i class="fa fa-thumbs-up"></i> Entendido',
	            text: 'Sr.(a) Representante es necesaria su presencia en nuestro departamento de administración a la brevedad.'
	        })
		}
		function olvidoClave() {
			ced=$('#usuario').val()
			if (ced=='') { alert('Ingrese Cedula del estudiante')}else  
			{
				$.post('olvidoClave.php',{'cedula':ced},function(data)
				{
					if(data.isSuccessful)
					{
					    Swal.fire({
				            icon: 'success',
				            title: 'Contraseña enviada',
				            confirmButtonText:
				            '<i class="fa fa-thumbs-up"></i> Entendido',
				            text: 'Estimado estudiante hemos enviado a su correo los datos de acceso a la pagina web.'
				        })
					}else
					{
					    Swal.fire({
				            icon: 'error',
				            title: 'Cedula invalida',
				            confirmButtonText:
				            '<i class="fa fa-thumbs-up"></i> Entendido',
				            text: 'Estimado usuario la cedula suministrada no esta registrada en nuestros archivos por favor verifique'
				        })
					}
				}, 'json');
			}
		}
		function bachi() 
		{
			Swal.fire({
	            icon: 'info',
	            title: 'Informacion!',
	            confirmButtonText:
	            '<i class="fa fa-thumbs-up"></i> Entendido',
	            text: 'Sr.(a) Representante esta opción es solo valida para bachillerato.'
	        })	
		}
		function verificar()
		{
			ced=$('#cedula').val();
			$.post('buscar-alum.php',{'ced':ced},function(data)
	        {
	            if(data.isSuccessful)
	            {
	                $('#verDatos').modal('show');
	                $('#idAlum').val(data.idAl);
	                $('#nombreAlum').val(data.nombre);
	                $('#apellidoAlum').val(data.apelli);
	                $('#gradoAlum').val(data.grad);
	                $('#seccionAlum').val(data.secci);
	                $('#periCur').val(data.periCur);
	                $('#cedulaAlum').val(data.cedAl);
	                $('#tabla').val(data.tabla);

	            } else
	            {
	                swal("Error!", "Cedula no Existe!", "error");
	            }
	        }, 'json');
		}
	</script>

	<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script><?php
	if(isset($_GET['ingreso']) && $_GET['ingreso']=='fail')
	{ ?>
		<script type="text/javascript">
			Swal.fire({
	            icon: 'error',
	            title: 'Error!',
	            confirmButtonText:
	            '<i class="fa fa-thumbs-up"></i> Entendido',
	            text: 'Cedula, usuario o contraseña errada!'
	        })
		</script><?php
	}
	if(isset($_GET['depa']) )
	{ ?>
		<script type="text/javascript">
			Swal.fire({
	            icon: 'info',
	            title: 'Importante!',
	            confirmButtonText:
	            '<i class="fa fa-thumbs-up"></i> Entendido',
	            html: 'Estimado representante, es necesaria su presencia en el dpto. de control de estudios para poder retirar el documento solicitado, de su representado.'
	        })
		</script><?php
	}
	if($_SESSION['msjHay']>0)
	{ ?>
		<script type="text/javascript">
			Swal.fire({
	            icon: 'info',
	            title: 'Atención!',
	            confirmButtonText:
	            '<i class="fa fa-thumbs-up"></i> Entendido',
	            text: 'Usted tiene mensajes pendientes.'
	        })
		</script><?php
	}
	if (isset($_GET['ingreso']) && $_GET['ingreso']==2) 
	{ ?>
	    <script type='text/javascript'>
	    	nomA=$('#alumnox').val();
	    	tare=$('#tareax').val();
	    	vide=$('#videox').val();
	    	come=$('#comentax').val();
	    	gra=$('#gradoCursa').val();
	    	mor=$('#morosida').val();
	     	document.querySelector('#nomAlumno').innerText = nomA;
	     	if(tare==2)
	     	{
	     		document.getElementById('botonAula').style.display = 'block';
	     		document.querySelector('#revisaTarea').innerText = 'Tienes material de clases pendiente por ver';
	     	}
	     	if(vide==2)
	     	{
	     		document.getElementById('botonVideo').style.display = 'block';
	     		document.querySelector('#revisaVideo').innerText = 'Tienes videos por ver';
	     	}
	     	if(come==1)
	     	{
	     		document.getElementById('botonAula').style.display = 'block';
	     		document.querySelector('#revisaComenta').innerText = 'Tienes observaciones de tus Docentes por ver';
	     	}
	        $(window).load(function() { $('#noingreso').modal('show'); });
	    </script><?php
	}
	if(isset($_GET['exis']))
	{ ?>
		<script type="text/javascript">
			Swal.fire({
	            icon: 'error',
	            title: 'Error!',
	            confirmButtonText:
	            '<i class="fa fa-thumbs-up"></i> Entendido',
	            text: 'Cedula YA existe por favor haga clic en Ingresar y coloque los datos requeridos, si olvido su contraseña haga clic en Olvide Contraseña'
	        })
		</script><?php
	}
	if(isset($_GET['ingre']))
	{ ?>
		<script type="text/javascript">
			Swal.fire({
	            icon: 'success',
	            title: 'Excelente!',
	            confirmButtonText:
	            '<i class="fa fa-thumbs-up"></i> Entendido',
	            text: 'Sus datos fueron almacenados exitosamente, haga clic en ingresar y coloque los datos requeridos'
	        })
		</script><?php
	} 
	if(isset($_GET['vencio']))
	{ ?>
		<script type="text/javascript">
			Swal.fire({
	            icon: 'info',
	            title: 'Información!',
	            confirmButtonText:
	            '<i class="fa fa-thumbs-up"></i> Entendido',
	            text: 'Su sesión a expirado, por favor haga clic nuevamente en Ingresar'
	        })
		</script><?php
	}
	if(isset($_GET['moro']))
	{ ?>
		<script type="text/javascript">
			Swal.fire({
	            icon: 'info',
	            title: 'Información!',
	            confirmButtonText:
	            '<i class="fa fa-thumbs-up"></i> Entendido',
	            text: 'Sr.(a) Representante es necesaria su presencia en nuestro departamento de administración a la brevedad.'
	        })
		</script><?php
	}
	if(isset($_GET['sinpago']))
	{ ?>
		<script type="text/javascript">
			Swal.fire({
	            icon: 'info',
	            title: 'Información!',
	            confirmButtonText:
	            '<i class="fa fa-thumbs-up"></i> Entendido',
	            text: 'Sr.(a) Representante aun no presenta ningun pago en su historial, favor comunicar al dpto de administración.'
	        })
		</script><?php
	}
	mysqli_free_result($tareas_query);
	mysqli_free_result($sin_entregar_query);
	mysqli_free_result($videos_query);
	mysqli_free_result($videoPend_query);
	mysqli_free_result($comenta_query);
	mysqli_free_result($query);
	mysqli_free_result($docente_query); ?>
</body>
</html>


