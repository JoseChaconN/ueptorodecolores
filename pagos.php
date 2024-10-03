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
$nombrePeriodo=$_SESSION['nombre_periodo'];
$tablaPeriodo=$_SESSION['periodoAlum'];
$result = mysqli_query($link,"SELECT A.nombre AS alumno ,B.nombreGrado AS nom_gra,  A.cedula, A.apellido, A.ruta as foto_alu, A.grado, A.Periodo, C.ruta as foto_rep FROM alumcer A,grado".$tablaPeriodo." B,represe C WHERE A.idAlum = '$idAlum' and B.grado = A.grado and C.cedula = A.ced_rep "); 
while ($row = mysqli_fetch_array($result))
{   
  $cedula = $row['cedula'];
  $nombre = $row['alumno'];  
  $apellido = $row['apellido'];
  $nom_gra = ($row['nom_gra']);
  $foto_alu = 'fotoalu/'.$row['foto_alu'];
  $foto_rep = 'fotorep/'.$row['foto_rep'];
  $grado = $row['grado'];
  $periodo = $row['Periodo'];
}
$pagado=$_SESSION['pagado'];
$deuda=$_SESSION['totalPeriodo'];
$morosida=$_SESSION['morosida'];

 ?>
<!DOCTYPE html>
<html lang="es">
  <!--link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"-->
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"><?php
  include_once "header.php"; ?>
  
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h3>Historial de Pagos Procesados <?= $nombrePeriodo ?></h3>
      </div>
    </div><!-- End Breadcrumbs -->
    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="col text-center">
            <img class='thumb from-group img-circle'  src="<?= $foto_alu; ?>" />     
            <img class='thumb from-group img-circle'  src="<?= $foto_rep; ?>" /> 
          </div>
        </div>
        <div class="row" style="margin-top:2%;">
          <div class="col-md-2 form-group">
            <label>Cedula</label>
            <label class="form-control"><?= $cedula;?></label>
          </div>
          <div class="col-md-4 form-group">
            <label>Nombres</label>
            <label class="form-control"><?= $nombre;?></label>
          </div>
          <div class="col-md-4 form-group">
            <label>Apellidos</label>
            <label class="form-control"><?= $apellido;?></label>
          </div>
          <div class="col-md-2 form-group">
            <label>Cursante</label>
            <label class="form-control"><?= $nom_gra;?></label>
          </div>
        </div>
        <div class="row" style="margin-top:2%;">
          <!--div class="col-md-3 form-group">
            <label>Deuda Total del Año</label>
            <label class="form-control"><?= number_format($deuda,2,',','.');?> $</label>
          </div>
          <div class="col-md-3 form-group">
            <label>Monto Pagado</label>
            <label class="form-control"><?= number_format($pagado,2,',','.');?> $</label>
          </div>
          <div class="col-md-3 form-group">
            <label>Monto por Pagar</label>
            <label class="form-control"><?= number_format($deuda-$pagado,2,',','.');?> $</label>
          </div>
          <div class="col-md-3 form-group">
            <label>Morosidad</label>
            <label class="form-control"><?= number_format($morosida,2,',','.');?> $</label>
          </div-->
        </div>
        <div class="row" style="margin-top:2%;">
          <table id="table_id" class="table table-striped table-hover">
            <thead>
              <tr>
                <th scope="col">Recibo</th>
                <th scope="col">Fecha</th>
                <th scope="col">Concepto</th>
                <th scope="col">Monto Bs.</th>
              </tr>
            </thead>
            <tbody><?php 
              $result2 = mysqli_query($link,"SELECT recibo, fecha, concepto, monto FROM pagos".$tablaPeriodo." WHERE statusPago=1 and idAlum = '$idAlum' and recibo>0 ORDER BY fecha DESC ");
              while ($row = mysqli_fetch_array($result2))
              { ?>
                <tr>
                  <td title="ssdsdsdds"><?= $row["recibo"]; ?></td>
                  <td><?= date("d-m-Y", strtotime($row['fecha'])); ?></td>
                  <td><?= utf8_decode($row['concepto']); ?></td>
                  <td align="right "><?= number_format($row['monto'],2,',','.'); ?></td>
                </tr> <?php 
              } ?>
            </tbody>
          </table>
        </div>
      </div>

      
    </section>
    

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <!--script src="assets/vendor/datatables/jquery.dataTables.js"></script-->
  <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript">
    $(document).ready( function () {
        $('#table_id').DataTable({
        "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }
        });
    } );
  </script><?php 
  if($deuda==0)
  {?>
    <script type="text/javascript">
      Swal.fire({
        icon: 'info',
        title: 'Notificación!',
        confirmButtonText:
        '<i class="fa fa-thumbs-up"></i> Entendido',
        text: 'No tiene pagos registrados, por favor verifique que la cedula del estudiante coincida con la cedula registrada en un recibo de pago.'
      })
    </script><?php
  }?>

</body>

</html>