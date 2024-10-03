<?php
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION['cargo']<1) 
{
  header("location:../index.php?vencio");
}
require '../../colegio/vendor/autoload.php';
$tablaPeriodo=$_SESSION['tablaPeriodo'];
include_once("../inicia.php");
use PhpOffice\PhpSpreadsheet\IOFactory;
require '../conexion.php';
$link=conectarse();
include_once "header.php";
$lapso=desencriptar($_POST['lapso']);
$mate=desencriptar($_POST['mate']);
$ced_prof=desencriptar($_POST['ced_prof']);
$nomprofe=$_POST['nomprofe'];
$porc1=$_POST['porc1'];
$porc2=$_POST['porc2'];
$porc3=$_POST['porc3'];
$porc4=$_POST['porc4'];
$porc5=$_POST['porc5'];
$ced_usuario=$_SESSION['usuario'];
$nom_usuario=$_SESSION['nomuser'].' '.$_SESSION['apelluser'];
$campoNota_query=mysqli_query($link,"SELECT camponota,nombremate FROM materiass".$tablaPeriodo." WHERE codigo = '$mate' ");   
$row2=mysqli_fetch_array($campoNota_query);
$camponota=$row2['camponota']; 
$nombremate=$row2['nombremate']; 
$var = substr($camponota, 4);
$grado=substr($mate,0,2);
$campo_nota = "nota".$lapso.$var;

//Variable con el nombre del archivo

$nombreArchivo = $_FILES['notas']['tmp_name'];
$nombreArch = $_FILES['notas']['name'];
// Cargo la hoja de cálculo
$objPHPExcel = IOFactory::load($nombreArchivo);

//Asigno la hoja de calculo activa
$objPHPExcel->setActiveSheetIndex(0);
//Obtengo el numero de filas del archivo
$numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

$codMate = $objPHPExcel->getActiveSheet()->getCell('G5')->getCalculatedValue();
$codMate=str_replace ( 'Codigo Materia' , '' , $codMate);

$lapsoExcel = $objPHPExcel->getActiveSheet()->getCell('G2')->getCalculatedValue();
$lapsoExcel=str_replace ( 'Lapso ' , '' , $lapsoExcel);
if($lapsoExcel=='Primero'){$lapsoExcel=1;}
if($lapsoExcel=='Segundo'){$lapsoExcel=2;}
if($lapsoExcel=='Tercero'){$lapsoExcel=3;}
 ?>
