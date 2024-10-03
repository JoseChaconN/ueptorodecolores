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
  $guias_query=mysqli_query($link,"SELECT A.ced_prof,B.nombre,B.apellido,B.atencion,B.ruta FROM trgsmp".$tablaPeriodo." A, alumcer B where A.cod_grado='$grado' and A.cod_seccion='$seccion' and A.ced_prof=B.cedula and A.doc_guia='1' ");

  $docente_query=mysqli_query($link,"SELECT A.cod_materia,B.idAlum,B.ruta,B.correo,B.nombre,B.apellido, C.nombremate FROM trgsmp".$tablaPeriodo." A, alumcer B, materiass".$tablaPeriodo." C WHERE A.cod_grado='$grado' and A.cod_seccion='$seccion' and A.ced_prof=B.cedula and A.cod_materia=C.codigo ORDER BY C.codigo  ");
   ?>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Contacto con mis docentes</h2>
      </div>
    </div><!-- End Breadcrumbs -->
    <style type="text/css">
      .contenedor {
        text-align: center;
      }
      .imagen-normal {
          width: 50px; /* Tamaño original */
          transition: width 0.5s; /* Transición suave de tamaño */
      }
      .imagen-ampliada {
          width: 150px; /* Tamaño ampliado */
      }
    </style>
    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="table-responsive">
          <table id="table_id" class="table table-striped table-hover" style="height: 100px !important;">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Foto</th>
                <th scope="col">Materia</th>
                <th scope="col">Nombre y Apellido</th>
                <th>Mensajes</th>
                <th scope="col">Boton</th>
              </tr>
            </thead>
            <tbody>
              <form>
              <input type="hidden" id='nombreGrado' value="<?= $nomGra ?>"><?php  
              $son=1;
              while($row=mysqli_fetch_array($docente_query)) 
              { 
                $idDocente=$row['idAlum'];
                $foto=$row['ruta'];
                $foto = (empty($foto)) ? 'imagenes/usuario.png' : 'docentes/fotodoc/'.$foto ;
                $nombremate=$row['nombremate'];
                $codigo=$row['cod_materia'];
                $docente=$row['nombre'].' '.$row['apellido'];
                $msjNue=0;
                $mensajes_query=mysqli_query($link,"SELECT count(id_chat) as msjHay FROM chat WHERE id_docente='$idDocente' and id_materia='$codigo' and idAlum='$idAlum' and visto='2' and envia='1' ");
                $row2=mysqli_fetch_array($mensajes_query);
                $msjHay=$row2['msjHay'];
                 ?>
                <tr <?php if($msjHay>0){ echo 'style="background-color: #E1BEE7;"';}?>>
                  <td><?= $son++; ?></td>
                  <td>
                    <div class="contenedor">
                      <img id="<?= 'suFoto'.$son ?>" src="<?= $foto.'?'.time().mt_rand(0, 99999) ?>" class="imagen-normal" onmouseover="ampliarImagen(this)" onmouseout="restaurarImagen(this)">
                    </div>
                  </td>
                  <td><?= $nombremate ?></td>
                  <td><?= $docente ?></td>
                  <td align="center"><?= $msjHay ?></td>
                  <td style="width: 15%;">
                    <button type="button" onclick='window.open("chatDocente.php?id=<?= encriptar($idDocente) ?>&idMat=<?= encriptar($codigo) ?>")' class="btn btn-outline-primary" title="Enviar mensaje"><i class="fas fa-comments "></i></button></a>
                  </td>
                </tr><?php 
              } ?>
              </form>
            </tbody>
          </table>
        </div>
      </div>
    </section>
    <section id="contact" class="contact">
      <div class="container" data-aos="fade-up">
        <h2>Atención personal al Representante </h2>
        <div class="row mt-5"><?php
          $van=0; $atencion='';
          while($row=mysqli_fetch_array($guias_query))
          {
            $nombre=$row['nombre'];
            $apellido=$row['apellido'];
            $foto=$row['ruta'];
            if(empty($foto)){
              $foto='imagenes/usuario.png';
            }else{
              $foto='docentes/fotodoc/'.$foto;
            }
            $atencion=$row['atencion'];
            $van++; ?>
            <div class="col-md-6 col-12 row">
              <div class="col-md-4 col-6 text-center" style="margin-bottom: 1%; ">
                <img src="<?= $foto ?>" style="width:100%; height: auto; cursor: pointer;">
              </div>
              <div class="col-md-8 col-12" style="background-color: #E3F2FD; margin-bottom: 1%;">
                <h6>Docente:<br><strong><?= $nombre ?> <?= $apellido ?></strong></h6>
                <p style="text-align: justify;"><?= $atencion ?></p>
              </div>
              <input type="hidden" id="atiende" value="<?= $atencion ?>">  
            </div><?php
          }?>
          <input type="hidden" id="atiende" value="<?= $atencion ?>">
        </div>
      </div>
    </section>
    <div class="modal fade" id="verFoto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Atención personal al Representante</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row mt-5"><?php
              $van=0;
              mysqli_data_seek($guias_query, 0);
              while($row=mysqli_fetch_array($guias_query))
              {
                $nombre=$row['nombre'];
                $apellido=$row['apellido'];
                $foto=$row['ruta'];
                if(empty($foto)){
                  $foto='imagenes/usuario.png';
                }else{
                  $foto='docentes/fotodoc/'.$foto;
                }
                $atencion=$row['atencion'];
                $van++; ?>
                <div class="col-md-12 col-12 row">
                  <div class="col-md-2 col-6 text-center" style="margin-bottom: 1%; ">
                    <img src="<?= $foto ?>" style="width:100%; height: auto; cursor: pointer;">
                  </div>
                  <div class="col-md-10 col-12" style="background-color: #E3F2FD; margin-bottom: 1%;">
                    <h6>Docente:<br><strong><?= $nombre ?> <?= $apellido ?></strong></h6>
                    <p style="text-align: justify;"><?= $atencion ?></p>
                  </div>  
                </div><?php
              }?>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  </main><!-- End #main -->
  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <script type="text/javascript">
    $(document).ready(function() {
      if($('#atiende').val()!=''){
        $('#verFoto').modal('show')
      }
    });
    function ampliarImagen(imagen) {
        // Agrega la clase "imagen-ampliada" para aumentar el tamaño de la imagen
        imagen.classList.add("imagen-ampliada");
    }

    function restaurarImagen(imagen) {
        // Quita la clase "imagen-ampliada" para restaurar el tamaño original
        imagen.classList.remove("imagen-ampliada");
    }
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