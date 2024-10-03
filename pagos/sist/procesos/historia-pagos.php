<?php
include_once "../include/header.php";
$link = Conectarse();
$idAlum=desencriptar($_GET['id']);
$tablaPeriodo=$_GET['peri'];
$periodo_query = mysqli_query($link,"SELECT * FROM periodos  WHERE tablaPeriodo='$tablaPeriodo' ");     
while($row = mysqli_fetch_array($periodo_query))
{  
    $periodoVer=$row['nombre_periodo'];
}
$agosto_query = mysqli_query($link,"SELECT SUM(A.montoDolar) as monto FROM pagos".$tablaPeriodo." A, conceptos B WHERE A.idAlum ='$idAlum' and A.id_concepto=B.id and B.agosto='S' and A.statusPago='1' GROUP BY A.recibo"); 
$agosto=0;
while ($row = mysqli_fetch_array($agosto_query))
{
    $agosto=$agosto+$row['monto'];
}
$datos_query = mysqli_query($link,"SELECT A.nombre AS alumno, A.cedula, A.apellido, A.ruta as foto_alu, C.ruta as foto_rep, C.representante, C.correo as correo_rep FROM alumcer A,represe C WHERE A.idAlum='$idAlum' and C.cedula=A.ced_rep "); 
while ($row = mysqli_fetch_array($datos_query))
{   
    $cedula = $row['cedula'];
    $nombre = $row['alumno'].' '.$row['apellido'];
    $foto_alu = $row['foto_alu'];
    $foto_rep = $row['foto_rep'];
    $nom_rep=$row['representante'];
    $correo_rep=$row['correo_rep'];
}
$matri_query = mysqli_query($link,"SELECT A.*,B.nombreGrado AS nomgra, C.nombre AS nomsec FROM matri".$tablaPeriodo." A,grado".$tablaPeriodo." B, secciones C WHERE A.idAlumno='$idAlum' and A.grado=B.grado and A.idSeccion=C.id "); 
if(mysqli_num_rows($matri_query) == 0)
{
    $matri_query = mysqli_query($link,"SELECT A.*,B.nombreGrado AS nomgra, C.nombre AS nomsec FROM notaprimaria".$tablaPeriodo." A,grado".$tablaPeriodo." B, secciones C WHERE A.idAlumno='$idAlum' and A.grado=B.grado and A.idSeccion=C.id "); 
}
$idGrado=''; $totDesc=0;
while ($row = mysqli_fetch_array($matri_query))
{
    $idGrado=$row['grado'];
    $seccion=$row['idSeccion'];
    $suma_a_pagado=$row['suma_a_pagado'];
    $nomGrado = ($row['nomgra'].' "'.$row['nomsec'].'"');
    $grado=($row['nomgra']);
    for ($i=1; $i <14 ; $i++) { 
        ${'desc'.$i} = $row['desc'.$i];
        $totDesc=$totDesc+$row['desc'.$i];
    }
}
$secciVer_query = mysqli_query($link,"SELECT * FROM secciones WHERE id='$seccion' ");
while($row = mysqli_fetch_array($secciVer_query))
{
    $nom_sec=$row['nombre'];
}
$montos_query = mysqli_query($link,"SELECT monto,fecha_vence,mes,insc FROM montos".$tablaPeriodo." WHERE id_grado ='$idGrado' "); 
    $totalPeriodo=0; $meses=0; $morosida=0; $exonera=0;
while ($row = mysqli_fetch_array($montos_query))
{
    $totalPeriodo=$totalPeriodo+$row['monto'];
    $meses++;
    ${'insc'.$meses} = $row['insc'];
    ${'mes'.$meses} = $row['mes'];
    ${'f_vence'.$meses} = $row['fecha_vence'];
    ${'monto'.$meses} = $row['monto'];
    if($row['fecha_vence']<$fechaHoy)
    {
        $morosida=$morosida+($row['monto']-${'desc'.$meses});
    }
}
$venceAgosto=${'f_vence'.($meses)};
$nomGrado = ($idGrado>0) ? $nomGrado : 'No cursa periodo '.substr($tablaPeriodo,0,2).'-'.substr($tablaPeriodo,2,2) ;

