<?php
include_once "../include/header.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../../includes/PHPMailerMaster/src/Exception.php';
require '../../../includes/PHPMailerMaster/src/PHPMailer.php';
require '../../../includes/PHPMailerMaster/src/SMTP.php';
$link=Conectarse();
$fechaYa = date("Y-m-d H:i:s");
$enviado_query=mysqli_query($link,"SELECT fechaEnvio, enviados FROM email_enviados WHERE idEmail='1'");
while($row=mysqli_fetch_array($enviado_query))
{
    $fechaEnvio=$row['fechaEnvio'];
    $enviados=$row['enviados'];
}
if(substr($fechaEnvio, 0,10)==substr($fechaYa, 0,10))
{
    $f1=strtotime($fechaYa);
    $f2=strtotime($fechaEnvio);
    $intervalo=(($f1-$f2)/60);
    if($intervalo>70)
    {
        $enviados=0;
    }
} else 
{  
    $enviados=0;
    $intervalo=0;
}
$fechaUltEnvio=date("d-m-Y H:i", strtotime($fechaEnvio));

if(isset($_POST["enviar"]) && $_POST["enviar"]=='1')
{
    $gradoEnvia=$_POST['gradoEnvia'];
    $seccionEnvia=$_POST['seccionEnvia'];
    $periodoAct=$_POST['periodoEnvia'];
    $aquienes=$_POST['aquienes'];
    
    $periodo_query1=mysqli_query($link,"SELECT tablaPeriodo FROM periodos where nombre_periodo='$periodoAct'"); 
    while($row=mysqli_fetch_array($periodo_query1))
    {
        $tablaPeriodo=trim($row['tablaPeriodo']);
    }
    if( ($enviados)<150 )
    {
        //include("../../../inicia.php");
        $operat = $_POST['operat'];
        $dia=substr($operat, 0,2);
        $mes=substr($operat, 3,2);
        $ano=substr($operat, 6,4);
        $operat=$mes.'/'.$dia.'/'.$ano;
        if ($gradoEnvia<61) {
            if ($aquienes=='1') {
                $consulta_query = mysqli_query($link,"SELECT A.desc1,A.desc2,A.desc3,A.desc4,A.desc5,A.desc6,A.desc7,A.desc8,A.desc9,A.desc10,A.desc11,A.desc12,A.desc13,A.suma_a_pagado,A.retiraPagos, A.idAlumno, B.cedula as ced_alu, B.apellido, B.nombre, C.cedula as ced_rep, C.correo as mai_rep, C.representante FROM notaprimaria".$tablaPeriodo." A, alumcer B, represe C WHERE A.statusAlum=1 and A.grado='$gradoEnvia' and A.idSeccion='$seccionEnvia' and A.idAlumno=B.idAlum and B.ced_rep=C.cedula and C.correo is not NULL ORDER BY B.apellido ");
            }else{
                $consulta_query = mysqli_query($link,"SELECT A.desc1,A.desc2,A.desc3,A.desc4,A.desc5,A.desc6,A.desc7,A.desc8,A.desc9,A.desc10,A.desc11,A.desc12,A.desc13,A.suma_a_pagado,A.retiraPagos, A.idAlumno, B.cedula as ced_alu, B.apellido, B.nombre, C.cedula as ced_rep, C.correo as mai_rep, C.representante FROM notaprimaria".$tablaPeriodo." A, alumcer B, represe C WHERE (A.convenio is NULL or A.convenio='' ) and A.statusAlum=1 and A.grado='$gradoEnvia' and A.idSeccion='$seccionEnvia' and A.idAlumno=B.idAlum and B.ced_rep=C.cedula and C.correo is not NULL ORDER BY B.apellido ");
            }
        }else
        {
            if ($aquienes=='1') {
                $consulta_query = mysqli_query($link,"SELECT A.desc1,A.desc2,A.desc3,A.desc4,A.desc5,A.desc6,A.desc7,A.desc8,A.desc9,A.desc10,A.desc11,A.desc12,A.desc13,A.suma_a_pagado,A.retiraPagos, A.idAlumno, B.cedula as ced_alu, B.apellido, B.nombre, C.cedula as ced_rep, C.correo as mai_rep, C.representante FROM matri".$tablaPeriodo." A, alumcer B, represe C WHERE A.statusAlum=1 and A.grado='$gradoEnvia' and A.idSeccion='$seccionEnvia' and A.idAlumno=B.idAlum and B.ced_rep=C.cedula and C.correo is not NULL ORDER BY B.apellido "); 
            }else
            {
                $consulta_query = mysqli_query($link,"SELECT A.desc1,A.desc2,A.desc3,A.desc4,A.desc5,A.desc6,A.desc7,A.desc8,A.desc9,A.desc10,A.desc11,A.desc12,A.desc13,A.suma_a_pagado,A.retiraPagos, A.idAlumno, B.cedula as ced_alu, B.apellido, B.nombre, C.cedula as ced_rep, C.correo as mai_rep, C.representante FROM matri".$tablaPeriodo." A, alumcer B, represe C WHERE (A.convenio is NULL or A.convenio='' ) and A.statusAlum=1 and A.grado='$gradoEnvia' and A.idSeccion='$seccionEnvia' and A.idAlumno=B.idAlum and B.ced_rep=C.cedula and C.correo is not NULL ORDER BY B.apellido "); 
            }
        }
        $enviando=0;
        $vanc=0;
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
        //$mail->addAddress($correo,$representante); //Destinatario
        $mail->isHTML(true);
        $mail->Subject =  utf8_decode("NOTIFICACION DE COBRANZA");
        while($row=mysqli_fetch_array($consulta_query))
        {
            $idAlumno=$row['idAlumno'];
            $cedula = $row['ced_alu'];
            $alumno=$row['nombre'].' '.$row['apellido'];
            $mai_rep = htmlspecialchars($row['mai_rep']);
            $nom_rep=$row["representante"];
            $ced_rep=$row["ced_rep"];
            $suma_a_pagado=$row['suma_a_pagado'];
            $retiraPagos=$row['retiraPagos'];
            if($retiraPagos>'1990-01-01'){$fechaVence=$retiraPagos;}else{$fechaVence=$fechaHoy;}
            for ($i=1; $i <14 ; $i++) { 
                ${'desc'.$i} = $row['desc'.$i];
            }
            $montos_query = mysqli_query($link,"SELECT monto,fecha_vence,mes,insc FROM montos".$tablaPeriodo." WHERE id_grado ='$gradoEnvia' "); 
            $deudatotal=0; $meses=0; $morosida=0; $exonera=0;
            while ($row = mysqli_fetch_array($montos_query))
            {
                $meses++;
                $deudatotal=$deudatotal+($row['monto']-${'desc'.$meses});
                ${'insc'.$meses} = $row['insc'];
                ${'mes'.$meses} = $row['mes'];
                ${'f_vence'.$meses} = $row['fecha_vence'];
                ${'monto'.$meses} = $row['monto']-${'desc'.$meses};
                if($row['fecha_vence']<$fechaVence)
                {
                    $morosida=$morosida+($row['monto']-${'desc'.$meses});
                }
            }
            $pagos_query = mysqli_query($link,"SELECT A.*,D.afecta FROM pagos".$tablaPeriodo." A, conceptos D WHERE A.idAlum = '$idAlumno' and A.id_concepto=D.id ORDER BY A.id ");
            $pagado=$suma_a_pagado; $pagos=0;
            while ($row = mysqli_fetch_array($pagos_query))
            {
                if($row['statusPago']=='1' and $row['afecta']=='S' )
                {
                    $pagado=$pagado+$row['montoDolar'];
                    $pagos++;
                }
            }
            $morosida=$morosida-$pagado;

            $msjCobro='La Administración de la U.E.P. '.EKKS.', luego de saludarle pasa a informar su situación ante este Departamento, no sin antes recordar el pago oportuno de las mensualidades, mismo que debe realizar durante los cinco (5) primeros días de cada mes, por adelantado.<br><br> A la fecha usted presenta un monto vencido de '.number_format($morosida,2,",",".").' $, para solventar dicha situación, debe pasar por esta Administración en un lapso de 24 horas a fin de solventar y/o aclarar cualquier duda.<br><br> ';
            
            try {
                $mail->addAddress($mai_rep,$nombre); //Destinatario
                
                $mensaje='
                <html>
                    <body>
                      <center>
                      <table style="width: 40%; background-color: #E0E0E0;">
                        <tr style="text-align: center;">
                          <th style="background-color: #283593; "><img src="https://'.DOMINIO.'/imagenes/logo.png" style="width: 30%; height: auto; text-align: center;">
                          </th>
                        </tr> 
                        <tr>
                          <td style="padding-left: 15px;">Estimado(a) representante<br> '.$nom_rep.'<br>
                          Estudiante:<br>'.$alumno.'<br>Cedula: '.$cedula.'<br><br></td>
                        </tr>
                        <tr style="text-align: center;">
                          <td><h2>NOTIFICACION DE COBRANZA</h2></td>
                        </tr>             
                        <tr style="text-align: justify;"><td style="padding: 10px;"><h4>'.$msjCobro.'</h4></td>
                        </tr>
                        <tr style="text-align: center;">
                          <td><h4>Atentamente</h4></td>
                        </tr>
                        <tr style="text-align: center;">
                          <td><h4>Departamento de Cobranzas</h4></td>
                        </tr>
                        <tr style="text-align: center;">
                          <td><h4>NOTA: si al momento de recibir este mensaje usted ya realizó el pago total de la deuda, haga caso omiso a esta comunicación.</h4></td>
                        </tr>
                        <tr style="text-align: center;">
                            <td align="center" valign="middle" style="color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; letter-spacing:-.5px; line-height:150%; padding-top:15px; padding-right:30px; padding-bottom:15px; padding-left:30px;">

                                <a href="mailto:'.SUCORREO.'?subject=Respuesta%20a%20la%20Notificacion%20de%20Cobranza%20'.$alumno.'" style="display:inline-block; padding:10px 20px; background-color:#007BFF; color:#ffffff; text-decoration:none; border-radius:5px;">Responder Aqui</a>
                            </td>
                        </tr>
                        <tr style="text-align: center;"><td><h4>Favor enviar su respuesta a nuestro correo oficial:<br>'.SUCORREO.'<br> o comunicarse directamente a nuestro teléfono<br>'.TELEMPM.'<br></h4>_____________________________</td>
                        </tr>
                        <tr style="text-align: center;">
                          <td><h4>'.NKXS.' '.EKKS.'<br></h4></td>
                        </tr>
                        </table>
                        </center>
                    </body>
                </html>';
                $mail->Body = $mensaje;
                if($morosida>0 )
                {
                    $mail->send();
                    $enviando++;
                }
                //$enviado++;
            } catch (Exception $e) 
            {
                echo "Error", $mail->ErrorInfo;
            }
            
            $mail->ClearAddresses();
            if (($enviando+$enviados)>=150) {
                break;
            }
        }
        $enviado=$enviando+$enviados;
        mysqli_query($link,"UPDATE email_enviados SET fechaEnvio='$fechaYa', enviados='$enviado' WHERE idEmail = '1'") or die ("CORREOS ENVIADOS".mysqli_error());
        echo "<script type='text/javascript'>                                
                window.location='correo-morosos.php?envio=$enviado&salen=$enviando';
              </script>";
    } else
    { ?>
        <script type="text/javascript">
            window.location='correo-morosos.php?msj=2';
        </script><?php
    }
    
    echo '<div class="container">
         <div class="row">
             <div class="col-md-12">
                 <h2><div class="alert alert-danger text-center" role="alert">Correos enviados '. $enviado. ' </div></h2>
             </div>
         </div>
     </div>';
}else{
    $periodo_query=mysqli_query($link,"SELECT tablaPeriodo FROM periodos where activoPeriodo='1' and adultos='N' "); 
    while($row=mysqli_fetch_array($periodo_query))
    {
        $tablaPeriodo=trim($row['tablaPeriodo']);
    }
} ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Envío de Correo a Morosos</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <!--h6 class="m-0 font-weight-bold text-primary">Archivos</h6-->
        </div>
        <div class="card-body">
            <form role="form" method="POST" onsubmit="return validacion()" enctype="multipart/form-data" action="">
                <div class="col-md-12 text-center">
                    <h3>Correos enviados la ultima hora: (<?= $enviados ?>)</h3><?php
                    if($intervalo<71 && $enviados>=150)
                    { ?>
                        <h3 style="background-color: #F5B7B1;">Disculpe el limite de correos permitidos por hora ya fue alcanzado por favor espere <?= number_format(70-$intervalo,0,'.',',').' min. para poder enviar correos nuevamente' ?></h3><?php
                    } ?>
                    <label class="subtituloficha">Fecha de Operativo</label>
                    <div class="form-group col-md-6 offset-md-3">
                        <input type="date" class="form-control" name="operat" title="Ingrese Fecha de Operativo de cobranza" placeholder="dd/mm/aaaa" required="" value="<?= $fechaHoy ?>">
                    </div>
                    <div class="form-group col-md-6 offset-md-3">
                        <label><h3>Periodo</h3></label><br>
                        <select name="periodoEnvia" id="periodoEnvia" onchange="validacion()" class="form-control" data-style="btn-default" data-live-search="true" data-width="fit"  >
                            <option value="">Seleccione....</option><?php
                            $periodo_query = mysqli_query($link,"SELECT nombre_periodo,activoPeriodo FROM periodos WHERE adultos='N' ORDER BY tablaPeriodo ");
                            while($row = mysqli_fetch_array($periodo_query))
                            {
                                $nombre_periodo=$row['nombre_periodo'];
                                $activoPer=$row['activoPeriodo'];
                                $selected = ($activoPer=='1') ? 'selected' : '' ;
                                echo '<option '.$selected.' value="'.$nombre_periodo.'">'.$nombre_periodo."</option>";
                            } ?>                                   
                        </select>
                    </div>
                    <div class="form-group col-md-6 offset-md-3" id="divGrado">
                        <label><h3>Grado o Año</h3></label><br>
                        <select name="gradoEnvia" id="gradoEnvia" onchange="validacion()" class="form-control" data-style="btn-default" data-live-search="true" data-width="fit"  >
                            <option value="">Seleccione....</option><?php
                            $grados_query = mysqli_query($link,"SELECT grado,nombreGrado FROM grado".$tablaPeriodo." WHERE grado <70 ORDER BY grado ");
                            while($row = mysqli_fetch_array($grados_query))
                            {
                                $nom_gradsd=($row['nombreGrado']);
                                $id_gradsd=$row['grado'];
                                echo '<option value="'.$id_gradsd.'">'.$nom_gradsd."</option>";
                            } ?>                                   
                        </select>
                    </div>
                    <div class="form-group col-md-6 offset-md-3" id="divSecci">
                        <label><h3>Sección</h3></label><br>
                        <select name="seccionEnvia" id="seccionEnvia" onchange="validacion()" class="form-control" data-style="btn-default" data-live-search="true" data-width="fit"  >
                            <option value="">Seleccione....</option><?php
                            $result1 = mysqli_query($link,"SELECT * FROM secciones ORDER BY id ");
                            while($row1 = mysqli_fetch_array($result1))
                            {
                                $nom_secdsd=utf8_encode($row1['nombre']);
                                $id_secdsd=$row1['id'];
                                echo '<option value="'.$id_secdsd.'">'.$nom_secdsd."</option>";
                            }?>                                
                        </select>   
                    </div>
                    <div class="form-group col-md-6 offset-md-3" id="divSecci">
                        <label><h3>Dirigido a:</h3></label><br>
                        <select name="aquienes" id="aquienes" class="form-control" data-style="btn-default" data-live-search="true" data-width="fit"  >
                            <option value="1">Todos los morosos del grado</option>
                            <option value="2">Omitir morosos con convenio</option>
                        </select>   
                    </div>
                </div>
                <div class="form-group col-md-6 offset-md-3">
                    <button type="submit" style="width: 100%;" name="enviar" value="1" class="btn btn-success" onclick="mostrar()" <?php if($intervalo<71 && $enviados>=150){ echo "disabled";} ?> ><i class="fas fa-cloud-upload-alt fa-sm" ></i> Enviar Correos</button>
                </div>                  
                <input type="hidden" id="intervalo" value="<?= number_format(70-$intervalo,0,'.',',').' Min.' ?>">
                <input type="hidden" id="envio" value="<?= $_GET['salen'] ?>">
            </form> 
            <div id="mensaje" class="col-md-12 text-center"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) 
    {
        if (screen.width<500) {
            $('#page-top').removeClass("sidebar-toggled");
            $('#accordionSidebar').addClass("navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled");
        }
        $('.collapse-item').removeClass("active");
        $('.collapse').removeClass("show");
        if (screen.width>1024) {
            $('#procesos').addClass("show");
        }
        $('#correoMorosos').addClass("active");
    });
    function validacion() 
    {
        var grado = document.getElementById("gradoEnvia");
        var secci = document.getElementById("seccionEnvia");
        if (grado.value.length == 0 )
        { 
          document.getElementById("divGrado").style.border="thin dotted #FE0101";
          return false;
        } else { document.getElementById("divGrado").style.border=""; }
        if (secci.value.length == 0 )
        { 
          document.getElementById("divSecci").style.border="thin dotted #FE0101";
          return false;
        } else { document.getElementById("divSecci").style.border=""; }
    }
    function mostrar()
    {
        var grado = document.getElementById("gradoEnvia");
        var secci = document.getElementById("seccionEnvia");
        if (grado.value.length > 0 && secci.value.length > 0 )
        {
            $("#mensaje").html("<img src='../img/enviar-loading.gif'><br><h2>Enviando correos ESPERE....</h2>");
        }
    }
