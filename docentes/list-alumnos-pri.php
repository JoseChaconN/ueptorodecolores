<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<1 ) 
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
<html lang="es">
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"><?php
  include_once "header.php";
  $grado=$_POST['grado'];
  $seccion=$_POST['seccion'];
  $grado_query=mysqli_query($link,"SELECT nombreGrado FROM grado".$tablaPeriodo." WHERE grado='$grado'");
  while($row=mysqli_fetch_array($grado_query))
  {
    $nombreGrado=$row['nombreGrado'];
  }
  $seccion_query=mysqli_query($link,"SELECT nombre FROM secciones WHERE id='$seccion'");
  while($row=mysqli_fetch_array($seccion_query))
  {
    $nombreSec=$row['nombre'];
  }
    
  $alumnos_query=mysqli_query($link,"SELECT A.idAlum,A.cedula,A.apellido,A.nombre FROM alumcer A, notaprimaria".$tablaPeriodo." B WHERE B.grado='$grado' AND B.idSeccion='$seccion' and B.statusAlum='1' and B.idAlumno=A.idAlum ORDER BY A.apellido ASC"); ?>
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h3><?= 'Estudiantes del '.$nombreGrado.' '.$nombreSec.'<br>Lapso : '.$lapsoActivo.'°' ?></h3>
      </div>
    </div><!-- End Breadcrumbs -->
    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="table-responsive">
          <table id="table_id" class="table table-striped table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Cedula</th>
                <th scope="col">Apellido</th>
                <th scope="col">Nombre</th>
                <th scope="col">Boton</th>
              </tr>
            </thead>
            <tbody><?php  
              $son=0;
              
              while($row=mysqli_fetch_array($alumnos_query)) 
              { 
                $ced_alu=$row['cedula'];
                $nombre=$row['nombre'];
                $apellido=$row['apellido'];
                $idAlum=$row['idAlum'];
                
                $son++;?>
                <tr>
                  <td><?= $son; ?></td>
                  <td><?= $ced_alu ?></td>
                  <td><?= $apellido ?></td>
                  <td><?= $nombre ?></td>
                  <td>
                    <button type="button" title='Carga de la escala evaluativa' onclick='window.open("carga-nota-pri.php?id=<?= encriptar($idAlum) ?>&grado=<?= encriptar($grado); ?>&seccion=<?= $seccion;?>&nomGra=<?= $nombreGrado ?>&nomSec=<?= $nombreSec ?>")' class="btn btn-outline-primary" ><i class="ri-edit-2-line"></i></button>
                  </td>
                </tr><?php 
              } ?>
            </tbody>
          </table>
          <input type="hidden" id="nombreGrado" value="<?= $nombreGrado ?>">
          <input type="hidden" id="nomsec" value="<?= $nombreSec ?>">
        </div>
      </div>
    </section>
  </main><!-- End #main -->
  

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript">
    $(document).ready( function () 
    {
      $('#table_id').DataTable({
        "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }
      });
      
    } );
  </script>

</body>

</html>