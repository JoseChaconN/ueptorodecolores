<?php
session_start();
if(!isset($_SESSION["usuario"])) 
{
  header("location:index.php?vencio");
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'includes/PHPMailerMaster/src/Exception.php';
require 'includes/PHPMailerMaster/src/PHPMailer.php';
require 'includes/PHPMailerMaster/src/SMTP.php';
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("inicia.php");
include_once "conexion.php";
$link = Conectarse();
$idAlum=$_SESSION['idAlum'];
$tablaPeriodo=$_SESSION['periodoAlum'];
$result = mysqli_query($link,"SELECT A.cedula, A.nombre, A.apellido,A.Periodo, A.ruta, B.nombreGrado, C.correo as correoRep FROM alumcer A, grado".$tablaPeriodo." B, represe C WHERE A.idAlum ='$idAlum' and B.grado=A.grado and A.ced_rep=C.cedula "); 
while ($row = mysqli_fetch_array($result))
{   
      $cedula = $row['cedula'];
      $nombre = ($row['nombre']).' '.($row['apellido']);  
      $periodo = $row['Periodo'];
      $foto_alu = 'fotoalu/'.$row['ruta'];
      $nombreGrado=($row['nombreGrado']);
      $correoRep=$row['correoRep'];
}  ?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php"; ?>
  
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h3>Registro de Pago Periodo <?= ANOESCM ?></h3>
      </div>
    </div><!-- End Breadcrumbs -->

    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="col text-center">
            <img class='thumb from-group img-circle'  src="<?php echo $foto_alu; ?>" />     
          </div>
        </div>
        <div class="row" style="margin-top: 2%;">
          <div class="col">
            <form action="" method="post" role="form" onsubmit="return validacion()" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-3 col-12 form-group">
                  <label>Cedula</label>
                  <input type="text" readonly class="form-control" name="cedula" value="<?= $cedula ?>">
                </div>
                <div class="col-md-6 col-12 form-group">
                  <label>Estudiante</label>
                  <input type="text" readonly name="nombreAlum" class="form-control" value="<?= $nombre ?>">
                </div>
                <div class="col-md-3 col-12 form-group">
                  <label>Cursante</label>
                  <input type="text" readonly class="form-control" value="<?= $nombreGrado ?>">
                </div>
              </div>
              <div class="row" style="margin-top:2%;">
                <div class="col-md-2 col-12 form-group">
                  <label for="operacion" >Operación</label><br>
                  <select name='operacion' id='tip_opera' onchange="tipOpe()" class="form-control" data-style="btn-default" data-live-search="true" data-width="fit" >
                  <option value="0">Seleccione...</option>
                  <!--option value="D">Deposito</option-->
                  <option value="T">Transferencia (Bs)</option>
                  <option value="Pa">Pago Movil (Bs)</option>
                  </select>
                </div>
                <div class="col-md-2 col-12 form-group">
                  <label for="fec_depo" >Fecha de Operación</label>
                  <input type="date" required name="fec_depo" class="form-control" value="<?= $fechahoy ?>" >
                </div>
                <div class="col-md-2 col-12 form-group">
                  <label for="ref_depo" >Ref. o Confirmación</label>
                  <input type="text" required name="ref_depo" maxlength="20" class="form-control" >
                </div>
                <div class="col-md-3 col-12 form-group">
                  <label for="mon_depo" >Monto de Operación</label>
                  <input type="text" oninput="validarNumero(this)" placeholder='separar decimal con comas' title="por favor ingrese los miles sin punto Ejemplo: 1850,25 y separe los decimales con la coma ,"  required name="mon_depo" maxlength="7" class="form-control" >
                </div>
                <div class="col-md-3 col-12 form-group">
                  <label for="operacion" >Banco del Colegio</label><br>
                  <select name='banco_rec' id='ban_recep' class="form-control" data-style="btn-default" data-live-search="true" data-width="fit" >
                  <option value="0">Seleccione...</option>
                  <OPTION class="bolivar" style="display: none;" ><?= BANCO1M ?></OPTION><?php 
                  if(!empty(BANCO2M))
                  {?>
                    <OPTION class="bolivar" style="display: none;"><?= BANCO2M ?></OPTION><?php
                  }
                  if(!empty(BANCO3M))
                  {?>
                    <OPTION class="bolivar" style="display: none;"><?= BANCO3M ?></OPTION><?php
                  }
                  if(!empty(BANCO4M))
                  {?>
                    <OPTION class="divBanes" style="display: none;"><?= BANCO4M ?></OPTION><?php
                  }
                  if(!empty(BANCO5M))
                  {?>
                    <OPTION class="divZell" style="display: none;"><?= BANCO5M ?></OPTION><?php
                  }?>
                  </select>
                </div>
              </div>
              <div class="row" style="margin-top:2%;" >
                <div class="col-md-3 col-12 form-group" title="Nombre del banco desde donde transfiere">
                  <label>Banco del Titular</label><br>
                  <select name='banco_emi' id='banco_emi' class="form-control" data-style="btn-default" data-live-search="true" data-width="fit" >
                    <option value="0">Seleccione....</option><?php
                    $select2 = mysqli_query($link,"SELECT * FROM bancos WHERE cod_banco>0 ORDER BY nom_banco ASC ");
                    while($row = mysqli_fetch_array($select2))
                    {
                      $nom_banco=$row['nom_banco'];
                      echo '<option value="'.$nom_banco.'">'.utf8_encode($nom_banco)."</option>";
                    } ?>                               
                  </select>
                </div>
                <div class="col-md-3 col-12 form-group" title="Datos del titular de la cuenta que realiza la operación">
                  <label for="rif_titular" >C.I. o Rif. del titular</label>
                  <input type="text" required name="rif_titular"  maxlength="20" class="form-control" >
                </div>
                <div class="col-md-3 col-12 form-group" title="Datos del titular de la cuenta que realiza la operación">
                  <label for="nombre_titular" >Nombre del titular</label>
                  <input type="text" required name="nombre_titular" class="form-control" >
                </div>
                <div class="col-md-3 col-12 form-group" title="Datos del titular de la cuenta que realiza la operación">
                  <label for="nombre_titular" >Correo del titular</label>
                  <input type="email" required name="correoRep" value="<?= $correoRep ?>" class="form-control" >
                </div>
              </div>
              <div class="row" style="margin-top:2%;">
                <div class="col-md-12 col-12">
                  <label>Adjunte Capture de la operación electrónica</label>
                  <input type="file" multiple="true" name="archivos[]" id="BSbtninfo" class="filestyle">  
                </div>
              </div>
              <div class="row" style="margin-top:2%;">
                <div class="col-md-12 col-12 form-group">
                  <label for="mot_depo" >Motivo del pago realizado.</label>
                  <input type="text" required name="mot_depo" maxlength="58" class="form-control" >
                </div>
                <div class="col-md-12 col-12 form-group" style="margin-top:2%;">
                  <label for="comenta" >Comentario</label>
                  <input type="text" name="comenta" maxlength="100" class="form-control" >
                </div>
                <div class="col-md-12 col-12 form-group" style="margin-top:2%;">
                  <p >Nota: En caso de depósito el vaucher debe ser consignado en las oficinas de administración.</p>
                </div>
              </div>
              <div class="d-grid gap-2 col-6 mx-auto text-center" style="margin-top: 2%;">
                <button type="submit" value="1" name="enviar" class="btn btn-success btn-lg"><i class="ri-upload-cloud-line"></i> Enviar Pago</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <script type="text/javascript" src="assets/bootstrap_filestyle_2_1_0/src/bootstrap-filestyle.min.js"> </script>
  <script type="text/javascript">
    $(":file").filestyle(
      {
        btnClass : 'btn-info',
        text : 'Buscar Capture'
      });
    function tipOpe() {
      ope=$('#tip_opera').val()
      if(ope=='T' || ope=='Pa'){
        $(".bolivar"). css("display", "block")
        $(".divBanes"). css("display", "none")
        $(".divZell"). css("display", "none")
      }
      if(ope=='Ze'){
        $(".bolivar"). css("display", "none")
        $(".divBanes"). css("display", "none")
        $(".divZell"). css("display", "block")
      }
      if(ope=='Td'){
        $(".bolivar"). css("display", "none")
        $(".divBanes"). css("display", "block")
        $(".divZell"). css("display", "none")
      }
      $('#ban_recep').val(0)
    }
    function validarNumero(input) {
      // Elimina caracteres no permitidos y asegura el formato correcto
      input.value = input.value.replace(/[^0-9,]/g, '');

      // Verifica que solo haya un punto decimal
      var partes = input.value.split(',');
      if (partes.length > 2) {
          input.value = partes[0] + ',' + partes.slice(1).join('');
      }

      // Puedes personalizar el mensaje de error según tus necesidades
      var mensajeError = document.getElementById("mensajeError");
      if (input.value.length === 0) {
          mensajeError.textContent = "Por favor, ingresa un número o decimal.";
      } else {
          mensajeError.textContent = "";
      }
    }
    function validacion() 
    {
      tip_opera=$('#tip_opera').val()
      ban_recep=$('#ban_recep').val()
      banco_emi=$('#banco_emi').val()
            
      if (tip_opera==0 )
      {
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Seleccione tipo de Operación'
        })
        return false;
      }
      if (ban_recep==0 )
      {
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Seleccione Banco Colegio'
        })
        return false;
      }
      if (banco_emi==0 && tip_opera!='Ze' )
      {
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> Entendido',
          text: 'Seleccione Banco del Titular'
        })
        return false;
      }
      return true;
    }
    function formatear(event) {
    $(event.target).val(function(index, value) {
        return value.replace(/\D/g, "")
          .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
      });
    }
  </script><?php 
  if(isset($_POST['enviar']))
  {
    $ced_alu=$_POST["cedula"];
    $nombre=$_POST['nombreAlum'];
    $operacion=$_POST["operacion"];
    $nomOpe='';
    $bancoemisor=$_POST["banco_emi"];
    if($operacion=='D'){$nomOpe='Deposito';}
    if($operacion=='T'){$nomOpe='Transferencia';}
    if($operacion=='Pa'){$nomOpe='Pago Movil';}
    if($operacion=='Ze'){$nomOpe='Zelle'; $bancoemisor='N/A';}
    $fechadepo=$_POST["fec_depo"];
    $monto=str_replace(".","",$_POST['mon_depo']); $monto=str_replace(",",".",$monto);
    //$monto=$_POST['mon_depo'];
    $ref_depo=$_POST['ref_depo'];
    $banco=$_POST["banco_rec"];
    
    $rif_titular=$_POST['rif_titular'];
    $nombre_titular=$_POST['nombre_titular'];
    $correoRep=$_POST['correoRep'];
    $archivos = $_FILES['archivos'];
    $nombre_archivos = $archivos['name'];
    $ruta_archivos = $archivos['tmp_name'];
    $concepto=$_POST["mot_depo"];
    $comentario=$_POST["comenta"];
    if (isset($ced_alu) && !empty($ced_alu))
    {
      $pago_query = mysqli_query($link,"SELECT nrodeposito FROM pagos B WHERE ced_alu ='$ced_alu' and nrodeposito='$ref_depo' "); 
      if(mysqli_num_rows($pago_query) == 0)
      {
        mysqli_query($link,"INSERT INTO pagos (banco, fechadepo, monto, concepto, ced_alu, fecha, operacion, bancoemisor, comentario, linea,rif_titular,nombre_titular,nrodeposito) VALUES ('$banco', '$fechadepo', '$monto', '$concepto', '$ced_alu', '$fechahoy', '$operacion', '$bancoemisor', '$comentario', '1','$rif_titular','$nombre_titular','$ref_depo' )")or die ("NO GUARDO EL PAGO".mysqli_error($link)); ?>
        <script type="text/javascript">
          Swal.fire({
            icon: 'success',
            title: 'Gracias!',
            confirmButtonText:
            '<i class="fa fa-thumbs-up"></i> Entendido',
            text: 'Su Pago fue enviado exitosamente'
          })
        </script><?php 
        $operacion = ($operacion=='T') ? 'Transferencia' : 'Pago Movil' ;
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp1.s.ipzmarketing.com';
        $mail->SMTPAuth = true;
        $mail->Username = MAILUSER; 
        $mail->Password = CLAVEMAIL; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom(CORREOM,NKXS.' '.utf8_decode(EKKS));
        $mail->addAddress(SUCORREO,utf8_decode('Administración')); //Destinatario
        //$mail->addAddress('sisjch.tlf@gmail.com',utf8_decode('Administración'));
        $mail->isHTML(true);
        $mail->Subject = utf8_decode('Registro de Pago por Pagina Web Estudiante '.$nombre);
        $nombre = htmlspecialchars($nombre);
        $cedula =$ced_alu;
        try {
          $mensaje='
          <html>
            <body>
              <center>
              <table style="width: 40%; background-color: #E0E0E0;">
                <tr style="text-align: center;">
                  <th style="background-color: #283593; "><img src="https://'.DOMINIO.'/imagenes/logo.jpg" style="width: 30%; height: auto; text-align: center;">
                  </th>
                </tr> 
                <tr style="text-align: center;">
                  <td><h2>Pago Registrado en Pagina Web</h2></td>
                </tr>             
                <tr>
                  <td style="font-weight: bold; padding-left: 15px;">Estudiante: '.$apellido.' '.$nombre.'<br>Cedula: '.$cedula.'</td>
                </tr>
                <tr style="text-align: center;">
                  <td><h3>Datos de la Operacion</h3></td>
                </tr>
                <tr>
                  <td style="padding-left: 15px;">C.I./Rif del titular: '.$rif_titular.'<br>Nombre del titular: '.$nombre_titular.'<br>Banco Emisor: '.$bancoemisor.'<br>Monto: '.number_format($monto,2,',','.').'<br>Fecha.: '.date("d-m-Y", strtotime($fechadepo)).'</td>
                </tr>
                <tr>
                  <td style="padding-left: 15px;">Tipo de Operación: '.$nomOpe.'<br>Banco Receptor: '.$banco.'<br>Ref. ó confirmación: '.$ref_depo.'<br>Motivo: '.$concepto.'<br>Comentario: '.$comentario.'</td>
                </tr>
                <tr style="text-align: center;"><td><h4>Correo enviado desde la pagina<br>'.DOMINIO.'<br>si desea responder hagalo al:<br><h3>'.$correoRep.'</h3></h4>_____________________________</td>
                </tr>
                <tr style="text-align: center;">
                  <td><h4>'.NKXS.' '.EKKS.'<br>Telefono.: '.TELEMPM.'</h4></td>
                </tr>
              </table>
              </center>
            </body>
          </html>';
          $i = 0;
          $nombreArchivo='';
          if($nombre_archivos[0]!=''){
            foreach ($ruta_archivos as $rutas_archivos) {
              $mail->AddAttachment($rutas_archivos,$nombre_archivos[$i]);
              $nombreArchivo=$nombreArchivo.', '.$nombre_archivos[$i];
              $i++;
            }
          }
          
        $mail->Body = $mensaje;
        $mail->send();
        //echo "Mensaje Enviado";
        } catch (Exception $e) {
          echo "Error", $mail->ErrorInfo;
        }
        $mail->ClearAddresses();
      }else
      { ?>
        <script type="text/javascript">
          Swal.fire({
            icon: 'error',
            title: 'Disculpe!',
            confirmButtonText:
            '<i class="fa fa-thumbs-up"></i> Entendido',
            text: 'Datos del pago ya existen...'
          })
        </script><?php 
      }
    }
  } ?>
</body>

</html>