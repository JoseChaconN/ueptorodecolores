<?php
session_start();
if(!isset($_SESSION["usuario"]) && !isset($_SESSION['password'])) 
{
  $lapsoMod = $_GET['lapsoMod'];
  $profesor = $_GET["ced_prof"]; 
  //header("location:../index.php?vencio"); 
  header("location:login.php?ced_prof=$profesor&lapsoMod=$lapsoMod");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("../inicia.php");
include_once("../conexion.php");
$link = conectarse();
$tablaPeriodo=$_SESSION['tablaPeriodo'];
if(empty($_SESSION['admin']))
{
  $profesor = $_SESSION["usuario"]; 
} else
{
  $profesor = $_GET["ced_prof"];  
}
$lapsoMod = (isset($_GET['lapsoMod'])) ? $_GET['lapsoMod'] : '';
$profe=mysqli_query($link,"SELECT * FROM alumcer WHERE cedula = '$profesor' ");
  while($row=mysqli_fetch_array($profe))
  {
    $nomprofe = $row['nombre'].' '.$row['apellido'];
  }
 ?>
<!DOCTYPE html>
<html lang="es">
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"><?php
  include_once "header.php";

  $query5=mysqli_query($link,"SELECT * FROM preinscripcion WHERE id = 2 ");
  while($row5=mysqli_fetch_array($query5))
  {
    $lapsoact=$row5['lapso'];     
  }
  $lapsoact = (!empty($lapsoMod)) ? $lapsoMod : $lapsoact ;
  if ($lapsoact==1) {$nomlapso='Primero';}
  if ($lapsoact==2) {$nomlapso='Segundo';}
  if ($lapsoact==3) {$nomlapso='Tercero';}
  
  $materias_query=mysqli_query($link,"SELECT * FROM trgsmp".$tablaPeriodo." WHERE ced_prof = '$profesor' ORDER BY cod_grado , cod_seccion , cod_materia "); ?>
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h4>Materias y Aulas Asignadas  <br>Prof. <?= $nomprofe.'<br>Lapso Activo: '.$nomlapso.'° ('.$_SESSION['nombre_periodo'].')' ?></h4>
      </div>
    </div><!-- End Breadcrumbs -->
    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="table-responsive">
          <table id="table_id" class="table table-striped table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Año</th>
                <th scope="col">Sección</th>
                <th scope="col">Materia</th>
                <th scope="col">Boton</th>
                <th scope="col">Evaluado</th>
              </tr>
            </thead>
            <tbody>
              <input type="hidden" id='nombreGrado' value="<?= $nomGra ?>"><?php  
              $son=0;
              
              while($row=mysqli_fetch_array($materias_query)) 
              { 
                $cod_grado=$row["cod_grado"];
                $cod_seccion=$row["cod_seccion"];
                $cod_materia=$row["cod_materia"];
                $porctotal = 0;
                $grado_query=mysqli_query($link,"SELECT grado, nombreGrado FROM grado".$tablaPeriodo." WHERE grado = '$cod_grado'");
                while($row=mysqli_fetch_array($grado_query))
                {$nomgra=$row["nombreGrado"];}
                $seccion=mysqli_query($link,"SELECT * FROM secciones WHERE id = '$cod_seccion'");
                while($row=mysqli_fetch_array($seccion))
                {$nomsec=$row["nombre"];}
                $materia=mysqli_query($link,"SELECT * FROM materiass".$tablaPeriodo." WHERE codigo = '$cod_materia'");
                while($row=mysqli_fetch_array($materia))
                {$nommate=trim($row["nombremate"]);}

                $corte1_existe=mysqli_query($link,"SELECT * FROM cortes1".$tablaPeriodo." WHERE cod_materia='$cod_materia' AND cod_seccion='$cod_seccion' ");
                $lp=$lapsoact;
                while($row=mysqli_fetch_array($corte1_existe))
                {
                  $porc1 = $row['porcentaje1'.$lp] ;
                  $porc2 = $row['porcentaje2'.$lp] ;
                  $porc3 = $row['porcentaje3'.$lp] ;
                  $porc4 = $row['porcentaje4'.$lp] ;
                  $porc5 = $row['porcentaje5'.$lp] ;
                  $porctotal=($porc1+$porc2+$porc3+$porc4+$porc5);
                }
                $son++;?>
                <tr id="linea<?= $son ?>">
                  <td><?= $son; ?></td>
                  <td><?= ($nomgra) ?></td>
                  <td><?= $nomsec ?></td>
                  <td><?= $nommate ?></td>
                  
                  <td >
                    <button type="button" title='Estrategia 1' onclick='window.open("notabachi_n.php?grado=<?= encriptar($cod_grado); ?>&seccion=<?= $cod_seccion;?>&materia=<?= encriptar($cod_materia); ?>&estrat=1&lapsoActivo=<?= $lapsoact ?>")' class="btn btn-outline-primary" title="Ver listado de alumnos"><i class="ri-number-1"></i></button>
                    <button type="button" title='Estrategia 2' onclick='window.open("notabachi_n.php?grado=<?= encriptar($cod_grado); ?>&seccion=<?= $cod_seccion;?>&materia=<?= encriptar($cod_materia); ?>&estrat=2&lapsoActivo=<?= $lapsoact ?>")' class="btn btn-outline-primary" title="Ver listado de alumnos"><i class="ri-number-2"></i></button>
                    <button type="button" title='Estrategia 3' onclick='window.open("notabachi_n.php?grado=<?= encriptar($cod_grado); ?>&seccion=<?= $cod_seccion;?>&materia=<?= encriptar($cod_materia); ?>&estrat=3&lapsoActivo=<?= $lapsoact ?>")' class="btn btn-outline-primary" title="Ver listado de alumnos"><i class="ri-number-3"></i></button>
                    <button type="button" title='Estrategia 4' onclick='window.open("notabachi_n.php?grado=<?= encriptar($cod_grado); ?>&seccion=<?= $cod_seccion;?>&materia=<?= encriptar($cod_materia); ?>&estrat=4&lapsoActivo=<?= $lapsoact ?>")' class="btn btn-outline-primary" title="Ver listado de alumnos"><i class="ri-number-4"></i></button>
                    <button type="button" title='Estrategia 5' onclick='window.open("notabachi_n.php?grado=<?= encriptar($cod_grado); ?>&seccion=<?= $cod_seccion;?>&materia=<?= encriptar($cod_materia); ?>&estrat=5&lapsoActivo=<?= $lapsoact ?>")' class="btn btn-outline-primary" title="Ver listado de alumnos"><i class="ri-number-5"></i></button>

                    <button type="button" title='Estrategia 5' onclick='window.open("rep-cortes.php?grado=<?= $cod_grado; ?>&seccion=<?= $cod_seccion;?>&materia=<?= $cod_materia; ?>&ced_prof=<?= $cedula;?>&lapso=<?= $lapsoact ?>")' class="btn btn-outline-primary" target='_blank' title="Imprimir reporte de notas"><i class="ri-printer-line"></i></button>
                  </td>
                  <td><?= $porctotal.'%' ?></td>
                </tr><?php 
              } ?>
            </tbody>
          </table>
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