<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<2 ) 
{
  header("location:../index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
$docente=$_SESSION["usuario"];
include_once("../inicia.php");
include_once("../conexion.php");
$link = conectarse(); ?>
<!DOCTYPE html>
<html lang="es"><?php
  include_once "header.php";
  $idAlum=desencriptar($_GET['id']);
  $grado=desencriptar($_GET['grado']);
  $seccion=$_GET['seccion'];
  $nomGra=$_GET['nomGra'];
  $nomSec=$_GET['nomSec'];
  if ($lapsoActivo==1) {
    $fecha_query=mysqli_query($link,"SELECT iniciaMaestro,terminaMaestro FROM preinscripcion WHERE id=3 ");  
  }
  if ($lapsoActivo==2) {
    $fecha_query=mysqli_query($link,"SELECT iniciaMaestro,terminaMaestro FROM preinscripcion WHERE id=4 ");  
  }
  if ($lapsoActivo==3) {
    $fecha_query=mysqli_query($link,"SELECT iniciaMaestro,terminaMaestro FROM preinscripcion WHERE id=5 ");  
  }
  while($row=mysqli_fetch_array($fecha_query)) 
  {
    $iniciaCarga=$row['iniciaMaestro'];
    $terminaCarga=$row['terminaMaestro'];
  }
  $permite = ($iniciaCarga<=$fechahoy && $terminaCarga>=$fechahoy ) ? 'S' : 'N' ;
  $alumnos_query=mysqli_query($link,"SELECT cedula,apellido,nombre,ruta FROM alumcer WHERE idAlum='$idAlum'");
  while($row=mysqli_fetch_array($alumnos_query)) 
  { 
    $ced_alu=$row['cedula'];
    $nombre=$row['nombre'];
    $apellido=$row['apellido'];
    $ruta=$row['ruta'];
  }
  $dicta_query = mysqli_query($link,"SELECT * FROM trgsmp".$tablaPeriodo." WHERE cod_grado='$grado' and cod_seccion='$seccion' ORDER BY cod_materia ");
  $van=0;
  while ($row = mysqli_fetch_array($dicta_query))
  {
    $van++;
    ${'dicta'.$van}=substr($row['cod_materia'],2,2);
    ${'cedProf'.$van}=$row['ced_prof'];
  }

  $grado_query = mysqli_query($link,"SELECT * FROM grado".$tablaPeriodo." WHERE grado='$grado' "); 
  while ($row = mysqli_fetch_array($grado_query))
  {
    $nomGra=$row['nombreGrado'];
    for ($i=1; $i <=10 ; $i++) { 
      ${'mate'.$i}=$row['mate'.$i];
    }
  }
  $boleta_query=mysqli_query($link,"SELECT * FROM boletas".$tablaPeriodo." WHERE grado='$grado' and seccion='$seccion' AND lapso = '$lapsoActivo'");
  while($row=mysqli_fetch_array($boleta_query))
  {
    for ($i=1; $i <=7; $i++) 
    { 
      ${'mate1'.$i} = $row['mate1'.$i];
      ${'mate2'.$i} = $row['mate2'.$i];
      ${'mate3'.$i} = $row['mate3'.$i];
      ${'mate4'.$i} = $row['mate4'.$i];
      ${'mate5'.$i} = $row['mate5'.$i];
      ${'mate6'.$i} = $row['mate6'.$i];
      ${'mate7'.$i} = $row['mate7'.$i];
      ${'mate8'.$i} = $row['mate8'.$i];
      ${'mate9'.$i} = $row['mate9'.$i];
      ${'mate10'.$i} = $row['mate10'.$i];
    }
  }
  $notas_query=mysqli_query($link,"SELECT * FROM notaprimaria".$tablaPeriodo." WHERE idAlumno='$idAlum'");
  if ($lapsoActivo == 1){$lp='1';}
  if ($lapsoActivo == 2){$lp='2';}
  if ($lapsoActivo == 3){$lp='3';}
  if(mysqli_num_rows($notas_query) > 0)
  {
    while($row=mysqli_fetch_array($notas_query))
    {
      $dias_habiles=$row["dias_habiles".$lp];
      $asistencia=$row["asistencia".$lp];
      $observacion=$row["observacion".$lp];
      for ($i=1; $i <=7; $i++) 
      { 
        ${'notap1'.$lp.$i} = $row['notap1'.$lp.$i];
        ${'notap2'.$lp.$i} = $row['notap2'.$lp.$i];
        ${'notap3'.$lp.$i} = $row['notap3'.$lp.$i];
        ${'notap4'.$lp.$i} = $row['notap4'.$lp.$i];
        ${'notap5'.$lp.$i} = $row['notap5'.$lp.$i];
        ${'notap6'.$lp.$i} = $row['notap6'.$lp.$i];
        ${'notap7'.$lp.$i} = $row['notap7'.$lp.$i];
        ${'notap8'.$lp.$i} = $row['notap8'.$lp.$i];
        ${'notap9'.$lp.$i} = $row['notap9'.$lp.$i];
        ${'notap10'.$lp.$i} = $row['notap10'.$lp.$i];
      }
      if($lapsoActivo==3)
      {
        $literal=$row['literal'];
      }
    }
  }else
  {
   $dias_habiles=1; $asistencia=2; $observacion='';
  }
  $editable='S';?>
  
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h2>Boleta de notas <?= 'Lapso: '.$lapsoActivo.'° ('.$periodoActivo.')' ?></h2>
        <h4>Fechas permitidas para cargar calificaciones<br> desde: <?= date("d-m-Y", strtotime($iniciaCarga)) ?> hasta: <?= date("d-m-Y", strtotime($terminaCarga)) ?></h4>
      </div>
    </div><!-- End Breadcrumbs -->
    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="row">
            <div class="col-md-8 form-group text-center">
              <img src="<?= '../fotoalu/'.$ruta ?>" class="thumb" />
            </div>
            <div class="col-md-4">
              <button type="button" onclick="javascript:window.close();opener.window.focus();" class="btn btn-warning" style="width:100%;">X&nbsp;&nbsp;&nbsp;Cerrar Ventana</button>    
            </div>
          </div>
          <form role="form" method="POST" enctype="multipart/form-data" action="" >
            <div class="row" style="margin-top: 2%;">
              <div class="col-md-3 form-group">
                <label for="ced_alu" >Cédula</label>
                <label class="form-control"><?= $ced_alu; ?></label>
              </div>
              <div class="col-md-3 form-group">
                <label for="grado" >Grado/Sección</label>
                <label class="form-control"><?= $nomGra.' '.$nomSec; ?></label>
              </div>
              <div class="col-md-3 form-group">
                <label for="dias_habiles" >Dias Habiles</label>
                <input type="text" <?php if ($permite=='N'){ echo "disabled"; } ?> onkeypress="return valida(event)" onClick="this.select()" onblur="guardaInas('<?= encriptar($idAlum) ?>')" id="dias_habiles" class="form-control" value="<?= $dias_habiles; ?>">
              </div>
              <div class="col-md-3 form-group">
                <label for="asistencia" >Asistencias</label>
                <input type="text" <?php if ($permite=='N'){ echo "disabled"; } ?> onkeypress="return valida(event)" onClick="this.select()" onblur="guardaInas('<?= encriptar($idAlum) ?>')" id="asistencia" class="form-control" value="<?= $asistencia; ?>">
              </div>
            </div>
            <div class="row" style="margin-top: 1%;">
              <div class="col-md-6 form-group">
                <label for="apellido" >Apellidos</label>
                <label class="form-control"><?= $apellido; ?></label>
              </div>
              <div class="col-md-6 form-group">
                <label for="nombre" >Nombres</label>
                <label class="form-control"><?= $nombre; ?></label>
              </div>
            </div>
            <div class="row" style="margin-top: 2%;">
              <div class="container" data-aos="fade-up">
                <div class="table-responsive">
                  <table id="table_id" class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody><?php 
                      for ($x=1; $x <= 10; $x++) 
                      {
                        if (${'cedProf'.$x}==$docente && ${'dicta'.$x}==str_pad($x, 2, "0", STR_PAD_LEFT) ) 
                        { ?>
                          <tr style="background-color:#9FA8DA;">
                            <th scope="col" >Indicadores de: <?= utf8_encode(${'mate'.$x}) ?> </th>
                            <th width="15%" class="text-center">Escala</th>
                          </tr>
                          <?php
                          for ($i=1; $i <=7; $i++) 
                          { 
                            if (!empty(${'mate'.$x.$i})) 
                            { ?>
                              <tr>
                                <td><p><?= ${'mate'.$x.$i} ?></p></td>
                                <td>
                                  <select class="form-control" <?php if ($permite=='N'){ echo "disabled"; } ?> onchange="guardaNota('<?= encriptar($idAlum) ?>','<?= 'notap'.$x.$lp.$i ?>')" name="notap<?= $x.$lp.$i ?>" id="notap<?= $x.$lp.$i ?>">
                                    <option value=""></option><?php 
                                    if ($grado<51) {?>
                                      <option <?php if(${'notap'.$x.$lp.$i}=='C'){echo "selected";} ?> value="C">C</option>
                                      <option <?php if(${'notap'.$x.$lp.$i}=='EP'){echo "selected";} ?> value="EP">EP</option>
                                      <option <?php if(${'notap'.$x.$lp.$i}=='I'){echo "selected";} ?> value="I">I</option><?php
                                    }else
                                    {?>
                                      <option <?php if(${'notap'.$x.$lp.$i}=='A'){echo "selected";} ?> value="A">Consolidado con Excelencia</option>
                                      <option <?php if(${'notap'.$x.$lp.$i}=='B'){echo "selected";} ?> value="B">Consolidado</option>
                                      <option <?php if(${'notap'.$x.$lp.$i}=='C'){echo "selected";} ?> value="C">Proceso Avanzado</option>
                                      <option <?php if(${'notap'.$x.$lp.$i}=='D'){echo "selected";} ?> value="D">Proceso</option>
                                      <option <?php if(${'notap'.$x.$lp.$i}=='E'){echo "selected";} ?> value="E">Iniciado</option><?php 
                                    }?>
                                  </select>
                                </td>
                              </tr><?php 
                            }
                          }
                        }
                      }?>
                    </tbody>
                  </table>
                  <input type="hidden" id="grado" value="<?= $grado ?>">
                  <input type="hidden" id="seccion" value="<?= $seccion ?>">
                  <input type="hidden" id="ced_alu" value="<?= $ced_alu ?>">
                </div>
              </div>
            </div><?php 
            if($lapsoActivo==3)
            {?>
              <div class="row" style="margin-top: 1%;">
                <div class="col-md-4 offset-md-4 form-group text-center">
                  <label for="literal" >Literal de Aprobación</label><br>
                  <select onchange="guardaLiteral('<?= encriptar($idAlum) ?>')" id="literal" title="Indique el literal con el que aprobo el grado" class="form-control" >
                    <option value="" >Elija</option>
                    <option value="A" <?php if($literal=='A'){echo "selected";} ?>>Literal "A"</option>
                    <option value="B" <?php if($literal=='B'){echo "selected";} ?>>Literal "B"</option>
                    <option value="C" <?php if($literal=='C'){echo "selected";} ?>>Literal "C"</option>
                    <option value="D" <?php if($literal=='D'){echo "selected";} ?>>Literal "D"</option>
                    <option value="E" <?php if($literal=='E'){echo "selected";} ?>>Literal "E"</option>
                    <option value="P" <?php if($literal=='P'){echo "selected";} ?>>Literal "P"</option>
                  </select>
                </div>
              </div><?php
            }?>
            <div class="row" style="margin-top: 1%;">
              <div class="col-md-12 form-group">
                <label for="observacion" >Observaciones del Docente</label>
                <textarea rows="4" <?php if ($permite=='N'){ echo "disabled"; } ?> onblur="guardaInas('<?= encriptar($idAlum) ?>')" id="observacion" class="form-control"><?= $observacion ?></textarea>
              </div>
            </div>
            <div class="col-md-4 offset-md-4 " style="margin-top: 1%; ">
              <button type="button" onclick="javascript:window.close();opener.window.focus();" class="btn btn-warning" style="width:100%;">X&nbsp;&nbsp;&nbsp;Cerrar Ventana</button>    
            </div>
          </form>
        </div>
      </div>
    </section>
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include_once "footer.php"; ?>
  <script type="text/javascript">
    function guardaNota(id,campo) 
    {
      not=$('#'+campo).val()
      gra=$('#grado').val()
      sec=$('#seccion').val()
      ced=$('#ced_alu').val()
      $.post('actualNotaPri.php',{'id':id, 'campo':campo, 'not':not, 'ced':ced, 'gra':gra, 'sec':sec},function(data)
      {
        if(data.isSuccessful)
        {
          const Toast = Swal.mixin({
            toast: true,
            position: 'center',
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })

          Toast.fire({
            icon: 'success',
            title: 'Almacenando Nota Espere...'
          })   
        } else
        {
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            confirmButtonText:
            '<i class="fa fa-thumbs-up"></i> Entendido',
            text: 'Nota no almacenada'
          })
        }
      }, 'json');
    }
    function guardaLiteral(id) {
      not=$('#literal').val()
      $.post('actualLiteral.php',{'id':id, 'not':not},function(data)
      {
        if(data.isSuccessful)
        {
          const Toast = Swal.mixin({
            toast: true,
            position: 'center',
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })

          Toast.fire({
            icon: 'success',
            title: 'Almacenando Literal Espere...'
          })   
        } else
        {
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            confirmButtonText:
            '<i class="fa fa-thumbs-up"></i> Entendido',
            text: 'Literal no almacenada'
          })
        }
      }, 'json');

    }
    function valida(e)
    {
      tecla = (document.all) ? e.keyCode : e.which;
      if (tecla==8)
      {
        return true;
      }
      patron =/[0-9]/;
      tecla_final = String.fromCharCode(tecla);
      return patron.test(tecla_final);
    }
    function guardaInas(id) 
    {
      gra=$('#grado').val()
      sec=$('#seccion').val()
      ced=$('#ced_alu').val()
      ina=$('#asistencia').val()
      hab=$('#dias_habiles').val()
      obs=$('#observacion').val()
      $.post('actualInasPri.php',{'id':id, 'inas':ina, 'dias':hab, 'obse':obs, 'ced':ced, 'gra':gra, 'sec':sec},function(data)
      {
        if(data.isSuccessful)
        {
          const Toast = Swal.mixin({
            toast: true,
            position: 'center',
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })

          Toast.fire({
            icon: 'success',
            title: 'Almacenando Datos Espere...'
          })   
        } else
        {
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            confirmButtonText:
            '<i class="fa fa-thumbs-up"></i> Entendido',
            text: 'Dato no almacenado'
          })
        }
      }, 'json');
    }
  </script>

</body>

</html>