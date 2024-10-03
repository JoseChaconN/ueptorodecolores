<?php
session_start();
if(!isset($_SESSION["usuario"])) 
{
  header("location:index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("inicia.php");
include_once("conexion.php");
$link = conectarse();
$usuario = $_SESSION['usuario'];
$tablaPeriodo=$_SESSION['tablaPeriodo'];
$result = mysqli_query($link,"SELECT A.nombre, A.apellido, A.ruta, B.nombreGrado FROM alumcer A, grado".$tablaPeriodo." B WHERE cedula ='$usuario' and A.grado=B.grado"); 
while ($row = mysqli_fetch_array($result))
{
  $alumno=$row['nombre'].' '.$row['apellido'];
  $foto_alu = $row['ruta'];
  $nomGra=($row['nombreGrado']);
} ?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php"; ?>
  
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Encuesta año escolar <?= PROXANOE ?></h2>
      </div>
    </div><!-- End Breadcrumbs -->

    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="row">
            <div class="col-md-12 form-group text-center">
              <img src="<?= 'fotoalu/'.$foto_alu.'?'.time().mt_rand(0, 99999); ?>" class="thumb" />
            </div>
          </div>
          <form role="form" method="POST" action="encuesta-pdf.php" target="_BLANK">
            <div class="row" style="margin-top: 2%;">
              <div class="col-md-3 form-group">
                <label>Cedula</label>
                <input type="text" class="form-control" readonly="" value="<?= $usuario; ?>" >
              </div>
              <div class="col-md-6 form-group">
                <label>Estudiante</label>
                <input type="text" readonly="" class="form-control" value="<?= $alumno; ?>">
              </div>
              <div class="col-md-3 form-group">
                <label>Cursando</label>
                <input type="text" readonly="" class="form-control" value="<?= $nomGra; ?>">
              </div>
            </div>
            <div class="row" style="margin-top: 2%;">
              <h3>1.- Selecione la alternativa deseada</h3>
            </div>
            <div class="row" style="margin-top: 2%;">
              <div class="col-md-12 form-group">
                <h4>A) <input type="radio" name="encuesta" required checked="true" value="1"> RESERVA CUPO PARA NUEVO AÑO ESCOLAR ( <?php echo PROXANOE ?> ) 
                </h4>
              </div>
              <div class="col-md-12 form-group">
                <h4>B) <input type="radio" name="encuesta" required  value="2"> SOLICITA RETIRO Y ENTREGA DE DOCUMENTOS </h4>
              </div>
              <div class="col-md-12 form-group">
                <h4>C) <input type="radio" name="encuesta"  required value="3"> DESEA ZONIFICACION PARA UN PLANTEL OFICIAL ( SOLO 1° y 4° año) </h4>
              </div>
            </div>
            <div class="row" style="margin-top: 2%;">
              <h3>2.- Realizar la presente encuesta antes del 25 de Mayo de <?= substr(ANOESCM,5,4) ?> a fin de cubrir los requisitos de reservación, zonificación o retiro.</h3>
            </div>
            <div class="col-md-4 offset-4 form-group" style="margin-top: 2%;">
              <button style="width:100%;" type="submit" class="btn btn-success">Procesar Encuesta</button>
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