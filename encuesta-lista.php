<?php
session_start();
if(!isset($_SESSION["usuario"])) 
{
  header("location:index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
$fecha_hoy = date("Y-m-d H:i:s");
include_once("inicia.php");
include_once("conexion.php");
$link = conectarse();
$idAlum=$_SESSION['idAlum'];
$nombrePeriodo=$_SESSION['nombre_periodo'];
$tablaPeriodo=$_SESSION['periodoAlum'];
$result = mysqli_query($link,"SELECT A.nombre AS alumno ,B.nombreGrado AS nom_gra,  A.cedula, A.apellido, A.ruta as foto_alu, A.grado, A.Periodo, A.reinscribe, A.seccion, C.ruta as foto_rep FROM alumcer A,grado".$tablaPeriodo." B,represe C WHERE A.idAlum = '$idAlum' and B.grado = A.grado and C.cedula = A.ced_rep "); 
while ($row = mysqli_fetch_array($result))
{   
  $cedula = $row['cedula'];
  $nombre = $row['alumno'];  
  $apellido = $row['apellido'];
  $nom_gra = ($row['nom_gra']);
  $foto_alu = 'fotoalu/'.$row['foto_alu'];
  $foto_rep = 'fotorep/'.$row['foto_rep'];
  $grado = $row['grado'];
  $seccion = $row['seccion'];
  $periodo = $row['Periodo'];
  $reinscribe=$row['reinscribe'];
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
        <h3>Encuestas Escolares <?= $nombrePeriodo ?></h3>
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
          <div class="table-responsive">
          <table id="table_id" class="table table-striped table-hover">
            <thead>
              <tr>
                <th scope="col">Nro</th>
                <th scope="col">Nombre de la Encuesta</th>
                <th scope="col">Desde</th>
                <th scope="col">Hasta</th>
                <th>Accion</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Cupo, Reservar o Retirar para el <?= PROXANOE ?></td>
                <td></td>
                <td></td>
                <td ><?php 
                  if($reinscribe>0){?>
                    <button type="button" class="btn btn-info btn-lg" onclick='window.open("encuesta-pdf.php?encuesta=<?= $reinscribe ?> ")'><i class="ri-printer-line"></i></button><?php
                  }else{?>
                    <a href="encuesta.php"><button type="button" class="btn btn-success btn-lg" title="Imprimir la encuesta"><i class="ri-upload-cloud-line"></i></button></a><?php
                  }?>
                </td>
              </tr><?php 
              $encuesta_query = mysqli_query($link,"SELECT * FROM encuesta WHERE status!='2' and periodo='$periodo' and '$fechahoy'>=fecha_ini and '$fechahoy'<=fecha_fin and IF(todos='S',periodo='$periodo','$grado'>=grado_des and '$grado'<=grado_has and '$seccion'>=sec_des and '$seccion'<=sec_has) ");
              $son=1;
              while ($row = mysqli_fetch_array($encuesta_query))
              { 
                $son++;
                $id_encuesta = $row["id_encuesta"];
                $titulo_enc=$row["titulo_enc"];
                $desde=$row['fecha_ini'];
                $hasta=$row['fecha_fin'];
                $respuestas_query=mysqli_query($link,"SELECT id_respuesta FROM encuesta_respuesta WHERE id_encuesta='$id_encuesta' and id_alum='$idAlum' ");
                if(mysqli_num_rows($respuestas_query) > 0){$imprime=1;}else{$imprime=2;} ?>
                <tr <?php if($imprime==2){echo 'style="background-color: #D7BDE2;"';} ?>>
                  <td><?= $son ?></td>
                  <td><?= $titulo_enc; ?></td>
                  <td><?= date("d-m-Y", strtotime($desde)); ?></td>
                  <td><?= date("d-m-Y", strtotime($hasta)); ?></td>
                  <td><?php 
                    if($fecha_hoy>=$desde && $fecha_hoy<=$hasta){
                      if ($imprime==1) {?>
                        <a href="encuestas-pdf.php?idEnc=<?= encriptar($id_encuesta); ?>" target="_blank" ><button type="button" class="btn btn-info btn-lg" title="Imprimir la encuesta"><i class="ri-printer-line"></i></button></a><?php 
                       }else{?>
                        <a href="encuesta-hacer.php?idEnc=<?= encriptar($id_encuesta); ?>"><button type="button" class="btn btn-success btn-lg" title="Realizar la encuesta"><i class="ri-upload-cloud-line"></i></button></a><?php
                       } 
                    }else{?>
                      <button type="button" onclick='vencida()' class="btn btn-success btn-lg" title="Realizar la encuesta"><i class="ri-upload-cloud-line"></i></button><?php
                    }?>
                  </td>
                </tr> <?php 
              } ?>
            </tbody>
          </table>
          </div>
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
    function hecha() {
      Swal.fire({
        position: 'top-end',
        icon: 'info',
        title: 'Disculpe, esta encuesta ya fue respondida',
        showConfirmButton: false,
        timer: 2500
      })
    }
    function vencida() {
      Swal.fire({
          position: 'top-end',
          icon: 'info',
          title: 'Disculpe, el tiempo establecido para responder esta encuesta ya termino',
          showConfirmButton: false,
          timer: 2500
      })
    }
  </script><?php 
  if (isset($_GET['sinresp'])) {?>
    <script type="text/javascript">
    Swal.fire({
          position: 'top-end',
          icon: 'info',
          title: 'Por favor, tomese un minuto y responda la encuesta pendiente',
          showConfirmButton: false,
          timer: 3500
      })
    </script><?php
  }?>

</body>

</html>