<?php
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("inicia.php");
include_once("conexion.php");
$link = conectarse(); ?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php"; ?>
  
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Crear la foto para la planilla de inscripcion (active el audio)</h2>
      </div>
    </div><!-- End Breadcrumbs -->

    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="col-md-8 offset-md-2 text-center col-xs-12 col-sm-12">
              <iframe width="100%" height="415" alt="Video Tutorial" src="https://www.youtube.com/embed/ThlkVpyrKwY" frameborder="0" allowfullscreen ></iframe>
            </div>
            <div class="col-md-3 offset-md-3">
              <a href="https://app.prntscr.com/es/" target="_blank" download="setup-lightshot.exe">
              <button type="button" style="width:100%;" class="btn btn-success">Descargar</button>
              </a>  
            </div>
            <div class="col-md-3">
              <button type="button" onclick="javascript:window.close();opener.window.focus();" style="width:100%;" class="btn btn-warning">Regresar</button>
            </div>
            
        </div>
      </div>
    </section>
    

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>

</body>

</html>