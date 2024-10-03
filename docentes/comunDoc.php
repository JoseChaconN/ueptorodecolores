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
$link = conectarse(); ?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php"; ?>
  
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>TITULO</h2>
      </div>
    </div><!-- End Breadcrumbs -->

    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="row">
            <div class="col-md-12 form-group text-center">
              <img src="<?= $foto_alu ?>" class="thumb" />
            </div>
          </div>
          <form role="form" method="POST" enctype="multipart/form-data" action="" >
            <div class="row" style="margin-top: 2%;">
              <div class="col-md-3 form-group">
                
              </div>
              <div class="col-md-3 form-group">
                
              </div>
              <div class="col-md-3 form-group">
                
              </div>
              <div class="col-md-3 form-group">
                
              </div>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto" style="margin-top: 2%;">
              <button type="submit" value="1" name="enviar" class="btn btn-success btn-lg"><i class="ri-upload-cloud-line"></i> Guardar Cambios</button>
            </div>
          </form>
        </div>
      </div>
    </section>
    

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>

</body>

</html>