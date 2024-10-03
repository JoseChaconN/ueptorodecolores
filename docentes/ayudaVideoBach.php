<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<2) 
{
  header("../location:index.php?vencio");
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
        <h2>Como agregar video a pagina web desde el celular</h2>
      </div>
    </div><!-- End Breadcrumbs -->

    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="row" style="margin-top: 1%;">
            <div class="col-md-12 form-group">
              <iframe id="videoAula" style="width:100%;" height="500" src="ayudaCelBach.pdf" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            
          </div>
          <div class="d-grid gap-2 col-6 mx-auto" style="margin-top: 2%;">
            <button type="button" onclick="javascript:window.close();opener.window.focus();" class="btn btn-success btn-lg">Cerrar Ventana</button>
          </div>
        </div>
      </div>
    </section>
    

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>

</body>

</html>