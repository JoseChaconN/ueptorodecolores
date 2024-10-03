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
$idAlum=$_SESSION['idAlum'];
$tablaPeriodo=$_SESSION['periodoAlum'];
$result = mysqli_query($link,"SELECT A.cedula, A.nombre, A.apellido, A.Periodo, A.ruta, A.ced_rep, B.nombreGrado, C.representante FROM alumcer A, grado".$tablaPeriodo." B, represe C WHERE A.idAlum ='$idAlum' and B.grado=A.grado and A.ced_rep=C.cedula "); 
while ($row = mysqli_fetch_array($result))
{   
  $cedula = $row['cedula'];
  $nombre = ($row['nombre']).' '.($row['apellido']);  
  $periodo = $row['Periodo'];
  $foto_alu = 'fotoalu/'.$row['ruta'];
  $nombreGrado=($row['nombreGrado']);
  $representante=$row['representante'];
  $ced_rep=$row['ced_rep'];
}
 ?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php"; ?>
  
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Constancia de Asistencia</h2>
      </div>
    </div><!-- End Breadcrumbs -->

    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="col text-center">
            <img class='thumb from-group img-circle'  src="<?php echo $foto_alu; ?>" />     
          </div>
        </div>
        <form role="form" method="POST" action="cons-asi.php" target="_blank" >
        <div class="row" style="margin-top:2%;">
          <div class="col-md-3 form-group">
            <label>Cedula</label>
            <input type="text" class="form-control" readonly="" name="cedula" value="<?php echo $cedula; ?>" >
          </div>
          <div class="col-md-6 form-group">
            <label>Estudiante</label>
            <input type="text" readonly="" class="form-control" name="" value="<?= $nombre;?>">
          </div>
          <div class="col-md-3 form-group">
            <label>Cursante</label>
            <input type="text" readonly="" class="form-control" name="" value="<?= $nombreGrado ?>">
          </div>
        </div>
        <div class="row" style="margin-top:2%;">
          <div class="col-md-3 form-group">
            <label>Cedula</label>
            <input type="text" class="form-control" readonly="" name="ced_rep" value="<?= $ced_rep; ?>" >
          </div>
          <div class="col-md-6 form-group">
            <label>Representante</label>
            <input type="text" readonly="" class="form-control" name="" value="<?= $representante;?>">
          </div>
          <div class="col-md-3 form-group">
            <label>Fecha de Asistencia</label>
            <input type="date" required name="diaasis" class="form-control" value="<?= $fechahoy ?>">
          </div>
        </div>
        <div class="row" style="margin-top:2%;">
          <div class="col-md-12 form-group">
            <label>Motivo</label>
            <input type="text" class="form-control" name="motivo" required maxlength="90" placeholder="Ingrese el Motivo por el cual asiste a la Institución" >
          </div>
        </div>
        <div class="d-grid gap-2 col-6 mx-auto text-center" style="margin-top: 2%;">
          <button type="submit" value="1" name="enviar" class="btn btn-success btn-lg"><i class="ri-printer-line"></i> Imprimir constancia</button>
        </div>
        </form>
      </div>
    </section>
    

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>

</body>

</html>