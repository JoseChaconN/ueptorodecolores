<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<1 ) 
{
  header("location:../index.php?vencio");
}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");
$fechahoy = strftime( "%Y-%m-%d");
include_once("../inicia.php");
include_once("../conexion.php");
$link = conectarse();
 ?>
<!DOCTYPE html>
<html lang="es">
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet"><?php
  include_once "header.php";
  if(isset($_GET['lapsoActivo']))
  {
    $lapsoActivo=$_GET['lapsoActivo'];
  }
  if(isset($_POST['grado']))
  {
    $grado=$_POST['grado'];
    $seccion=$_POST['seccion'];
    $materia=$_POST['materia'];
    $estrat=$_POST['estrat']; 
    $porcentaje=$_POST['porcentaje'];
    $fechacorte=$_POST['fechacorte'];
    $obsercorte=$_POST['obsercorte'];
    $corte1_existe=mysqli_query($link,"SELECT * FROM cortes1".$tablaPeriodo." WHERE cod_materia='$materia' AND cod_seccion='$seccion' ");
    if(mysqli_num_rows($corte1_existe)==0)
    {
      if($porcentaje>0)
      {
        mysqli_query($link,"INSERT INTO cortes1".$tablaPeriodo." (cod_materia , cod_seccion, fecha$estrat$lapsoActivo , porcentaje$estrat$lapsoActivo , obser$estrat$lapsoActivo ) VALUES ('$materia', '$seccion', '$fechacorte', '$porcentaje', '$obsercorte')") or die ("NO SE GUARDO".mysqli_error());
      }
    }else
    {
      $lp=$lapsoActivo;
      while($row=mysqli_fetch_array($corte1_existe))
      {
        $porc1 = ($estrat==1) ? 0 : $row['porcentaje1'.$lp] ;
        $porc2 = ($estrat==2) ? 0 : $row['porcentaje2'.$lp] ;
        $porc3 = ($estrat==3) ? 0 : $row['porcentaje3'.$lp] ;
        $porc4 = ($estrat==4) ? 0 : $row['porcentaje4'.$lp] ;
        $porc5 = ($estrat==5) ? 0 : $row['porcentaje5'.$lp] ;
        $total=($porc1+$porc2+$porc3+$porc4+$porc5);
      }
      if(($porcentaje+$total)<=100)
      {
        mysqli_query($link,"UPDATE cortes1".$tablaPeriodo." SET fecha$estrat$lapsoActivo='$fechacorte', porcentaje$estrat$lapsoActivo='$porcentaje', obser$estrat$lapsoActivo='$obsercorte' WHERE cod_materia='$materia' AND cod_seccion='$seccion'") or die ("NO SE ACTUALIZO".mysqli_error());
      }else
      {?>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <script type="text/javascript">
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            confirmButtonText:
            '<i class="fa fa-thumbs-up"></i> Entendido',
            text: 'El porcentaje seleccionado supera el maximo permitido'
          })
        </script><?php 
      }
    }?>
    <script type="text/javascript">
      opener.document.location.reload();
    </script><?php
  }else
  {
    $grado=desencriptar($_GET['grado']);
    $seccion=$_GET['seccion'];
    $materia=desencriptar($_GET['materia']);
    $estrat=$_GET['estrat'];
  }

  if($lapsoActivo==1)
  {
    $fecha_query=mysqli_query($link,"SELECT fecinicio,fecfinal FROM preinscripcion WHERE id=3 ");
  }
  if($lapsoActivo==2)
  {
    $fecha_query=mysqli_query($link,"SELECT fecinicio,fecfinal FROM preinscripcion WHERE id=4 ");
  }
  if($lapsoActivo==3)
  {
    $fecha_query=mysqli_query($link,"SELECT fecinicio,fecfinal FROM preinscripcion WHERE id=5 ");
  }
  while($row=mysqli_fetch_array($fecha_query))
  {
    $fecinicio=$row['fecinicio'];
    $fecfinal=$row['fecfinal'];
  }
  if(isset($_GET['lapsoActivo']))
  {
    $fecinicio=strftime( "%Y-%m-%d");
    $fecfinal=date("Y-m-d",strtotime($fecinicio."+ 1 days"));
  }

  $grado_query=mysqli_query($link,"SELECT nombreGrado FROM grado".$tablaPeriodo." WHERE grado='$grado'");
  while($row=mysqli_fetch_array($grado_query))
  {$nomgra=($row["nombreGrado"]);}
  
  $seccion_query=mysqli_query($link,"SELECT nombre FROM secciones WHERE id='$seccion'");
  while($row=mysqli_fetch_array($seccion_query))
  {$nomsec=$row["nombre"];}
  
  $materia_query=mysqli_query($link,"SELECT nombremate FROM materiass".$tablaPeriodo." WHERE codigo='$materia'");
  while($row=mysqli_fetch_array($materia_query))
  {$nommate=trim($row["nombremate"]);}

  $corte1_query=mysqli_query($link,"SELECT * FROM cortes1".$tablaPeriodo." WHERE cod_materia='$materia' AND cod_seccion='$seccion' ");
  if(mysqli_num_rows($corte1_query)==0)
  {
    $fechacorte=$fechahoy; $porcentaje=0; $obsercorte=''; $por1=0; $por2=0; $por3=0; $por4=0; $por5=0;
    $porctotal=0;  
  }else
  {
    while($row=mysqli_fetch_array($corte1_query))
    {
      $fechacorte=$row["fecha$estrat$lapsoActivo"];
      $porcentaje=$row["porcentaje$estrat$lapsoActivo"];
      $obsercorte=$row["obser$estrat$lapsoActivo"];
      $por1=$row['porcentaje1'.$lapsoActivo];
      $por2=$row['porcentaje2'.$lapsoActivo];
      $por3=$row['porcentaje3'.$lapsoActivo];
      $por4=$row['porcentaje4'.$lapsoActivo];
      $por5=$row['porcentaje5'.$lapsoActivo];
      $porctotal=$por1+$por2+$por3+$por4+$por5;
    }  
  }?>
  
  <main id="main">
    <!-- ======= TITULO ======= -->
    <div class="breadcrumbs" data-aos="fade-in">
      <div class="container">
        <h4>Estudiantes del <?= $nomgra.' '.$nomsec.'<br>Estrategia '.$estrat.'° / Lapso: '.$lapsoActivo.' / Materia: '.$nommate.'<br>Evaluado: '.$porctotal.'%<br>Puede cargar notas desde el: '.date("d-m-Y", strtotime($fecinicio)).' hasta el: '.date("d-m-Y", strtotime($fecfinal)) ?></h4>
      </div>
    </div><!-- End Breadcrumbs -->
    <section id="about" class="about" style="background-color:#E0E0E0;">
      <div class="container" data-aos="fade-up">
        <form method="POST" action="">
          <div class="row" style="margin-bottom: 2%;">
            <div class="col-md-2 col-xs-6 col-sm-2">
              <label>Estrategia (%)</label><br>
              <select name="porcentaje" required id="porcentaje" class="form-control" data-style="btn-default" data-live-search="true" data-width="fit">
                <option value="0">Seleccione...</option><?php 
                $i=5; 
                while($i <= 100) 
                {
                 $selected="";                           
                 if($porcentaje == $i){$selected="selected";}
                 echo "<option value='$i' $selected >$i</option>";
                 $i+=5; 
                }?>
              </select>
            </div>
            <div class="col-md-3 col-xs-6 col-sm-3">
              <label>Fecha de la Estrategia</label>
              <input type="date" name="fechacorte" required class="form-control" value="<?= $fechacorte; ?>">
              <input type="hidden" name="nroestrat" value="<?= $corteact ?>">
            </div>
            <div class="col-md-7 col-xs-12 col-sm-7">
              <label>Estrategia</label>
              <input type="text" name="obsercorte" required maxlength="50" class="form-control" value="<?= $obsercorte; ?>">
            </div>
            <input type="hidden" name="grado" value="<?= $grado ?>">
            <input type="hidden" name="seccion" id="seccion" value="<?= $seccion ?>">
            <input type="hidden" name="materia" value="<?= $materia ?>">
            <input type="hidden" name="estrat" value="<?= $estrat ?>"><?php
            if ($fechahoy>=$fecinicio && $fechahoy<=$fecfinal) 
            {
              if($porcentaje==0)
              {?>
                <div class="col-md-4 offset-md-2" style="margin-top:1%;">
                  <button type="submit" style="width: 100%;" class="btn btn-success">Crear Estrategia</button>
                </div>
                <div class="col-md-4" style="margin-top:1%;">
                  <button type="button" style="width: 100%;" onclick="javascript:window.close();opener.window.focus();" class="btn btn-warning">Cerrar Ventana</button>
                </div><?php 
              }else
              {?>
                <div class="col-md-4 offset-md-2" style="margin-top:1%;">
                  <button type="submit" title="Al realizar cambio en el %, fecha u observación de la estrategia debe hacer clic aqui para guardar el cambio" style="width: 100%;" class="btn btn-success">Actualizar Estrategia</button>
                </div>
                <div class="col-md-4" style="margin-top:1%;">
                  <button type="button" style="width: 100%;" onclick="javascript:window.close();opener.window.focus();" class="btn btn-warning">Cerrar Ventana</button>
                </div><?php
              }
            }?>
          </div>
        </form><?php 
        if($porcentaje>0)
        {?>
        <div class="table-responsive">
          <table id="table_id" class="table table-striped table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Cedula</th>
                <th scope="col">Estudiante</th>
                <th scope="col">Nota</th>
                <th scope="col">Inasistencia</th>
              </tr>
            </thead>
            <tbody><?php  
              $son=0;
              $alumnos_query=mysqli_query($link,"SELECT A.idAlumno, B.cedula, B.nombre, B.apellido FROM matri".$tablaPeriodo." A, alumcer B WHERE A.statusAlum='1' and A.grado='$grado' and A.idSeccion='$seccion' and A.ced_alu=B.cedula ORDER BY B.apellido ASC");
              while($row=mysqli_fetch_array($alumnos_query)) 
              { 
                $ced_alu=$row['cedula'];
                $nom_alu=substr($row['apellido'], 0,15).' '.substr($row['nombre'],0,15) ;
                $idAlum=$row['idAlumno'];
                $son++;?>
                <tr>
                  <td><?= $son; ?></td>
                  <td><?= $ced_alu ?></td>
                  <td><?= $nom_alu ?></td><?php
                  $nota_query=mysqli_query($link,"SELECT * FROM cortes".$tablaPeriodo." WHERE ced_alu = '$ced_alu' AND cod_materia = '$materia'");
                  if(mysqli_num_rows($nota_query) > 0)
                  {
                    while ($row6=mysqli_fetch_array($nota_query)) 
                    {
                      $nota=$row6["nota$estrat$lapsoActivo"];
                      $inas=$row6["inas$estrat$lapsoActivo"];
                    }  
                  }else
                  {
                    $nota=1;
                    $inas="";
                  } ?>
                  <td><?php 
                    if ($fechahoy>=$fecinicio && $fechahoy<=$fecfinal) 
                    {?>
                      <select name="<?= "nota$son"; ?>" id="nota<?= $son ?>" class="form-control" data-style="btn-default" data-live-search="true" onchange="guardaNota('<?= $ced_alu ?>','<?= $materia ?>','nota<?= $son ?>','<?= $son ?>','<?= $estrat ?>','<?= encriptar($idAlum) ?>')" >
                        <option value="">00</option><?php $i=1; 
                        while ($i <= 9)
                        { 
                          $nota1=substr($nota, 1);
                          $selected="";                           
                          if($nota1 == $i){$selected="selected";}
                          echo "<option value='0$i' $selected >0$i</option>";
                          $i+=1;
                        } 
                        $a=10;
                        while ($a <= 20)
                        { 
                          $selected="";                           
                          if($nota == $a){$selected="selected";}
                          echo "<option value='$a' $selected >$a</option>";
                          $a+=1;
                        } ?>
                      </select><?php 
                    }else 
                    {?>
                      <label class="form-control"><?= $nota ?></label><?php
                    }?>
                  </td>
                  <td><?php 
                    if ($fechahoy>=$fecinicio && $fechahoy<=$fecfinal) 
                    {?>
                      <input onkeypress="return valida(event)" onClick="this.select()" onblur="guardaInas('<?= $ced_alu ?>','<?= $materia ?>','inas<?= $son ?>','<?= $son ?>','<?= $estrat ?>')" type="text" maxlength="2" name="<?= "inas$son"; ?>" id="inas<?= $son ?>" class="form-control" style="width: 60px;" value="<?= $inas; ?>"><?php 
                    }else 
                    {?>
                      <label class="form-control"><?= $inas ?></label><?php
                    }?>
                  </td>
                </tr><?php 
              } ?>
            </tbody>
          </table>
          <input type="hidden" id="nombreGrado" value="<?= $nombreGrado ?>">
          <input type="hidden" id="nomsec" value="<?= $nomsec ?>">
          <input type="hidden" id="tituloTarea" value="<?= $tituloTarea ?>">
          <input type="hidden" id="descriTarea" value="<?= $descriTarea ?>">
          <input type="hidden" id="fechaPublica" value="<?= $fechaPublica ?>">
          <input type="hidden" id="fechaMaxima" value="<?= $fechaMaxima ?>">
          <input type="hidden" id="tabla" value="<?= $tablaPeriodo ?>">
          <input type="hidden" id="lapso" value="<?= $lapsoActivo ?>">
        </div><?php
        }?>
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
    function guardaNota(ced,mate,not,lin,cor,id) 
    {
      nota=$('#nota'+lin).val()
      sec=$('#seccion').val()
      por=$('#porcentaje').val()
      tab=$('#tabla').val()
      lap=$('#lapso').val()
      $.post('actualNota.php',{'ced':ced, 'mate':mate, 'nota':nota, 'corte':cor,'secci':sec,'porce':por,'idAlu':id,'tabla':tab,'lapso':lap},function(data)
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
      //alert(ced+' '+mate+' '+not+' '+nota)
    }
    function guardaInas(ced,mate,ina,lin,cor) 
    {
      inas=$('#inas'+lin).val()
      $.post('actualInas.php',{'ced':ced, 'mate':mate, 'inas':inas, 'corte':cor},function(data)
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
            title: 'Almacenando Dato Espere...'
          })   
        } else
        {
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            confirmButtonText:
            '<i class="fa fa-thumbs-up"></i> Entendido',
            text: 'Inasistencia no almacenada'
          })
        }
      }, 'json');
      //alert(ced+' '+mate+' '+not+' '+nota)
    }
  </script>

</body>

</html>