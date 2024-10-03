<?php
session_start();
if(!isset($_SESSION["usuario"])) 
{
  header("location:index.php#features");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("inicia.php");
include_once("conexion.php");
$link = conectarse(); ?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php";
  $nomPeri=$_SESSION['nombre_periodo'];
  $utiles_query = mysqli_query($link,"SELECT titulo,nombre_archivo FROM utiles WHERE grado='$grado' and seccion='$seccion' and periodo='$nomPeri' ORDER BY fecha_doc DESC"); ?>
  
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Lista de Utiles Escolares <?= $nomPeri ?></h2>
      </div>
    </div><!-- End Breadcrumbs -->

    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up"><?php
        while($row=mysqli_fetch_array($utiles_query) ) 
        { ?>
          <div class="row" style="margin-top: 2%;">
            <div class="col-md-12 form-group">
              <iframe src="archivos/<?= $row['nombre_archivo']; ?>" frameborder="0" allowfullscreen style="width: 100%; height: 560px;"   ></iframe>
            </div>
            </div><?php
        }?>
         
      </div>
    </section>
    

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php";
  mysqli_free_result($utiles_query); ?>

</body>

</html>