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
$link = conectarse();
$idAlum=$_SESSION['idAlum']; ?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php";
  
  $docente_query=mysqli_query($link,"SELECT B.idAlum,B.ruta,B.nombre,B.apellido FROM trgsp".$tablaPeriodo." A, alumcer B WHERE (A.id_grado1='$grado' or A.id_grado2='$grado') and (A.id_seccion1='$seccion' or A.id_seccion2='$seccion') and A.ced_prof=B.cedula  ");
   ?>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Contacto con mis docentes</h2>
      </div>
    </div><!-- End Breadcrumbs -->

    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="table-responsive">
          <table id="table_id" class="table table-striped table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Foto</th>
                <th scope="col">Nombre y Apellido</th>
                <th scope="col">Mensajes</th>
                <th scope="col">Boton</th>
              </tr>
            </thead>
            <tbody><?php  
              $son=1;
              while($row=mysqli_fetch_array($docente_query)) 
              { 
                $idDocente=$row['idAlum'];
                $foto=$row['ruta'];
                $foto = (empty($foto)) ? 'imagenes/usuario.png' : 'docentes/fotodoc/'.$foto ;
                $docente=$row['nombre'].' '.$row['apellido'];
                $msjNue=0;
                $mensajes_query=mysqli_query($link,"SELECT count(id_chat) as msjHay FROM chat WHERE id_docente='$idDocente' and idAlum='$idAlum' and visto='2' and envia='1' ");
                $row2=mysqli_fetch_array($mensajes_query);
                $msjHay=$row2['msjHay'];
                 ?>
                <tr <?php if($msjHay>0){ echo 'style="background-color: #E1BEE7;"';}?> >
                  <td><?= $son++; ?></td>
                  <td><img style="width: 80px; height:80px;" src="<?= $foto.'?'.time().mt_rand(0, 99999) ?>"></td>
                  <td><?= $docente ?></td>
                  <td align="center"><?= $msjHay ?></td>
                  <td style="width: 15%;">
                    <button type="button" onclick='window.open("chatDocentePri.php?id=<?= encriptar($idDocente) ?>")' class="btn btn-outline-primary" title="Enviar mensaje"><i class="fas fa-comments fa-2x"></i></button></a>
                  </td>
                </tr><?php 
              } ?>
              </form>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main><!-- End #main -->
  

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <script type="text/javascript">
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
  </script><?php 
  mysqli_free_result($docente_query);
  mysqli_free_result($mensajes_query);?>

</body>

</html>