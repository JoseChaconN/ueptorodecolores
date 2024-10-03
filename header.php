<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?= EKKS ?></title>
  <meta content="<?= NKXS.' '.EKKS.' '.CIUDADM.' '.ESTADOM ?>" name="description">
  <meta content="colegio, Inscripcion, liceo, escuela, bachillerato, primaria, Santa Cruz, educacion, ciencias, deportes,Colegio <?= EKKS ?>,jesistemas.com" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/logo.png?1" rel="icon">
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
  <!-- Template Main CSS File -->
  <link href="assets/css/style.css?0.5" rel="stylesheet">
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
</head><?php 
include_once("includes/funciones.php");

if(isset($_SESSION['usuario']))
{
  $morosida=$_SESSION['morosida'];
  $pagado=$_SESSION['pagado'];
  $grado=$_SESSION['grado'];
  $seccion=$_SESSION['seccion'];
  $tablaPeriodo=$_SESSION['periodoAlum'];
}else
{
  if(isset($_COOKIE['usuarioCol']) && isset($_COOKIE['passwordCol']))
  {
    include_once("includes/reconectar.php");
  }
}
$habi=2;?>
<body>
  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">
      <h4 class="logox me-auto"><a href="index.php"><img id="logoColegio" src="assets/img/logo.png?1" style="width:10%; height: auto; padding: 2px; "><span id="colegio" style="color: #FFF;">U.E.P. <?= EKKS ?></span></a></h4>
      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
          <li><a href="index.php">Inicio</a></li>
          <li><a href="index.php#why-us">Procesos</a></li>
          <li><a href="index.php#features">Reportes</a></li>
          <li><a href="index.php#bancos">Bancos</a></li><?php
          if(isset($_SESSION["usuario"])) 
          { ?> 
            <li><a href="index.php#publica">Comunicados</a></li>
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
                  <li class="dropdown"><a href="#"><span>Reportes</span> <i class="bi bi-chevron-right"></i></a>
                    <ul>
                        <li><a href="planilla.php" target="_blank">Planilla de Inscripción</a></li>
                        <li><a href="cons-ins.php" target="_blank">Constancia de Inscripción</a></li><?php 
                        if($morosida==0 && $pagado>0)
                        { ?>
                          <li><a href="cons-est.php" target="_blank">Constancia de Estudio</a></li><?php 
                          if($grado>60)
                          { ?>
                            <li><a href="cons-prom.php" target="_blank">Constancia de Promedio</a></li><?php
                          } 
                        } ?>
                        <li><a href="motivo.php">Constancia de Asistencia</a></li><?php 
                        if($morosida==0 && $pagado>0)
                        { ?>
                          <li><a href="carnet.php" target="_blank">Carnet</a></li><?php 
                        } ?>
                    </ul>
                  </li><?php 
                  if($morosida==0 && $pagado>0)
                  { ?>
                    <li class="dropdown"><a href="#"><span>Notas</span> <i class="bi bi-chevron-right"></i></a>
                      <ul><?php if($grado>60){?>
                          <li><a href="bole-liceo-corte.php"  target="_blank">Corte de Notas</a></li>
                          <li><a href="verifi/certifiAlum-pdf.php"  target="_blank">Notas Certificadas</a></li><?php 
                        }?>
                        <li><a href="boletas.php">Boletin de Calificaciones</a></li>
                          
                      </ul>
                    </li><?php 
                  }
                  } ?>
                  <li class="dropdown"><a href="#"><span>Aula Virtual</span> <i class="bi bi-chevron-right"></i></a>
                    <ul>
                        <li><a <?php if($grado<60){ echo 'href="list-videos-pri.php"';} else { echo 'href="list-videos.php"';} ?>>Video Aula</a></li>
                        <li><a <?php if($grado<60){ echo 'href="list-tareas-pri.php"';} else { echo 'href="list-tareas.php"';} ?>>Materiales</a></li>
                        <li><a <?php if($grado<60){ echo 'href="contactoPri.php"';} else { echo 'href="contactoDoc.php"';} ?>>Mensaje al Docente</a></li>
                    </ul>
                  </li>
                  <li><a href="docen-guias.php" >Atención al Representante</a></li>
                  <li><a href="manual.php" target="_blank">Manual de Uso</a></li>
                  <li><a href="elegir.php">Elecciones Internas</a></li>
                  <li><a href="galeria.php">Galería de Imagenes</a></li>
                  <li><a href="articulos.php">Artículos Escolares</a></li>
              </ul>
            </li><?php
          } ?>
         
          <li><a href="contacto.php">Contacto</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->
    </div>
  </header><!-- End Header -->
  
  <div class="modal fade" id="requisitos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">REQUISITOS INICIALES NUEVOS INGRESOS<br> Año Escolar <?= PROXANOE ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <center><h3>Primaria (Estudiantes 1ero. a 6to. grado)</h3></center>
          <p >
            01 Partida de nacimiento (original legible y fotocopia).<br>
            02 fotocopias de la cédula de identidad.<br>
            02 Fotos actualizadas.<br>
            01 Boleta de promoción.<br>
            01 Carpeta amarilla tamaño oficio.<br>
            01 Informe médico (En caso de tratamiento especial) <br>
                  01 Planilla de inscripción emitida por la página web <br>
                </p>
                <center><h3>E.M.G. (Estudiantes 1ero. a 5to. año)</h3></center>
          <p >
            01 Partida de nacimiento (original legible y fotocopia).<br>
            02 fotocopias de la cédula de identidad.<br>
            02 Fotos actualizadas.<br>
            01 Boleta de promoción (para estudiantes de 1er. año) / NOTAS CERTIFICADAS (para estudiantes de 2do. a 4to. año).<br>
            01 Carpeta amarilla tamaño oficio.<br>
            01 Informe médico (En caso de tratamiento especial) <br>
            01 Planilla de inscripción emitida por la página web <br>
          </p>
          <center><h3>Del Representante (por cada representado)</h3></center>
          <p >
            02 Fotocopias de la cédula.<br>
            02 Fotos actualizadas.<br>
            01 Constancia de trabajo o de ingresos del representante legal.<br>
            01 Cancelar el costo de inscripción y mensualidad por taquilla.<br>
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>