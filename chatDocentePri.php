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
$link = conectarse(); ?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php";
  $idDocente=desencriptar($_GET['id']);
  $idMateria=desencriptar($_GET['idMat']);
  $idAlum=$_SESSION['idAlum'];

  $docente_query=mysqli_query($link,"SELECT A.ruta,A.correo,A.nombre,A.apellido FROM alumcer A WHERE A.idAlum='$idDocente' "); 
  $row=mysqli_fetch_array($docente_query);
  $ruta=$row['ruta'];
  $fotoAlum=$_SESSION['fotoAlum'];
  $foto = (empty($ruta)) ? 'imagenes/usuario.png' : 'docentes/fotodoc/'.$ruta ;
  $fotoAlum = (empty($fotoAlum)) ? 'imagenes/usuario.png' : 'fotoalu/'.$fotoAlum ;
  $nombreDoc=$row['nombre'].' '.$row['apellido']; ?>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
  <main id="main">
    <!-- ======= TITULO ======= -->
    <style type="text/css">
      .scroll
      {
        width:100%;
        height:300px;
        overflow: auto;
        border:1px solid #000000; /* Solo lo puse para que se vea el cuadro*/
      }
      .thumb
      {
        width: 100px;
        height: 100px;
      }
    </style>
    <section id="about" class="about" style="background-color:#E0E0E0; margin-top: 2%;">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="row">
            <div class="col-md-3 offset-md-3 col-xs-6 form-group text-center">
              <img src="<?= $foto.'?'.time().mt_rand(0, 99999) ?>" class="thumb" />
            </div>
            <div class="col-md-3 col-xs-6 form-group text-center">
              <img src="<?= $fotoAlum.'?'.time().mt_rand(0, 99999) ?>" class="thumb" />
            </div>
          </div>
          
          <?php 
          $chat_query=mysqli_query($link,"SELECT * FROM chat WHERE idAlum='$idAlum' and id_docente='$idDocente' ORDER BY fecha_chat DESC"); 
          ?>
          <form role="form" method="POST" enctype="multipart/form-data" action="" >
            <div class="row" style="margin-top: 2%;">
              <div class="col-md-12 form-group">
                <textarea class="form-control" rows="2" id="mensaje" placeholder="Escriba aqui su mensaje para <?= $nombreDoc ?>"></textarea>
              </div>
            </div>
            <div class="col-md-12 mx-auto" style="margin-top: 2%;">
              <div class="row">
                <div class="col-md-6">
                  <button type="button" onclick="javascript:window.close();opener.window.focus();" style="width:100%;" class="btn btn-warning btn-lg"><i class="fas fa-door-closed"></i> Salir</button>    
                </div>
                <div class="col-md-6">
                  <button type="button" onclick="enviar()" style="width:100%;" class="btn btn-success btn-lg"><i class="ri-upload-cloud-line"></i> Enviar</button>
                </div>
              </div>
            </div>
            <input type="hidden" id="idDoc" value="<?= encriptar($idDocente) ?>">
            <input type="hidden" id="idAlu" value="<?= encriptar($idAlum) ?>">
            <input type="hidden" id="idMat" value="<?= encriptar('5101') ?>">
          </form>
          <div class="col-md-12" style="background-color:#1266F1; color: white; text-align: center; margin-top: 1%;"><h3>Historial de mensajes con <?= $nombreDoc ?></h3></div>
          <div class="col-md-12 scroll" style="margin-top:1%; ">
            <table><?php
              while($row=mysqli_fetch_array($chat_query)) 
              { 
                $id_chat=$row['id_chat'];
                $visto=$row['visto'];
                $envia=$row['envia'];
                if($visto=='2' && $envia=='1')
                {
                  $color='style="background-color: #E6EE9C;"';
                  $avisa='(MENSAJE NUEVO)';
                }else
                {
                  $color='';
                  $avisa='';
                }
                $colorTd = ($envia=='1') ? 'style="background-color:#B39DDB;"' : 'style="background-color:#B2DFDB;"' ;
                $quien = ($envia=='1') ? $nombreDoc : $_SESSION['nomuser'] ;
               ?>
                <tr >
                  <td <?= $colorTd ?>><?= date("d-m-Y H:i", strtotime($row['fecha_chat'])).' / '.$quien.' / <span style="background-color:#76FF03; "> '.$avisa.'</span>' ; ?></td>
                </tr>
                <tr <?= $color ?>>
                  <td><?= $row['texto'] ?></td>
                </tr>
                <tr  style="border:1px solid black;">
                  <td></td>
                </tr>
                <?php
                $_SESSION['msjHay'] =0;
                mysqli_query($link,"UPDATE chat SET visto='1' WHERE id_chat='$id_chat' and envia='1' ") or die ("NO ACTUALIZO ".mysqli_error());
              } ?>
            </table>
          </div>
        </div>
      </div>
    </section>
    

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <script type="text/javascript">
    jQuery(document).ready(function($) {
      opener.document.location.reload();
    });
    function enviar() {
      msj=$('#mensaje').val()
      idD=$('#idDoc').val()
      idA=$('#idAlu').val()
      idM=$('#idMat').val()
      if(msj=='')
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
        $.post('enviaChat.php',{'idDoc':idD,'idAlu':idA,'idMate':idM,'mensaje':msj},function(data)
        {
          if(data.isSuccessful)
          {
            window.parent.location.reload();
          }
        }, 'json');
      }
    }
  </script>

</body>

</html>