</script>
<?php
if(isset($_GET['msj']) && $_GET['msj']=='2')
{?>
    <script type="text/javascript">
        //alert('aaaa')
        Swal.fire({
            icon: 'info',
            title: 'Limite alcanzado!',
            confirmButtonText:
            '<i class="fa fa-thumbs-up"></i> Entendido',
            text: 'Disculpe el limite de correos permitidos por hora ya fue alcanzado por favor intente en '+$('#intervalo').val()+' Aproximadamente'
        })
    </script><?php
}
if(isset($_GET['envio']) )
{?>
    <script type="text/javascript">
        envi=$('#envio').val()
        if(envi>0){
            Swal.fire({
                icon: 'success',
                title: 'Excelente!',
                confirmButtonText:
                '<i class="fa fa-thumbs-up"></i> Entendido',
                text: 'Los ('+envi+') correos fueron enviados exitosamente'
            })
        }else{
            Swal.fire({
                icon: 'info',
                title: 'Información!',
                confirmButtonText:
                '<i class="fa fa-thumbs-up"></i> Entendido',
                text: 'No hay representantes morosos en este envío'
            })
        }
    </script><?php
}
include_once "../include/footer.php"; 
mysqli_free_result($enviado_query);
mysqli_free_result($periodo_query1);
mysqli_free_result($consulta_query);
mysqli_free_result($montos_query);
mysqli_free_result($pagos_query);
mysqli_free_result($periodo_query);
mysqli_free_result($grados_query);
mysqli_free_result($result1);
?>
           