<!DOCTYPE html>
<html lang="es">
    <main id="main">
        <!-- ======= TITULO ======= -->
        <div class="breadcrumbs" data-aos="fade-in">
          <div class="container">
            <h4>Materia: <?= $nombremate ?> <br>Prof. <?= $nombre.' '.$apelli.'<br>Lapso Activo: '.$lapsoActivo.'° ('.$periodoActivo.')' ?></h4>
          </div>
        </div><!-- End Breadcrumbs -->
        <section id="about" class="about" style="background-color:#E0E0E0;">
            <div class="container"><?php
                if($mate==$codMate and $lapsoExcel==$lapso)
                { 
                    $periodo_query=mysqli_query($link,"SELECT fechaRF, fechaRev FROM periodos WHERE tablaPeriodo = '$tablaPeriodo' ");
                    while($row=mysqli_fetch_array($periodo_query))
                    {
                        $fecRf=$row['fechaRF'];
                        $fecRv=$row['fechaRev'];
                    }?>
                    <div class="col-md-12 text-center" style="background-color: #85C1E9; color: #FFF; border-style: solid; margin-bottom: 1%; ">
                        <h3>Notas por Estrategia Actualizadas</h3>
                    </div>
                    <div class="col-md-12 ">
                        <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0" border="1" style="background-color: #FFF;">
                            <tr style="text-align: center; background-color: #85929E;">
                                <td>Cedula</td>
                                <td>Estudiante</td>
                                <td>&nbsp;Nota 1&nbsp;</td>
                                <td>&nbsp;Nota 2&nbsp;</td>
                                <td>&nbsp;Nota 3&nbsp;</td>
                                <td>&nbsp;Nota 4&nbsp;</td>
                                <td>&nbsp;Nota 5&nbsp;</td>
                                <td>&nbsp;Definitiva&nbsp;</td>
                            </tr><?php
                            for ($i = 13; $i <= $numRows; $i++) 
                            {
                                $cedula = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
                                $nombre = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
                                $nota1 = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
                                $nota2 = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
                                $nota3 = $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
                                $nota4 = $objPHPExcel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue();
                                $nota5 = $objPHPExcel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue();
                                if($porc1>0)
                                {$nota1=($nota1>0 and $nota1<10) ? '0'.intval($nota1) : $nota1 ;}else
                                {$nota1='';}
                                if($porc2>0)
                                {$nota2=($nota2>0 and $nota2<10) ? '0'.intval($nota2) : $nota2;}else
                                {$nota2='';}
                                if($porc3>0)
                                {$nota3=($nota3>0 and $nota3<10) ? '0'.intval($nota3) : $nota3;}else
                                {$nota3='';}
                                if($porc4>0)
                                {$nota4=($nota4>0 and $nota4<10) ? '0'.intval($nota4) : $nota4;}else
                                {$nota4='';}
                                if($porc5>0)
                                {$nota5=($nota5>0 and $nota5<10) ? '0'.intval($nota5) : $nota5;}else
                                {$nota5='';}
                                
                                $nota_def=round((($nota1*$porc1)/100) + (($nota2*$porc2)/100) + (($nota3*$porc3)/100) + (($nota4*$porc4)/100) + (($nota5*$porc5)/100),0);
                                
                                $ced_limpio=str_replace ( '-' , '' , $cedula);
                                $ced_limpio=str_replace ( 'V' , '' , $ced_limpio);
                                $ced_limpio=str_replace ( 'E' , '' , $ced_limpio);
                                $ced_limpio=str_replace ( 'P' , '' , $ced_limpio);
                                $ced_limpio=str_replace ( '.' , '' , $ced_limpio);
                                $ced_limpio=str_replace ( ' ' , '' , $ced_limpio); ?>
                                <tr>
                                    <td>&nbsp;<?= $ced_limpio ?>&nbsp;</td>
                                    <td>&nbsp;<?= $nombre ?></td>
                                    <td style="text-align: center;"><?= $nota1 ?></td>
                                    <td style="text-align: center;"><?= $nota2 ?></td>
                                    <td style="text-align: center;"><?= $nota3 ?></td>
                                    <td style="text-align: center;"><?= $nota4 ?></td>
                                    <td style="text-align: center;"><?= $nota5 ?></td>
                                    <td style="text-align: center;"><?= round($nota_def) ?></td>
                                </tr><?php
                                $nota_def=round($nota_def);
                                $corte_query=mysqli_query($link,"SELECT id FROM cortes".$tablaPeriodo." WHERE ced_alu = '$ced_limpio' and cod_materia='$mate' ");
                                if(mysqli_num_rows($corte_query) > 0)
                                {
                                    mysqli_query($link,"UPDATE cortes".$tablaPeriodo." SET nota1$lapso='$nota1', nota2$lapso='$nota2', nota3$lapso='$nota3', nota4$lapso='$nota4', nota5$lapso='$nota5' WHERE ced_alu='$ced_limpio' AND cod_materia = '$mate'") or die ("NO SE ACTUALIZO".mysqli_error());
                                }else
                                {
                                    mysqli_query($link,"INSERT INTO cortes".$tablaPeriodo." (ced_alu,cod_materia,nota1$lapso,nota2$lapso,nota3$lapso,nota4$lapso,nota5$lapso ) VALUE ('$ced_limpio','$mate','$nota1','$nota2','$nota3','$nota4','$nota5' ) ") or die ("NO SE CREO ".mysqli_error());
                                }
                                

                                mysqli_query($link,"UPDATE matri".$tablaPeriodo." SET $campo_nota = '$nota_def' WHERE ced_alu = '$ced_limpio'") or die ("NO ACTUALIZO NOTAS".mysqli_error($link));
                                $certiSi=0;
                                $tbnotas=mysqli_query($link,"SELECT idMatri, idAlumno, nota1".$var.", nota2".$var.", nota3".$var." FROM matri".$tablaPeriodo." WHERE ced_alu = '$ced_limpio'");
                                while($row=mysqli_fetch_array($tbnotas))
                                {
                                    $idAlumno=$row['idAlumno'];
                                    $notaDef1=$row["nota1".$var]; 
                                    $notaDef2=$row["nota2".$var]; 
                                    $notaDef3=$row["nota3".$var];
                                    $total=($notaDef1+$notaDef2+$notaDef3)/3;
                                    if ($total>9.4) {
                                        $certiSi++;
                                    }
                                    $total=round($total, 0, PHP_ROUND_HALF_UP);
                                    
                                }
                                if ($certiSi>0) {
                                    $tipo=($total<9.5) ? 'R' : 'F' ;
                                    $mesEv=($total<9.5) ? substr($fecRv, 5,2) : substr($fecRf, 5,2) ;
                                    $anoEv=($total<9.5) ? substr($fecRv, 0,4) : substr($fecRf, 0,4) ;
                                    $notas_query=mysqli_query($link,"SELECT notas, tipos, meses, anos, planteles FROM certifi WHERE idAlumno = '$idAlumno' and idGrado='$grado' ");
                                    if(mysqli_num_rows($notas_query)>0)
                                    {   
                                        foreach ($notas_query as $key => $value) {
                                            $notasArray = json_decode($value['notas'],TRUE);
                                            $tiposArray = json_decode($value['tipos'],TRUE);
                                            $mesesArray = json_decode($value['meses'],TRUE);
                                            $anosArray = json_decode($value['anos'],TRUE);
                                            $plantelArray = json_decode($value['planteles'],TRUE);
                                            
                                            $notasArray[$var]=($total<9.5) ? '  ' : $total ;
                                            $tiposArray[$var]=$tipo;
                                            $mesesArray[$var]=intval($mesEv);
                                            $anosArray[$var]=$anoEv;
                                            $plantelArray[$var]=CERTIPLAN;
                                        }
                                        $notasArray = json_encode($notasArray);
                                        $tiposArray = json_encode($tiposArray);
                                        $mesesArray = json_encode($mesesArray);
                                        $anosArray = json_encode($anosArray);
                                        $plantelArray = json_encode($plantelArray);
                                        mysqli_query($link,"UPDATE certifi SET notas='$notasArray', tipos='$tiposArray', meses='$mesesArray', anos='$anosArray', planteles='$plantelArray' WHERE idAlumno='$idAlumno' and idGrado='$grado' ") or die("No actualizo NOTAS".mysqli_error($link));
                                    }else
                                    {
                                        $notasArray=[''];
                                        $tiposArray=[''];
                                        $mesesArray=[''];
                                        $anosArray=[''];
                                        $plantelArray=[''];
                                        for ($i=0; $i <= 12; $i++) 
                                        { 
                                            $notasArray[$i]='PE';
                                            $tiposArray[$i]=$tipo;
                                            $mesesArray[$i]=intval($mesEv);
                                            $anosArray[$i]=$anoEv;
                                            $plantelArray[$i]=CERTIPLAN;
                                        }
                                        
                                        $notasArray[$var]=($total<9.5) ? '  ' : $total ;
                                        $tiposArray[$var]=$tipo;
                                        $mesesArray[$var]=intval($mesEv);
                                        $anosArray[$var]=$anoEv;
                                        $plantelArray[$var]=CERTIPLAN;
                                        
                                        $notasArray = json_encode($notasArray);
                                        $tiposArray = json_encode($tiposArray);
                                        $mesesArray = json_encode($mesesArray);
                                        $anosArray = json_encode($anosArray);
                                        $plantelArray = json_encode($plantelArray);
                                        mysqli_query($link,"INSERT INTO certifi (idAlumno, idGrado, notas, tipos, meses, anos, planteles) VALUES ('$idAlumno', '$grado', '$notasArray', '$tiposArray','$mesesArray', '$anosArray', '$plantelArray')") or die ("NO creo Arrays".mysqli_error($link));
                                    }
                                }

                            }
                            $evento='Subio notas desde subirEstrategias.php materia '.$nombremate;
                            mysqli_query($link,"INSERT INTO bitacora (ced_usuario, ced_docente, nombre_usuario, nombre_profe, evento, archivo_sube) VALUES ('$ced_usuario', '$ced_prof', '$nom_usuario', '$nomprofe', '$evento', '$nombreArch')") or die ("NO GUARDO BITACORA".mysqli_error()); ?>
                        </table>
                    </div><?php
                }else
                { ?>
                    <div class="col-md-8 offset-md-2" style="background-color: #CD6155; color: #FFF; border-style: solid; margin-bottom: 1%; ">
                        <center><h2>CUIDADO!</h2></center>
                        <h3 style="text-align: justify; padding:8px;">El archivo excel no es el correcto o fue modificado indebidamente<br>para poder subir notas debe:<br><br>
                            1- Debe crear las estrategias previamente hasta sumar el 100% del lapso.(en el Listado de Materias Asignadas)<br><br> 
                            2- Descargue el archivo en el botón verde con el icono de Excel que se encuentra en el Listado de Materias Asignadas.<br><br>
                            3- Debe descargar cada archivo según tantas materias tenga el docente asignada.<br><br>
                            4- Solo puede modificar del archivo las NOTAS, no debe realizar modificación de ningún otro dato.<br><br>
                            5- El archivo a subir debe ser el correspondiente a la materia, grado y sección (Ejemplo: NO PUEDE subir el archivo de Matemática de primer año A en el botón de Física de tercer año B).<br><br>
                            Nota: Recuerde que solo usted es el responsable de la información aquí ingresada y se guarda un registro del proceso realizado.
                         </h3>
                    </div><?php
                    $evento='ERROR! Intento subir notas desde subirEstrategias.php materia '.$nombremate;
                    mysqli_query($link,"INSERT INTO bitacora (ced_usuario, ced_docente, nombre_usuario, nombre_profe, evento, archivo_sube) VALUES ('$ced_usuario', '$ced_prof', '$nom_usuario', '$nomprofe', '$evento', '$nombreArch')") or die ("NO GUARDO BITACORA".mysqli_error());
                } ?>
                <div class="col-md-12" style="margin-top: 2%; margin-bottom: 2%;">
                    <div class="col-md-6 offset-md-3"><?php
                        if ($_SESSION['admin']==NULL) {?>
                            <a href="list-mate-prof.php?ced_prof=<?= $ced_prof ?>&pagva=2&lapsoMod=<?= $lapso ?>"><button class="btn btn-warning" style="width: 100%;">Regresar</button></a><?php 
                        }else
                        {?>
                            <a href="listmateprof.php?ced_prof=<?= $ced_prof ?>&pagva=2&accion=1&lapsoMod=<?= $lapso ?>"><button class="btn btn-warning" style="width: 100%;">Regresar</button></a><?php 
                        }?>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php include_once "footer.php"; ?>