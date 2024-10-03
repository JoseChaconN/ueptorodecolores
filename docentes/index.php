<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<2 ) 
{
  header("location:../index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("../inicia.php");
include_once("../conexion.php");
$link = conectarse();
$idDocente=$_SESSION['idAlum']; ?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php";
  
  $lapso_query=mysqli_query($link,"SELECT lapso FROM preinscripcion where id='2'"); 
  while($row=mysqli_fetch_array($lapso_query))
  {
    $lapsoActivo=$row['lapso'];
    $_SESSION['lapsoActivo'] = $row['lapso'];
  } ?>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Menu Principal del Docente <?= $_SESSION['periodoActivo'].'<br>Lapso Activo: '.$lapsoActivo.'°' ?></h2>
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#verArchivo">Ver instructivo</button>
        <button type="button" class="btn btn-primary" onclick='window.open("contactoAlum.php")'>Ver mensajes</button>
      </div>
    </div><!-- End Breadcrumbs -->

    <section id="features" class="features" >
      <div class="container" data-aos="fade-up">
        <div class="row" data-aos="zoom-in" data-aos-delay="100">
          <div class="col-md-12 from-group text-center titulo" style="margin-top: 2%;">
            <p>Opciones de Primaria</p>
          </div>
          <div class="col-lg-3 col-md-4 mt-3">
            <a href="" data-bs-toggle="modal" style='cursor: pointer' data-bs-target="#gradosTar">
              <div class="icon-box">
                <i class="ri-newspaper-line" style="color: #47aeff;"></i>
                  <h3>Material de Clases</h3>
              </div>
            </a>
          </div>
          <div class="col-lg-3 col-md-4 mt-3">
            <a href="" data-bs-toggle="modal" style='cursor: pointer' data-bs-target="#gradosVid">
              <div class="icon-box">
                <i class="ri-movie-2-line" style="color: #ffa76e;"></i>
                  <h3>Video Clases</h3>
              </div>
            </a>
          </div>
          <div class="col-lg-3 col-md-4 mt-3">
            <a href="" data-bs-toggle="modal" style='cursor: pointer' data-bs-target="#gradosNot">
              <div class="icon-box">
                <i class="ri-file-list-3-line" style="color: #11dbcf;"></i>
                  <h3>Calificaciones</h3>
              </div>
            </a>
          </div>
          <div class="col-lg-3 col-md-3 mt-3">
            <a href="" data-bs-toggle="modal" style='cursor: pointer' data-bs-target="#gradosCom" title="Envíe correos y mensajes">
              <div class="icon-box">
                <i class="fas fa-users" style="color: #FFA900;"></i>
                  <h3>Estudiantes</h3>
              </div>
            </a>
          </div>
          <div class="col-md-12 from-group text-center titulo" style="margin-top: 2%;">
            <p>Opciones de Bachillerato</p>
          </div>
          <div class="col-lg-3 col-md-3 mt-3">
            <a href="list-materias.php">
              <div class="icon-box">
                <i class="ri-newspaper-line" style="color: #47aeff;"></i>
                  <h3>Material de Clases</h3>
              </div>
            </a>
          </div>
          <div class="col-lg-3 col-md-3 mt-3">
            <a href="list-video-materias.php">
              <div class="icon-box">
                <i class="ri-movie-2-line" style="color: #ffa76e;"></i>
                  <h3>Video Clases</h3>
              </div>
            </a>
          </div>
          <div class="col-lg-3 col-md-3 mt-3">
            <a href="list-mate-prof.php">
              <div class="icon-box">
                <i class="ri-file-list-3-line" style="color: #11dbcf;"></i>
                  <h3>Calificaciones</h3>
              </div>
            </a>
          </div>
          <div class="col-lg-3 col-md-3 mt-3">
            <a href="" data-bs-toggle="modal" style='cursor: pointer' data-bs-target="#gradosBach" title="Envíe correos y mensajes">
              <div class="icon-box">
                <i class="fas fa-users" style="color: #FFA900;"></i>
                  <h3>Estudiantes</h3>
              </div>
            </a>
          </div>
          <!--div class="col-lg-3 col-md-3 mt-3">
            <a href="list-comunica.php" title="Publique un comunicado, documento, plan de estudio etc, destinado a un año y sección">
              <div class="icon-box">
                <i class="fas fa-exclamation-triangle" style="color: #FFA900;"></i>
                  <h3>Comunicados</h3>
              </div>
            </a>
          </div-->

          <div class="col-md-12 from-group text-center titulo" style="margin-top: 2%;">
            <p>Opciones del Docente</p>
          </div>
          <div class="col-lg-3 col-md-4 mt-4">
            <a href="perfil.php">
              <div class="icon-box">
                <i class="ri-user-2-fill" style="color: #ffbb2c;"></i>
                <h3>Mi Perfil</h3>
              </div>
            </a>
          </div>
          <div class="col-lg-3 col-md-4 mt-4">
            <a href="carnet.php" target="_blank">
              <div class="icon-box">
                <i class="ri-shield-user-line " style="color: #5578ff;"></i>
                <h3>Carnet</h3>
              </div>
            </a>
          </div>
          <div class="col-lg-3 col-md-4 mt-4">
            <a href="tutoYou.php">
              <div class="icon-box">
                <i class="ri-vidicon-fill" style="color: #e80368;"></i> 
                  <h3>Video Tutoria</h3>
              </div>
            </a>
          </div>
          <div class="col-lg-3 col-md-4 mt-4">
            <a href="horario-pdf.php" target="_blank">
              <div class="icon-box">
                <i class="bi-clock" style="color: #e361ff;"></i>
                <h3>Horario</h3>
              </div>
            </a>
          </div>
        </div>
      </div>
    </section><!-- End Features Section -->
  </main><!-- End #main -->
  <style type="text/css">
    .btn-float{
      display:scroll;
      position:fixed;
      /*height: 45px;*/
      right: 70px;
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
  {?>
    <button class="btn btn-success btn-float " onclick='window.open("contactoAlum.php")' ><i class="fas fa-comments fa-2x"></i>&nbsp;&nbsp;Mensajes</button><?php 
  }?>
  <div class="modal fade" id="ingreso" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                  <img src="../assets/img/logo.jpg" style="width:30%;">
                  <h3 id="mensajeNuevo"></h3>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="btnMensaje" style="display:none;" class="btn btn-primary" onclick='window.open("contactoAlum.php")'>Ver mensajes</button>
              <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#verArchivo">Ver instructivo</button>
            </div>
        </div>
      </div>
  </div>
  <div class="modal fade" id="verArchivo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog  modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Instructivo:</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="col">
              <div class="row">
                <div class="col-md-12 text-center col-xs-12 col-sm-12" style="border-style: ridge; margin-top: 2%;">
                    <iframe style="width: 100%;" height="500" src="manualProf.pdf?2" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cerrar Ventana</button>
          </div>
        </div>
      </div>
    </div>
  <input type="hidden" id="nombreUsuario" value="<?= $_SESSION['nomuser'] ?>">

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php";
  if(isset($_GET['ingreso']))
  {
    $chat_query=mysqli_query($link,"SELECT count(id_chat) as msjNue FROM chat WHERE id_docente='$idDocente' and visto='2' and envia='2' ");
    $msjNue=0;
    if(mysqli_num_rows($chat_query) > 0)
    {
      $row2=mysqli_fetch_array($chat_query);
      $msjNue=$row2['msjNue'];
    }
    ?>
    <input type="hidden" id="msjNue" value="<?= $msjNue ?>">
    <script type="text/javascript">
      nomA=$('#nombreUsuario').val();
      msj=$('#msjNue').val();
      document.querySelector('#nomAlumno').innerText = nomA;
      if(msj>1)
      {
        $('#btnMensaje').show(); 
        document.querySelector('#mensajeNuevo').innerText = 'Tiene ('+msj+') mensajes nuevos';
      }
      if(msj==1)
      {
        $('#btnMensaje').show(); 
        document.querySelector('#mensajeNuevo').innerText = 'Tiene ('+msj+') mensaje nuevo';
      }
      $(window).load(function() { $('#ingreso').modal('show'); });
    </script><?php

  } ?>

</body>

</html>