$pagos_query = mysqli_query($link,"SELECT A.*,B.nombrePago, C.nom_banco,D.afecta FROM pagos".$tablaPeriodo." A, formas_pago B, bancos C, conceptos D WHERE A.idAlum = '$idAlum' and A.operacion=B.id and A.banco=C.cod_banco and A.id_concepto=D.id ORDER BY A.recibo  ");
$pagosMisc_query = mysqli_query($link,"SELECT A.*,B.nombrePago,C.nom_banco,D.afecta FROM miscelaneos A, formas_pago B, bancos C, conceptos D WHERE A.idAlum = '$idAlum' and A.recibo <> '' and A.operacion=B.id and A.banco=C.cod_banco and A.id_concepto=D.id ORDER BY A.id ");
$pagado=$suma_a_pagado; $pagos=0; $procesado=0;
while ($row = mysqli_fetch_array($pagos_query))
{
    if($row['statusPago']=='1' and $row['afecta']=='S')
    {
        $pagado=$pagado+$row['montoDolar'];
        $procesado=$procesado+$row['montoDolar'];
        $pagos++;
    }
}
$pagados=$pagado;
if ($fechaHoy>=$venceAgosto) {
    $pagado=$pagado+$agosto;    
}
$totalPeriodo=$totalPeriodo-$totDesc;
$morosida=$morosida-$pagado;
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Historial de Pagos</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <!--div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div-->
        <div class="card-body">
            <div class="table-responsive">
                <div class="form-row">
                    <div class="col-md-12" style="height: 40px;">
                        <button  class="btn btn-sm btn-block text-left" type="button" style="border-style: none;    background: #5499C7; color: white;" onclick="fnShowSecciones('#fotos_1','#btn_1');"><i class="fas fa-chevron-right f26em" id="btn_1"> </i>
                        <strong class="card-title" style="font-size: 16px; color: black;">&nbsp; Foto del Estudiante y Representante</strong></button>
                    </div>
                    <div class="col-md-12" style="display: none;" id="fotos_1">
                        <div class="form-row">
                            <div class="col-md-4  text-center"><?php  
                                if (file_exists('../../../fotoalu/'.$foto_alu)) 
                                {
                                    $foto_alu='../../../fotoalu/'.$foto_alu.'?'.time().mt_rand(0, 99999);
                                }else
                                {
                                    $foto_alu='../../../fotoalu/'.$foto_alu.'?'.time().mt_rand(0, 99999);
                                }
                                if (file_exists('../../../fotorep/'.$foto_rep)) 
                                {
                                    $foto_rep='../../../fotorep/'.$foto_rep.'?'.time().mt_rand(0, 99999);
                                }else
                                {
                                    $foto_rep='../../../fotorep/'.$foto_rep.'?'.time().mt_rand(0, 99999);
                                }?>
                                <img class='img-circle from-group' id="fotox" src="<?= $foto_alu ?>" /><br>
                                <label >Estudiante</label>
                            </div>
                            <div class="col-md-4  text-center">
                                <img class='img-circle from-group' src="<?= $foto_rep ?>" /><br>
                                <label >Representante</label>
                            </div>
                        </div>
                        <div class="col-md-12 " style="background-color: #CCD1D1; margin-top: 2%; margin-bottom: 2%; height: .5px;" ></div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-2 col-xs-12 col-sm-2">
                        <label>Cedula</label>
                        <input type="text" class="form-control" readonly value="<?= $cedula ?>">
                    </div>
                    <input type="hidden" name="ced_alu" value="<?= $cedula ?>">
                    <div class="col-md-4 col-xs-12 col-sm-5">
                        <label>Estudiante</label>
                        <input type="text" class="form-control" readonly value="<?= $nombre ?>">
                    </div>
                    <div class="col-md-3 col-xs-12 col-sm-5">
                        <label>Año/Grado</label>
                        <input type="text" class="form-control" readonly value="<?= $nomGrado ?>">
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-4">
                        <label for="peri_alu" >Año Escolar</label>
                        <select name='peri_alu' id="peri_alu" onchange="verPeriodo()" class="form-control"  ><?php
                            $peri2 = mysqli_query($link,"SELECT * FROM periodos WHERE pagos='S'  ORDER BY tablaPeriodo ASC");     
                            while($row = mysqli_fetch_array($peri2))
                            {   
                                $nom_peri2=$row['nombre_periodo'];
                                $tabla=$row['tablaPeriodo'];
                                $selected='';
                                if($tablaPeriodo == $tabla){
                                    $selected='selected';
                                }
                                echo '<option readonly value="'.$tabla.'"'.$selected.'>'.utf8_encode($nom_peri2)."</option>";
                            }
                            mysqli_free_result($peri2);?>
                        </select>
                    </div>
                </div>
                <div class="form-row" style="margin-bottom: 2%;">
                    <div class="col-md-3 col-xs-6 col-sm-4">
                        <label>Deuda Total del Año $</label>
                        <input type="text" class="form-control" readonly id="total" value="<?= number_format($totalPeriodo,2,'.','.') ?>">
                        <input type="hidden" id="idAlum" value="<?= encriptar($idAlum) ?>">
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-4">
                        <label>Monto Pagado $</label>
                        <input type="text" class="form-control" readonly id="pagado" value="<?= number_format($pagados,2,'.','.') ?>">
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-4">
                        <label>Agosto $</label>
                        <input type="text" class="form-control" readonly id="agosto" value="<?= number_format($agosto,2,'.','.') ?>">
                    </div>
                    <div class="col-md-3 col-xs-6 col-sm-4">
                        <label>Morosidad $</label>
                        <input type="text" class="form-control" readonly id="morosida" value="<?= number_format($morosida,2,'.','.') ?>">
                    </div><?php 
                    if ($idUserAct==1) {?>
                        <div class="row col-md-12 col-xs-12 col-sm-12">
                            <div class="col-md-6">
                                <label>Procesado $</label>
                                <input type="text" readonly value="<?= number_format($procesado,2,'.','.') ?>" class="form-control" >    
                            </div>
                            <div class="col-md-6">
                                <label>Sumar a Pagado $</label>
                                <input type="text" id="suma_a_pagado" value="<?= number_format($suma_a_pagado,2,'.','.') ?>" class="form-control" onClick="this.select()" onchange="actualiza()">    
                            </div>
                            
                            <input type="hidden" id="idAlum2" value="<?= ($idAlum) ?>">
                            
                        </div><?php 
                    }?>
                    <div class="col-md-3" style="margin-top: 2%;"><?php 
                        if ($idGrado<61) {?>
                            <button style="width: 100%; " onclick='window.open("../alumnos/perfil-pri-alumno.php?id=<?= encriptar($idAlum) ?>&peri=<?= $tablaPeriodo ?>&gra=<?= $idGrado ?>&sec=<?= $seccion ?>&nomP=<?= $periodoVer ?> ")' type="button" title='Perfil del estudiante ' class="btn btn-success " ><i class="fas fa-user fa-lg" ></i> Perfil</button><?php 
                        }else
                        {?>
                            <button style="width: 100%; " onclick='window.open("../alumnos/perfil-alumno.php?id=<?= encriptar($idAlum) ?>&peri=<?= $tablaPeriodo ?>&gra=<?= $idGrado ?>&sec=<?= $seccion ?>&nomP=<?= $periodoVer ?> ")' type="button" title='Perfil del estudiante ' class="btn btn-success " ><i class="fas fa-user fa-lg" ></i> Perfil</button><?php
                        }?>
                    </div>
                    <div class="col-md-3" style="margin-top: 2%;">
                        <button style="width: 100%;" onclick="enviaMail('<?= $nombre ?>','<?= $nom_rep ?>','<?= $correo_rep ?>','<?= $grado ?>','<?= $nom_sec ?>')" type="button" title='Enviar correo al representante' data-toggle="modal" data-target="#enviaMail" class="btn btn-info " ><i class="fas fa-envelope fa-lg" ></i> Enviar Correo</button>
                    </div>
                    <div class="col-md-3" style="margin-top: 2%;">
                        <button type="button" style="width: 100%;" class="btn btn-warning" onclick="javascript:window.close();opener.window.focus();"><i class="fas fa-reply fa-lg"></i> Cerrar</button>
                    </div>
                    <div class="col-md-3" style="margin-top: 2%;">
                        <button style="width: 100%;" onclick='window.open("edo-cuenta-pdf.php?id=<?= encriptar($idAlum) ?>&peri=<?= $tablaPeriodo ?>&anio=<?= $totalPeriodo ?>&pago=<?= $pagados ?>&moro=<?= $morosida ?>")' type="button" title='Estado de cuenta del estudiante' class="btn btn-danger " ><i class="fas fa-dollar-sign fa-lg" ></i> Edo.de Cuenta</button>
                    </div>
                    <input type="hidden" id="tablaPeriodo" value="<?= $tablaPeriodo ?>">
                    <input type="hidden" id="grado" value="<?= $idGrado ?>">
                </div><?php 
                if ($idGrado>0) {?>
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Recibo</th>
                                <th>Fecha</th>
                                <th>Concepto</th>
                                <th>Monto $</th>
                                <th>Monto Bs.</th>
                                <th>Status</th>
                                <th>Boton</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Recibo</th>
                                <th>Fecha</th>
                                <th>Concepto</th>
                                <th>Monto $</th>
                                <th>Monto Bs.</th>
                                <th>Status</th>
                                <th>Boton</th>
                            </tr>
                        </tfoot>
                        <tbody><?php 
                            $son=0;
                            mysqli_data_seek($pagos_query, 0);
                            while($row=mysqli_fetch_array($pagos_query)) 
                            {
                                $id=$row['id'];
                                $nrodeposito=$row['nrodeposito'];
                                $fechadepo=date("d-m-Y", strtotime($row['fechadepo']));
                                $fecha=date("d-m-Y", strtotime($row['fecha']));
                                //$recibo=$row['recibo'];
                                $recibo = ($row['recibo']>0) ? $row['recibo'] : $row['recibo2'] ;
                                $reciboTabla = ($row['recibo']>0) ? 'recibo' : 'recibo2' ;
                                $tipRecibo = ''; // ($row['recibo']>0) ? 'H-' : 'F-' ;
                                $salio = ($row['recibo']>0) ? '1' : '2' ;
                                $monto=$row['monto'];
                                $id_concepto=$row['id_concepto'];
                                $concepto=$row['concepto'];
                                $comentario=$row['comentario'];
                                $nombrePago=$row['nombrePago'];
                                $montoDolar=$row['montoDolar'];
                                $montoTasa=$row['montoTasa'];
                                $emitidoPor=$row['emitidoPor'];
                                $statusPago=$row['statusPago'];
                                $status = ($row['statusPago']=='1') ? 'Activo' : 'Anulado' ;
                                $nom_banco=$row['nom_banco'];
                                $opera='Operación: '.$nombrePago.' Banco: '.$nom_banco.', Ref.: '.$nrodeposito;?>
                                <tr <?php if($statusPago=='2'){ echo 'style="background-color:#FFCDD2; "';} ?> class="<?= 'trLin'.$recibo ?>">
                                    <td><?= $son+=1; ?></td>
                                    <td><?= $tipRecibo.str_pad($recibo, 6, "0", STR_PAD_LEFT) ?></td>
                                    <td><?= $fecha ?></td>
                                    <td><span data-toggle="tooltip" data-placement="top" title="<?= $opera ?>"><?= $concepto ?></span></td>
                                    <td align="right"><?= $montoDolar ?></td>
                                    <td align="right"><?= number_format($monto,2,',','.') ?></td>
                                    <td class="<?= 'sta'.$recibo ?>"><?= $status ?></td>
                                    <td>
                                        <button onclick="verPago('<?= $recibo ?>','<?= $son ?>',1,'<?= $salio ?>')" type="button" title='Ver datos del Pago' data-toggle="modal" data-target="#verPago" class="btn btn-info btn-circle" ><i class="fas fa-eye fa-lg" ></i></button>
                                    </td>
                                </tr><?php
                            }
                            if(mysqli_num_rows($pagosMisc_query) > 0)
                            {?>
                                <tr style="background-color:#16A085; color: white; " >
                                    <td><?= $son+=1; ?></td>
                                    <td><?= 'Recibo' ?></td>
                                    <td><?= 'Fecha' ?></td>
                                    <td>Conceptos por MISCELANEOS</td>
                                    <td>Monto $</td>
                                    <td>Monto Bs.</td>
                                    <td>Status</td>
                                    <td>Boton</td>
                                </tr>
                                <?php

                                while($row=mysqli_fetch_array($pagosMisc_query)) 
                                {
                                    $id=$row['id'];
                                    $nrodeposito=$row['nrodeposito'];
                                    $fechadepo=date("d-m-Y", strtotime($row['fechadepo']));
                                    $fecha=date("d-m-Y", strtotime($row['fecha']));
                                    $recibo=$row['recibo'];
                                    $monto=$row['monto'];
                                    $id_concepto=$row['id_concepto'];
                                    $concepto=$row['concepto'];
                                    $comentario=$row['comentario'];
                                    $nombrePago=$row['nombrePago'];
                                    $montoDolar=$row['montoDolar'];
                                    $montoTasa=$row['montoTasa'];
                                    $emitidoPor=$row['emitidoPor'];
                                    $statusPago=$row['statusPago'];
                                    $status = ($row['statusPago']=='1') ? 'Activo' : 'Anulado' ;
                                    $nom_banco=$row['nom_banco'];
                                    $opera='Operación: '.$nombrePago.' Banco: '.$nom_banco.', Ref.: '.$nrodeposito;?>
                                    <tr <?php if($statusPago=='2'){ echo 'style="background-color:#FFCDD2; "';} ?> class="<?= 'trLin'.$recibo ?>">
                                        <td><?= $son+=1; ?></td>
                                        <td><?= str_pad($recibo, 6, "0", STR_PAD_LEFT) ?></td>
                                        <td><?= $fecha ?></td>
                                        <td><span data-toggle="tooltip" data-placement="top" title="<?= $opera ?>"><?= $concepto ?></span></td>
                                        <td align="right"><?= $montoDolar ?></td>
                                        <td align="right"><?= number_format($monto,2,',','.') ?></td>
                                        <td class="<?= 'sta'.$recibo ?>"><?= $status ?></td>
                                        <td>
                                            <button onclick="verPago('<?= $recibo ?>','<?= $son ?>',2)" type="button" title='Ver datos del Pago' data-toggle="modal" data-target="#verPago" class="btn btn-info btn-circle" ><i class="fas fa-eye fa-lg" ></i></button>
                                        </td>
                                    </tr><?php
                                }
                            } ?>
                        </tbody>
                    </table><?php
                }?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="verPago" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle del Recibo: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Recibo Nro.</label>
                            <input type="text" id="recibo" class="form-control">
                            <input type="hidden" id="reciboPrint">
                            <input type="hidden" id="tablaRecibo">
                        </div>
                        <div class="col-md-4">
                            <label>Fecha</label>
                            <input type="text" id="fecha" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Tasa Bs.</label>
                            <input type="text" id="tasa" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Monto $</label>
                            <input type="text" id="dolar" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Monto Bs.</label>
                            <input type="text" id="bolivar" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Procesado por:</label>
                            <input type="text" id="usuario" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <h4 id="msjNulo" style="margin-top: 1%; background-color: #FFCDD2; display:none; text-align: center;">*** RECIBO ANULADO ***</h4>
                            <label>Comentario: </label>
                            <textarea id="comenta" class="form-control" rows="3"></textarea>
                        </div>
                        <input type="hidden" id="sale">
                        <div class="col-md-12" style="margin-top: 2%;">
                            <table class="table table-striped table-bordered " cellspacing="0" width="100%" >
                                <thead>
                                    <th >Concepto</th>
                                    <th style="text-align: center;">Monto $</th>
                                    <th style="text-align: center;">Monto Bs.</th>
                                </thead>
                                <tbody id="cuerpo">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Cerrar Ventana</button><?php
                if($_SESSION['cargo']=='1')
                {?>
                    <button type="button" data-toggle="modal" data-target="#anulaRecibo"  id="btnAnula" class="btn btn-danger">Anular recibo</button>
                    <button type="button" data-toggle="modal" data-target="#recuperaRecibo" id="btnRecupera" style="display:none;" class="btn btn-info">Recuperar recibo</button>
                    <?php 
                }?>
                <button type="button" onclick="reimprimeRecibo()" id="btnPrint" class="btn btn-info"><i class="fas fa-print fa-sm"></i> Reimprimir</button>
            </div>
            
            <input type="hidden" id="linea">
        </div>
    </div>
