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

$result = mysqli_query($link,"SELECT A.cedula, A.nombre, A.apellido, A.Periodo, A.ruta, A.ced_rep,A.grado,A.seccion, B.nombreGrado, C.representante FROM alumcer A, grado".$tablaPeriodo." B, represe C WHERE A.idAlum ='$idAlum' and B.grado=A.grado and A.ced_rep=C.cedula "); 
while ($row = mysqli_fetch_array($result))
{   
  $cedula = $row['cedula'];
  $nombre = ($row['nombre']).' '.($row['apellido']);  
  $periodo = $row['Periodo'];
  $foto_alu = 'fotoalu/'.$row['ruta'];
  $nombreGrado=($row['nombreGrado']);
  $grado=$row['grado'];
  $seccion=$row['seccion'];
  $ruta=$row['ruta'];
}
$horario_query = mysqli_query($link,"SELECT archivo FROM horario WHERE grado='$grado' and seccion='$seccion' and periodo='$periodo' and status='1' "); 
if(mysqli_num_rows($horario_query) > 0)
{
  $row=mysqli_fetch_array($horario_query);
  $archivo='horario/'.$row['archivo'];
  $exis=1;
}else{
  $archivo='imagenes/pendiente.jpg';
  $exis=2;
}

if($ruta==''){
  $foto_alu='imagenes/usuario.png';
}
 ?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php"; ?>
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Horario de Clases<br>Periodo Escolar <?= $periodo ?></h2>
      </div>
    </div><!-- End Breadcrumbs -->
    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="col text-center">
            <img class='thumb from-group img-circle'  src="<?= $foto_alu; ?>" />     
          </div>
        </div>
        <div class="row" >
          <div class="col-md-3 form-group">
            <label>Cedula </label>
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
        </div><?php 
        if ($exis==1) {?>
          <div class="col-md-8 offset-md-2 col-12" style="margin-top:1%;"><?php 
        }else{?>
          <div class="col-md-6 offset-md-3 col-12" style="margin-top:1%;"><?php 
        }?>

          <iframe id="videoVer" style="width: 100%; " height="550" src="<?= $archivo ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        
      </div>
    </section>
    

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>

</body>

</html>