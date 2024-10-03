<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'includes/PHPMailerMaster/src/Exception.php';
require 'includes/PHPMailerMaster/src/PHPMailer.php';
require 'includes/PHPMailerMaster/src/SMTP.php';
?>
<!DOCTYPE html>
<html lang="es"><?php 
  include_once("inicia.php");
  include_once "header.php";?>
  <main id="main">
    <!-- ======= Breadcrumbs ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Como llegar a nuestra Institución</h2>
        <p>Le damos la mas cordial bienvenida de parte de toda la gran familia UEP <?= EKKS ?></p>
      </div>
    </div><!-- End Breadcrumbs -->
    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
      <div data-aos="fade-up">
        <iframe style="border:0; width: 100%; height: 350px;" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3927.0485846713827!2d-67.51457747434544!3d10.176706439141684!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e802312de3cccfd%3A0x72778dc18df601ef!2sEl%20Toro%20De%20Colores!5e0!3m2!1ses!2sve!4v1710897672759!5m2!1ses!2sve" allowfullscreen="" ></iframe>
      </div>
      <div class="container" data-aos="fade-up">
        <div class="row mt-5">
          <div class="col-lg-4">
            <div class="info">
              <div class="address">
                <i class="bi bi-geo-alt"></i>
                <h4>Dirección:</h4>
                <p><?= DIRECCM .' '. CIUDADM.' - '.ESTADOM ?></p>
              </div>
              <div class="email">
                <i class="bi bi-envelope"></i>
                <h4 class="oculta">Email:</h4>
                <p ><?= SUCORREO ?></p>
              </div>
              <div class="phone">
                <i class="bi bi-phone"></i>
                <h4>Telefono:</h4>
                <p><?= TELEMPM ?></p>
              </div>
            </div>
          </div>
          <div class="col-lg-8 mt-5 mt-lg-0">
            <form action="" method="post" role="form" class="php-email-form">
              <div class="row">
                <div class="col-md-6 form-group">
                  <input type="text" name="nombre" class="form-control" id="name" placeholder="Su Nombre" required>
                </div>
                <div class="col-md-6 form-group mt-3 mt-md-0">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Su Email" required>
                </div>
              </div>
              <div class="form-group mt-3">
                <input type="text" class="form-control" name="asunto" id="subject" placeholder="Asunto" required>
              </div>
              <div class="form-group mt-3">
                <textarea class="form-control" name="mensaje" rows="5" placeholder="Mensaje" required></textarea>
              </div>
              <div class="my-3">
                <div class="loading">Cargando</div>
                <div class="error-message" id="errado">Disculpe, su mensaje no pudo enviarse!</div>
                <div class="sent-message" id="enviado">Su mensaje a sido enviado Gracias!</div>
              </div>
              <div class="form-group col-md-4 offset-md-4 mt-3 text-center">
                <h3 id="suma"></h3>
                <input type="text" class="form-control" name="respuesta" placeholder="Su respuesta" required>
              </div>
              <div class="text-center"><button type="submit" name="enviar">Enviar Mensaje</button></div>
              <input type="hidden" id="n1" name="n1">
              <input type="hidden" id="n2" name="n2">
            </form>
          </div>
        </div>
      </div>
    </section><!-- End Contact Section -->
  </main><!-- End #main -->
  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <script type="text/javascript">
    jQuery(document).ready(function($) 
    {
      calcula()
    });
    function calcula() 
    {
      hoy=new Date();
      n1=hoy.getMinutes();
      n2=hoy.getSeconds();
      if(n1>n2)
      {calcu=n1+' - '+n2+' = ?';}else{calcu=n1+' + '+n2+' = ?';}
      
      document.getElementById("suma").innerHTML = calcu;
      $('#n1').val(n1)
      $('#n2').val(n2)
    }
  </script><?php 
  if (isset($_POST['enviar'])) 
  {
    $respuesta=$_POST['respuesta'];
    $n1=$_POST['n1'];
    $n2=$_POST['n2'];
    if ($n1>$n2) 
    {
      $total=($n1-$n2);
    }else
    {
      $total=($n1+$n2);
    }
    if($respuesta==$total)
    {
      $nombre=$_POST['nombre']; 
      $email=$_POST['email']; 
      $asunto=$_POST['asunto']; 
      $mimensaje=$_POST['mensaje']; 
      $mail = new PHPMailer(true);
      $mail->SMTPDebug = 0;
      $mail->isSMTP();
      $mail->Host = 'smtp1.s.ipzmarketing.com';
      $mail->SMTPAuth = true;
      $mail->Username = MAILUSER; 
      $mail->Password = CLAVEMAIL; 
      $mail->SMTPSecure = 'tls';
      $mail->Port = 587;
      $mail->setFrom(CORREOM,NKXS.' '.EKKS);
      $mail->addAddress(SUCORREO,'Correo principal '.NKXS.' '.EKKS); //Destinatario
      //$mail->addAddress('sisjch.tlf@gmail.com','Pagina '.EKKS);
      $mail->isHTML(true);
      $mail->Subject = 'Datos de acceso a pagina web';
      $nombre = htmlspecialchars($nombre);
      try {
        $ekks=utf8_decode(EKKS);
        $mensaje='
          <html>
            <body>
              <center>
              <table style="width: 40%; background-color: #E0E0E0;">
                <tr style="text-align: center;">
                  <th style="background-color: #283593; "><img src="https://'.DOMINIO.'/imagenes/logo.png" style="width: 30%; height: auto; text-align: center;">
                  </th>
                </tr> 
                <tr style="text-align: center;">
                  <td><h2>Contacto Pagina Web</h2></td>
                </tr>             
                <tr>
                  <td style="font-weight: bold; padding-left: 15px;">Nombre: '.$nombre.'</td>
                </tr>
                <tr>
                  <td style="font-weight: bold; padding-left: 15px;">Responder al correo: '.$email.'</td>
                </tr>
                <tr>
                  <td style="font-weight: bold; padding-left: 15px;">Asunto: '.$asunto.'<br><br></td>
                </tr>
                <tr style="text-align: justify;">
                  <td style=" padding-left: 15px;">'.$mimensaje.'<br><br></td>
                </tr>
                <tr style="text-align: center;"><td><h4>Esta correo fue enviado desde la pagina<br>'.DOMINIO.'<br> por favor responder al correo del solicitante: '.$email.' </h4>___________________________________________________________</td>
                </tr>
                <tr style="text-align: center;">
                  <td><h4>'.NKXS.' '.EKKS.'<br>Telefono.: '.TELEMPM.'</h4></td>
                </tr>
                </table>
                </center>
            </body>
          </html>';
          $i = 0;
          
        $mail->Body = $mensaje;
        $mail->send();
        //echo "Mensaje Enviado";
      } catch (Exception $e) {
        echo "Error", $mail->ErrorInfo;
      }
      $enviado=1;
      $mail->ClearAddresses(); ?>
      <script type="text/javascript">
        document.getElementById('enviado').style.display = 'block';
        document.getElementById('errado').style.display = 'none';
      </script><?php
    }else
    {?>
      <script type="text/javascript">
        document.getElementById('enviado').style.display = 'none';
        document.getElementById('errado').style.display = 'block';
      </script><?php
    }
  } ?>
  

</body>

</html>