</div>
<div class="modal fade" id="anulaRecibo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="background-color: #FFCCBC; ">
            <div class="modal-header">
                <h5 class="modal-title">Anulación del Recibo: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Motivo de Anulación</label>
                            <textarea class="form-control" placeholder="Por favor indique el motivo por el cual anula este recibo, para poder ejecutar la anulación" id="motivo" onkeyup="activaBoton()" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Rechazar Anulación</button>
                <button type="button" onclick="anulaRecibo()" disabled id="btnAnula2" class="btn btn-danger">Ejecutar Anulación</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="recuperaRecibo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="background-color: #FFCCBC; ">
            <div class="modal-header">
                <h5 class="modal-title">Recuperación del Recibo: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Motivo de Recuperación</label>
                            <textarea class="form-control" placeholder="Por favor indique el motivo por el cual recupera este recibo, despues de haber sido anulado" id="motivoRecupera" onkeyup="activaBotonRecuperar()" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Rechazar Recuperación</button>
                <button type="button" onclick="recuperarRecibo()" disabled id="btnRecupera2" class="btn btn-danger">Ejecutar Recuperación</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="enviaMail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Representante: <h4 id="aquien"></h4></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" method="POST" target="_blank" enctype="multipart/form-data" action="../alumnos/mailRepre.php">
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" name="asunto" required id="asunto" placeholder="Asunto" class="form-control">
                        </div>
                        <div class="col-md-12" style="margin-top:1%;">
                            <textarea placeholder="Mensaje a enviar" rows="6" class="form-control" name="mensaje" id="mensaje"></textarea>
                        </div>
                        <div class="col-md-12" style="margin-top:1%;">
                            <label class="subtituloficha">Archivo adjunto</label>
                            <input type="file"  name="archivo" id="BSbtninfo" class="archivo" >
                        </div>
                        <input type="hidden" id="alumn_mail" name="alumn_mail">
                        <input type="hidden" id="repre_mail" name="repre_mail">
                        <input type="hidden" id="corre_mail" name="corre_mail">
                        <input type="hidden" id="grado_mail" name="grado_mail">
                        <input type="hidden" id="secci_mail" name="secci_mail">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" onclick="enviado()" class="btn btn-primary">Enviar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) 
    {
        $('#page-top').removeClass("sidebar-toggled");
        $('#accordionSidebar').addClass("navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled");
        $('.collapse-item').removeClass("active");
        $('.collapse').removeClass("show");
        //$('#emisionFactura').addClass("active");
    });
    $('#BSbtninfo').filestyle({
      buttonName : 'btn-info',
      buttonText : ' Buscar Archivo'
    });
    function enviaMail(alu,rep,mai,gra,sec) {
        document.querySelector('#aquien').innerText = rep;
        $('#alumn_mail').val(alu)
        $('#repre_mail').val(rep)
        $('#corre_mail').val(mai)
        $('#grado_mail').val(gra)
        $('#secci_mail').val(sec)
    }
    function enviado() {
        $('#enviaMail').modal('hide')
    }
    function fnShowSecciones(div,btn) 
    {
        $(div).slideToggle();
        $(btn).toggleClass("fas fa-chevron-right");
        $(btn).toggleClass("fas fa-chevron-down");
    }
    function reimprimeRecibo() {
        sale=$('#sale').val();
        reci=$('#reciboPrint').val()
        bus=$('#tablaRecibo').val()
        if(bus==1){window.open("../factura/factura-reimprime-pdf.php?recibo="+reci+"&sale="+sale);}else
        {window.open("../factura/factura-reimprime-misc-pdf.php?recibo="+reci);}
        
    }
    function actualiza() {
        id=$('#idAlum2').val()
        sum=$("#suma_a_pagado").val()
        tab=$('#tablaPeriodo').val()
        gra=$('#grado').val()
        pag=$("#pagado").val()
        tot=$("#total").val()
        $.post('../reportes/sumarPago.php',{'idAlum':id, 'suma':sum,'tabla':tab,'grado':gra,'pagado':pag,'total':tot},function(data)
        {
            if(data.isSuccessful)
            {
                
                $('#morosida').val(data.moro)
                $('#pagado').val(data.pago)
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
                title: 'Almacenando Monto Espere...'
              })
            } 
        }, 'json');
        
    }
    function verPago(reci,lin,bus,sale)
    {
        peri=$('#tablaPeriodo').val()
        $("#cuerpo").html("");
        $('#recibo').val(reci);
        $('#linea').val(lin);
        $('#tablaRecibo').val(bus)
        $('#sale').val(sale);
        if(bus==1){archivo='historia-buscar.php';}else{archivo='historia-misc-buscar.php';}
        $.post(archivo,{'recib':reci,'tabla':peri,'salio':sale},function(data)
        {
            if(data.isSuccessful)
            {
                $('#fecha').val(data.fecha);
                $('#forma').val(data.formaPag);
                $('#dolar').val(data.totalDolar);
                $('#bolivar').val(data.totalBs);
                $('#tasa').val(data.tasa);
                $('#referencia').val(data.nrodeposito);
                $('#banco').val(data.banco);
                $('#usuario').val(data.emitidoPor);  
                $('#comenta').val(data.comenta); 
                $('#reciboPrint').val(data.recPrint);
                if(data.status=='2')
                {
                    $('#btnAnula').hide();
                    $('#btnRecupera').show();
                    document.getElementById('msjNulo').style.display = 'block';
                    document.getElementById('btnPrint').style.display = 'none';
                    
                } else 
                {
                    $('#btnAnula').show();
                    $('#btnRecupera').hide();
                    document.getElementById('msjNulo').style.display = 'none';
                    document.getElementById('btnPrint').style.display = 'block';
                }
                for(var i=0; i<data.options.length; i++)
                {
                    if(data.options[i].codigo<10){tipo='0'+data.options[i].codigo} else{tipo=data.options[i].codigo}
                    var tr = "<tr>"+
                      "<td>"+data.options[i].conce+"</td>"+
                      "<td align='right'>"+data.options[i].dolar+" $</td>"+
                      "<td align='right'>"+data.options[i].bolivar+" Bs.</td>"+
                    "</tr>";
                    $("#cuerpo").append(tr)
                }
            } else
            {
                Swal.fire({
                  icon: 'error',
                  title: 'Alerta!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Datos no encontrados!'
                })
            }
        }, 'json');
    }
    function anulaRecibo() {
        peri=$('#tablaPeriodo').val()
        reci=$('#recibo').val()
        mot=$('#comenta').val()+' (Motivo de la Anulacion: '+$('#motivo').val()+')'
        lin=$('#linea').val()
        gra=$('#grado').val()
        bus=$('#tablaRecibo').val()
        if(bus==1){archivo='historia-anula.php';}else{archivo='historia-misc-anula.php';}
        $.post(archivo,{'recib':reci,'tabla':peri,'motivo':mot,'grado':gra },function(data)
        {
            if(data.isSuccessful)
            {
                $('#verPago').modal('hide')
                $('#anulaRecibo').modal('hide')
                var elColor=document.getElementsByClassName("trLin"+reci);
                for (var i=0; i<elColor.length; i++) elColor[i].style.backgroundColor="#FFCDD2";
                var elTexto=document.getElementsByClassName("sta"+reci);
                for (var i=0; i<elTexto.length; i++) elTexto[i].innerText = 'Anulado';
                Swal.fire({
                  icon: 'success',
                  title: 'Realizado!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Recibo fue eliminado satisfactoriamente!'
                })   
                
            } else
            {
                Swal.fire({
                  icon: 'error',
                  title: 'Alerta!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Datos no encontrados!'
                })
            }
        }, 'json');
    }
    function activaBoton() {
        mot=$('#motivo').val().length
        if(mot<16){document.getElementById("btnAnula2").disabled = true;}else{document.getElementById("btnAnula2").disabled = false;}
    }
    function verPeriodo()
    { 
        id=$('#idAlum').val()
        per=$('#peri_alu').val()
        location.href="historia-pagos.php?peri="+per+"&id="+id;
    } 
    function activaBotonRecuperar() {
        mot=$('#motivoRecupera').val().length
        if(mot<16){document.getElementById("btnRecupera2").disabled = true;}else{document.getElementById("btnRecupera2").disabled = false;}
    }
    function recuperarRecibo() {
        peri=$('#tablaPeriodo').val()
        reci=$('#recibo').val()
        mot=$('#comenta').val()+' (Motivo de la Recuperacion: '+$('#motivoRecupera').val()+')'
        lin=$('#linea').val()
        gra=$('#grado').val()
        bus=$('#tablaRecibo').val()
        if(bus==1){archivo='historia-recupera.php';}else{archivo='historia-misc-recupera.php';}
        $.post(archivo,{'recib':reci,'tabla':peri,'motivo':mot,'grado':gra },function(data)
        {
            if(data.isSuccessful)
            {
                window.parent.location.reload();
            } else
            {
                Swal.fire({
                  icon: 'error',
                  title: 'Alerta!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'Datos no encontrados!'
                })
            }
        }, 'json');
    }
</script>
<?php
include_once "../include/footer.php"; ?>
           