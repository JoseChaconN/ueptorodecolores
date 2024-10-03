<?php
session_start();
include_once("inicia.php");
include_once "header.php";
include_once "conexion.php";
$link = Conectarse();
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$url_actual =  $_SERVER["SERVER_NAME"];
$fechahoy = strftime( "%Y-%m-%d");
 ?>
<!DOCTYPE html>
<html lang="es"><?php
  $galeria_query=mysqli_query($link,"SELECT * FROM galeria_grupo where adultos is NULL and status=1 and fecha<='$fechahoy' ");
   ?>
  
  <main id="main">
    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>GALERÍA DE IMÁGENES</h2>
        <p style="font-size: 22px; ">Eventos acontecidos en nuestra institución</p>
        <h4><a href="<?= INSTAGRAM ?>" target="_blank" class="instagram">Síguenos en Instagram <i class="bx bxl-instagram"></i></a></h4>
      </div>
    </div><!-- End Breadcrumbs -->
    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
      
      <div class="container" data-aos="fade-up">
        <div class="row mt-5"><?php
          $van=0;
          while($row=mysqli_fetch_array($galeria_query))
          {
            $id=$row['id'];
            $titulo=$row['titulo'];
            $descripcion=$row['descripcion'];
            $fecha=date("d-m-Y", strtotime($row['fecha']));
            $foto_principal=$row['foto_principal'];?>
            <!--div class="col-md-4 text-center">
              <img onclick="ver('<?= $id ?>','<?= $titulo ?>','<?= $descripcion ?>','<?= $fecha ?>','<?= $foto_principal ?>')" src="<?= 'galeria/'.$foto_principal ?>" style="width:100%; height: auto; cursor: pointer;">
            </div--><?php
            if($van==3)
            {?>
              <div class="col-md-12" style="margin-bottom: 2%; ">
                <span> </span>
              </div><?php
              $van=0;
            }
            $galeria_query2=mysqli_query($link,"SELECT * FROM galeria where id_grupo='$id' ");
            while($row=mysqli_fetch_array($galeria_query2))
            {
              $id=$row['id'];
              $imagen=$row['imagen'];
              $van++;?>
              <div class="col-md-4 text-center">
                <img onclick="ver('<?= $id ?>','<?= $titulo ?>','<?= $descripcion ?>','<?= $fecha ?>','<?= $imagen ?>')" src="<?= 'galeria/'.$imagen ?>" style="width:100%; height: auto; cursor: pointer;">
              </div><?php 
              if($van==3)
              {?>
                <div class="col-md-12" style="margin-bottom: 2%; ">
                  <span> </span>
                </div><?php
                $van=0;
              }
            }
          }
          ?>
        </div>
      </div>
    </section>
    <div class="modal fade" id="verFoto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Galeria de imagenes</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="col-md-12">
              <h5 id="titulo"></h5>
              <h6 id="descripcion"></h6>
            </div>
            <div class="col-md-12">
              <img id="foto" src="" style="width: 100%; height: auto; ">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div><!-- End Contact Section -->
  </main><!-- End #main -->
  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <script type="text/javascript">
    jQuery(document).ready(function($) 
    {
      
    });
    function ver(id,tit,des,fec,fot) {
      $('#verFoto').modal('show')
      document.querySelector('#titulo').innerText = tit+' ('+fec+')';
      document.querySelector('#descripcion').innerText = des;
      $("#foto").attr("src",'galeria/'+fot);
    }
  </script>
</body>

</html>