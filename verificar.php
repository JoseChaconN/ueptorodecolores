<?php
session_start();
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("inicia.php");
include_once("conexion.php");
$link = conectarse(); ?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php";
  $idAlum=desencriptar($_POST['idAlum']);
  $tablaPeriodo=$_POST['tabla'];
  $alumno_query=mysqli_query($link,"SELECT cedula,morosida,pagado,grado FROM alumcer Where idAlum = '$idAlum'"); 
  while($row=mysqli_fetch_array($alumno_query))
  {
    $morosida=$row['morosida'];
    $pagado=$row['pagado'];
    $cedula=$row['cedula'];
    $grado=$row['grado'];
  } ?>
  
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Documentos solo para verificar su autenticidad</h2>
      </div>
    </div><!-- End Breadcrumbs -->

    
    <section id="features" class="features" >
      <div class="container" data-aos="fade-up">
        <div class="section-title" style="margin-top:2%;">
          <h2>Reportes</h2>
          <p>Documentación</p>
        </div>
        <div class="row" data-aos="zoom-in" data-aos-delay="100">
          <div class="col-lg-3 col-md-4 mt-4 mt-md-0">
            <div class="icon-box">
              <i class="ri-newspaper-line" style="color: #5578ff;"></i>
              <h3><a href="verifi/cons-ins.php?ced=<?= $cedula ?>" target="_blank">Constancia de Inscripción</a></h3>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 mt-4 mt-md-0">
            <div class="icon-box">
              <i class="ri-file-mark-line" style="color: #e80368;"></i>
              <h3><a href="verifi/cons-est.php?ced=<?= $cedula ?>" target="_blank">Constancia de Estudio</a></h3>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 mt-4 mt-md-0">
            <div class="icon-box">
              <i class="ri-file-list-3-line" style="color: #11dbcf;"></i><?php 
              if($morosida>0 || $pagado==0)
              { ?>
                <h3><a onclick="msjMoro()" style='cursor: pointer'>Boletin de Calificaciones</a></h3><?php 
              }else
              {
                if($grado>60)
                { ?>
                  <h3><a href="verifi/bole-liceo.php?id=<?= encriptar($idAlum) ?>&peri=<?= encriptar($tablaPeriodo) ?>&lapsom=3" target="_blank">Boletin de Calificaciones</a></h3><?php 
                }else
                {?>
                  <h3><a href="verifi/bole-primaria.php">Boletin de Calificaciones</a></h3><?php 
                }
              }?>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 mt-4 mt-md-0">
            <div class="icon-box">
              <i class="ri-medal-fill" style="color: #ff5828;"></i><?php 
                if($morosida>0 || $pagado==0)
                { ?>
                  <h3><a onclick="msjMoro()" style='cursor: pointer'>Certificación de Notas</a></h3><?php 
                }else
                {?>
                  <h3><a href="verifi/certifi-pdf.php?id=<?= encriptar($idAlum) ?>" target="_blank">Certificación de Notas</a></h3><?php 
                }?>
            </div>
          </div>
        </div>
        <div class="col-md-12" style="margin-top:5%;">
          <p style="text-align: justify;"><strong>Nota:</strong> Estimado representante esta sección es únicamente para verificar la autenticidad de los documentos emitidos por la <?= NKXS.' '.EKKS ?>, desde otra institución, si desea emitir uno de estos documentos debe ir a inicio y hacer clic en el botón ingresar seguidamente colocar su usuario y contraseña para luego emitir el documento deseado desde la sección de reportes.</p>
        </div>
      </div>
    </section>
    

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <script type="text/javascript">
    function msjMoro()
    {
      Swal.fire({
        icon: 'info',
        title: 'Informacion Importante!',
        confirmButtonText:
        '<i class="fa fa-thumbs-up"></i> Entendido',
        text: 'Sr.(a) Representante en necesaria su presencia en nuestro departamento de administración a la brevedad.'
      })
    }
  </script>

</body>

</html>