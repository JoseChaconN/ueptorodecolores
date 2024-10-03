<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<2) 
{
  header("location:../index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("../inicia.php");
include_once("../conexion.php");
$link = conectarse();
$idAlum=$_SESSION['idAlum'];
 ?>
<!DOCTYPE html>
<html lang="es">
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"><?php
  include_once "header.php";
  if(isset($_GET['verMsj']) && $_GET['verMsj']=='2')
  {
    $docente_query=mysqli_query($link,"SELECT A.fecha_chat, A.id_materia, B.idAlum as idAlumno, B.nombre as nombreAlu,B.apellido, D.nombreGrado,E.nombre as nombreSec, C.nombremate FROM chat A, alumcer B, materiass".$tablaPeriodo." C, grado".$tablaPeriodo." D, secciones E WHERE A.envia='2' and  A.id_docente='$idAlum' and A.idAlum=B.idAlum and A.id_materia=C.codigo and B.grado=D.grado and B.seccion=E.id GROUP BY A.idAlum,A.id_materia "); 
    $verMsj='2';
  }else
  {
    $docente_query=mysqli_query($link,"SELECT A.fecha_chat, A.id_materia, B.idAlum as idAlumno, B.nombre as nombreAlu,B.apellido, D.nombreGrado,E.nombre as nombreSec, C.nombremate FROM chat A, alumcer B, materiass".$tablaPeriodo." C, grado".$tablaPeriodo." D, secciones E WHERE A.visto='2' and A.envia='2' and  A.id_docente='$idAlum' and A.idAlum=B.idAlum and A.id_materia=C.codigo and B.grado=D.grado and B.seccion=E.id GROUP BY A.idAlum,A.id_materia ");
    $verMsj='1';
  }
   ?>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Contacto con mis Estudiantes</h2>
        
          <div class="col-md-4 offset-md-4">
            <label>Ver los mensajes</label>
            <select class="form-control" id="verMsj" onchange="actualVer()">
              <option value="1" <?php if($verMsj=='1'){ echo 'selected';} ?>>Nuevos</option>
              <option value="2" <?php if($verMsj=='2'){ echo 'selected';} ?>>Todos</option>
            </select>
          </div>
          <div class="col-md-4 offset-md-4" style="margin-top:1%;">
            <button type="button" onclick="javascript:window.close();opener.window.focus();" style="width:100%;" class="btn btn-warning btn-lg"><i class="fas fa-door-closed"></i> Salir</button>
          </div>
        
      </div>

    </div><!-- End Breadcrumbs -->

    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="table-responsive">
          <table id="table_id" class="table table-striped table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Estudiante</th>
                <th scope="col">Materia</th>
                <th scope="col">Grado</th>
                <th scope="col">Fecha/Hora</th>
                <th scope="col">Boton</th>
              </tr>
            </thead>
            <tbody><?php  
              $son=1;
              while($row=mysqli_fetch_array($docente_query)) 
              {
                $idAlumno=$row['idAlumno'];
                $alumno=$row['apellido'].' '.$row['nombreAlu'];
                $nombreGrado=($row['nombreGrado']);
                $id_materia=$row['id_materia'];
                $nombremate=$row['nombremate'];
                $nombreSec=$row['nombreSec'];
                $fecha_chat=date("d-m-Y H:i", strtotime($row['fecha_chat'])); ?>
                <tr >
                  <td><?= $son++; ?></td>
                  <td><?= $alumno ?></td>
                  <td><?= $nombremate ?></td>
                  <td><?= $nombreGrado.' '.$nombreSec ?></td>
                  <td align="center"><?= $fecha_chat ?></td>
                  <td style="width: 15%;">
                    <button type="button" onclick='window.open("chatAlumno.php?id=<?= encriptar($idAlumno) ?>&idMat=<?= encriptar($id_materia) ?>")' class="btn btn-outline-primary" title="Enviar mensaje"><i class="fas fa-comments fa-2x"></i></button></a>
                  </td>
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
    function enviarMail(nom,id,mate) {
      $('#docente').val(nom)
      $('#materia').val(mate)
    }
    function enviar() {
      msj=$('#mensaje').val()
      if((msj==''))
      {
          Swal.fire({
          icon: 'error',
          title: 'Error!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Mensaje NO enviado por no contener texto que enviar'
        })
      }else 
      {
        Swal.fire({
          icon: 'success',
          title: 'Excelente!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Su mensaje fue enviado exitosamente'
        })
      }
    }
    function actualVer() {
      ver=$('#verMsj').val()
      location.href="contactoAlum.php?verMsj="+ver;
    }
  </script>

</body>

</html>