<?php
include_once "../include/header.php";
$link = Conectarse();
if(isset($_GET['id']))
{
    // actualizar INGRESOS
    /*$ingreso_query = mysqli_query($link,"SELECT recibo,fecha FROM pagos2122 GROUP BY recibo ORDER BY recibo "); 
    while ($row = mysqli_fetch_array($ingreso_query))
    {
        $recibo=$row['recibo'];
        $fecIng=$row['fecha'];
        mysqli_query($link,"INSERT INTO ingresos (id, tabla, fecha ) VALUE ('$recibo','2122','$fecIng' ) ") or die ("NO SE CREO ".mysqli_error());
    }*/
    $idAlum=desencriptar($_GET['id']);
    $tablaPeriodo=$_GET['peri'];
    $grado=$_GET['gra'];
    $tasa_query = mysqli_query($link,"SELECT monto FROM tasa_dia WHERE idTasa='1' "); 
    $row2=mysqli_fetch_array($tasa_query);
    $tasa=$row2['monto'];
    $puede_query = mysqli_query($link,"SELECT pagos FROM periodos WHERE tablaPeriodo='$tablaPeriodo' "); 
    $row3=mysqli_fetch_array($puede_query);    
    $pago_va=$row3['pagos'];
    $agosto_query = mysqli_query($link,"SELECT SUM(A.montoDolar) as monto FROM pagos".$tablaPeriodo." A, conceptos B WHERE A.idAlum ='$idAlum' and A.id_concepto=B.id and B.agosto='S' and A.statusPago='1' GROUP BY A.recibo "); 
    $agosto=0; 
    while ($row = mysqli_fetch_array($agosto_query))
    {
        $agosto=$agosto+$row['monto'];
    }
    $datos_query = mysqli_query($link,"SELECT A.nombre AS alumno, A.cedula, A.apellido, A.ruta as foto_alu,A.id_quienPaga,A.correo as mai_alu,A.ced_rep, C.ruta as foto_rep,C.correo as mai_rep, C.tlf_celu as cel_rep, B.nom_reci, B.ced_reci, B.dir_reci FROM alumcer A, emite_pago B, represe C WHERE A.idAlum='$idAlum' and C.cedula=A.ced_rep and A.id_quienPaga=B.id "); 
    while ($row = mysqli_fetch_array($datos_query))
    {   
        $cedula = $row['cedula'];
        $nombre = $row['alumno'].' '.$row['apellido'];
        $foto_alu = $row['foto_alu'];
        $foto_rep = $row['foto_rep'];
        $nom_reci=$row['nom_reci'];
        $ced_reci=$row['ced_reci'];
        $dir_reci=$row['dir_reci'];
        $id_quienPaga=$row['id_quienPaga'];
        $mai_alu=$row['mai_alu'];
        $mai_rep=$row['mai_rep'];
        $cel_rep=$row['cel_rep'];
        $ced_rep=$row['ced_rep'];
    }
    if($grado<61)
    {
        $matri_query = mysqli_query($link,"SELECT A.*,B.nombreGrado AS nomgra, C.nombre AS nomsec FROM notaprimaria".$tablaPeriodo." A,grado".$tablaPeriodo." B, secciones C WHERE A.idAlumno='$idAlum' and A.grado=B.grado and A.idSeccion=C.id "); 
        $idGrado=''; $totDesc=0;
        while ($row = mysqli_fetch_array($matri_query))
        {
            $idGrado=$row['grado'];
            $suma_a_pagado=$row['suma_a_pagado'];
            $retiraPagos=$row['retiraPagos'];
            $textRetiro = ($retiraPagos>'1990-01-01') ? 'Retirado el '.date("d-m-Y", strtotime($retiraPagos)) : '' ;
            $nomGrado = ($row['nomgra'].' "'.$row['nomsec'].'"');
            for ($i=1; $i <14 ; $i++) { 
                ${'desc'.$i} = $row['desc'.$i];
                $totDesc=$totDesc+$row['desc'.$i];
            }
            $convenio=$row['convenio'];
        }
    }else
    {
        $matri_query = mysqli_query($link,"SELECT A.*,B.nombreGrado AS nomgra, C.nombre AS nomsec FROM matri".$tablaPeriodo." A,grado".$tablaPeriodo." B, secciones C WHERE A.idAlumno='$idAlum' and A.grado=B.grado and A.idSeccion=C.id "); 
        $idGrado=''; $totDesc=0;
        while ($row = mysqli_fetch_array($matri_query))
        {
            $idGrado=$row['grado'];
            $suma_a_pagado=$row['suma_a_pagado'];
            $retiraPagos=$row['retiraPagos'];
            $textRetiro = ($retiraPagos>'1990-01-01') ? 'Retirado el '.date("d-m-Y", strtotime($retiraPagos)) : '' ;
            $nomGrado = ($row['nomgra'].' "'.$row['nomsec'].'"');
            for ($i=1; $i <14 ; $i++) { 
                ${'desc'.$i} = $row['desc'.$i];
                $totDesc=$totDesc+$row['desc'.$i];
            }
            $convenio=$row['convenio'];
        }    
    }
    if($retiraPagos>'1990-01-01'){$fechaVence=$retiraPagos;}else{$fechaVence=$fechaHoy;}
    $montos_query = mysqli_query($link,"SELECT monto,fecha_vence,mes,insc FROM montos".$tablaPeriodo." WHERE id_grado ='$idGrado' "); 
        $totalPeriodo=0; $meses=0; $morosida=0; $exonera=0; $montoAgosto=0;
    while ($row = mysqli_fetch_array($montos_query))
    {
        $totalPeriodo=$totalPeriodo+($row['monto']);
        $meses++;
        ${'insc'.$meses} = $row['insc'];
        ${'mes'.$meses} = $row['mes'];
        ${'f_vence'.$meses} = $row['fecha_vence'];
        ${'monto'.$meses} = $row['monto'];
        if($row['fecha_vence']<$fechaVence)
        {
            $morosida=$morosida+($row['monto']-${'desc'.$meses});
            $montoAgosto=$row['monto']-${'desc'.$meses};
        }
    }

    $montoMes=${'monto'.($meses-1)};
    $venceAgosto=${'f_vence'.($meses)};
    $nomGrado = ($idGrado>0) ? $nomGrado : 'No cursa periodo '.substr($tablaPeriodo,0,2).'-'.substr($tablaPeriodo,2,2) ;
    $ultimo_pagos_query = mysqli_query($link,"SELECT A.*,B.nombrePago,C.nom_banco,D.afecta FROM pagos".$tablaPeriodo." A, formas_pago B, bancos C, conceptos D WHERE A.statusPago='1' and A.idAlum = '$idAlum' and A.operacion=B.id and A.banco=C.cod_banco and A.id_concepto=D.id ORDER BY A.id DESC LIMIT 1 ");
    if(mysqli_num_rows($ultimo_pagos_query) > 0)
    {
        $rowU=mysqli_fetch_array($ultimo_pagos_query);
        $ultFecha=date("d-m-Y", strtotime($rowU['fecha']));
        $ultRecibo=$rowU['recibo'];
        $ultMonto=$rowU['monto'];
        $ultConcepto=$rowU['concepto'];
        $ultMontoDolar=$rowU['montoDolar'];
        $ultimo='Ultimo Pago Fact: '.$ultRecibo.' del '.$ultFecha.' '.$ultConcepto.' '.$ultMontoDolar.' $ = '.$ultMonto.' Bs.';
    }else{
        $ultimo='';
    }

    $pagos_query = mysqli_query($link,"SELECT A.*,B.nombrePago,C.nom_banco,D.afecta FROM pagos".$tablaPeriodo." A, formas_pago B, bancos C, conceptos D WHERE A.idAlum = '$idAlum' and A.operacion=B.id and A.banco=C.cod_banco and A.id_concepto=D.id ORDER BY A.id ");
    $pagado=$suma_a_pagado; $pagos=0;
    while ($row = mysqli_fetch_array($pagos_query))
    {
        if($row['statusPago']=='1' and $row['afecta']=='S' )
        {
            $pagado=$pagado+$row['montoDolar'];
            $pagos++;
        }
    }
    $pagados=$pagado;
    if ($fechaHoy>=$venceAgosto) {
        $pagado=$pagado+$agosto;    
    }
    $totalPeriodo=$totalPeriodo-$totDesc;
    $morosida=$morosida-$pagado;?>
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="form-row">
            <div class="col-md-8">
                <h1 class="h3 mb-2 text-gray-800">Emisión de Factura </h1>
            </div>
            <div class="col-md-4">
                <button type="button" style="width: 100%; color: black;" class="btn btn-warning" onclick="javascript:window.close();opener.window.focus();"><i class="fas fa-reply fa-sm"></i> Cerrar</button>
            </div>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
           <div class="card-body">
                <div class="table-responsive">
                    <div class="form-row"><!--FOTO-->
                        <div class="col-md-12 col-12" style="height: 40px;">
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
                    <div class="form-row"><!--CED NOMB GRADO-->
                        <div class="col-md-2 col-12">
                            <label>Cedula</label>
                            <input type="text" class="form-control" readonly value="<?= $cedula ?>">
                        </div>
                        <input type="hidden" name="ced_alu" value="<?= $cedula ?>">
                        <input type="hidden" id="ced_rep" value="<?= $ced_rep ?>">
                        <div class="col-md-4 col-12">
                            <label>Estudiante</label>
                            <input type="text" class="form-control" readonly value="<?= $nombre ?>">
                        </div>
                        <div class="col-md-3 col-12">
                            <label>Año/Grado</label>
                            <input type="text" class="form-control" readonly value="<?= $nomGrado ?>">
                            <input type="hidden" id="idGrado" value="<?= $idGrado ?>">
                        </div>
                        <div class="col-md-3 col-12"><!--PERIODO-->
                            <label for="peri_alu" >Año Escolar</label>
                            <select style="background-color: red; color: white; font-size: 18px; font-weight: bold; text-align: center; " name='peri_alu' id="peri_alu" disabled class="form-control"  ><?php
                                $peri2 = mysqli_query($link,"SELECT * FROM periodos  ORDER BY tablaPeriodo ASC");     
                                while($row = mysqli_fetch_array($peri2))
                                {   
                                    $nom_peri2=$row['nombre_periodo'];
                                    $tabla=$row['tablaPeriodo'];
                                    $selected='';
                                    if($tablaPeriodo == $tabla){
                                        $selected='selected';
                                    }
                                    echo '<option readonly value="'.$tabla.'"'.$selected.'>'.'>>> '.utf8_encode($nom_peri2)." <<<</option>";
                                }
                                mysqli_free_result($peri2);?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row" style="margin-bottom: 2%; text-align: center; ">
                        
                        <div class="col-md-3 col-xs-6 col-sm-4" style="background-color: #EBDEF0;"><!--DEUDA DEL PERIODO-->
                            <label>Deuda Total del Año $</label>
                            <label class="form-control"><?= number_format($totalPeriodo,2,'.','.') ?></label>
                        </div>
                        <div class="col-md-3 col-xs-6 col-sm-4" style="background-color:#D4EFDF;" ><!--MONTO PAGADO-->
                            <label>Pagado $</label>
                            <label class="form-control"><?= number_format($pagados,2,'.','.') ?></label>
                        </div>
                        <div class="col-md-3 col-xs-6 col-sm-4" style="background-color:#D4EFDF;"><!--AGOSTO-->
                            <label>Agosto $</label>
                            <label class="form-control"><?= number_format($agosto,2,'.','.') ?></label>
                        </div>
                        <div class="col-md-3 col-xs-6 col-sm-4" style="background-color:#FADBD8;"><!--MOROSIDAD-->
                            <label>Morosidad $</label>
                            <label class="form-control" data-toggle="tooltip" data-placement="top" title="<?= $textRetiro ?>"><?= number_format($morosida,2,'.','.') ?></label>
                        </div>
                    </div>
                    <div class="form-row"><!--CONVENIOS-->
                        <div class="col-md-12" style="height: 40px;">
                            <button class="btn btn-sm btn-block text-left" type="button" style="border-style: none; background: #5499C7; color: white;" onclick="fnShowSecciones('#convenio_1','#btn_3');"><i class="fas fa-chevron-right f26em" id="btn_3"> </i>
                            <strong class="card-title" style="font-size: 16px; color: black;">&nbsp; Convenios</strong></button>
                        </div>
                        <div class="col-md-12" style="<?php if(empty($convenio)){ echo 'display: none;'; } ?>" id="convenio_1" >
                            <textarea class="form-control" id="convenio" onblur="guardaConvenio()" rows="5" style="margin-bottom: 2%; <?php if(!empty($convenio)){ echo 'background-color: #F4D03F;'; } ?> "><?= $convenio ?></textarea>
                        </div>
                    </div>
                    <div class="form-row"><!--TABLA DE HISTORIA-->
                        <div class="col-md-12" style="height: 40px;">
                            <button  class="btn btn-sm btn-block text-left" type="button" style="border-style: none;    background: #5499C7; color: white;" onclick="fnShowSecciones('#historia_1','#btn_2');"><i class="fas fa-chevron-right f26em" id="btn_2"> </i>
                            <strong class="card-title" style="font-size: 16px; color: black;">&nbsp; Historia de Pagos</strong></button>
                        </div><?php
                        if ($ultimo!='') {?>
                            <div class="col-md-12" style="background-color: #FFFF8D; color: black; ">
                                <h5><?= $ultimo ?></h5>
                            </div><?php
                         } 
                        if ($idGrado>0) {?>
                            <div class="col-md-12" style="display: none;" id="historia_1">
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
                                            $recibo1=$row['recibo'];
                                            $recibo2=$row['recibo2'];
                                            $recibo = ($recibo1>0) ? $recibo1 : $recibo2 ;
                                            $tipRec = ''; //($recibo1>0) ? 'H-' : 'F-' ;
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
                                                <td><?= $tipRec.str_pad($recibo, 6, "0", STR_PAD_LEFT) ?></td>
                                                <td><?= $fecha ?></td>
                                                <td><span data-toggle="tooltip" data-placement="top" title="<?= $opera ?>"><?= $concepto ?></span></td>
                                                <td align="right"><?= $montoDolar ?></td>
                                                <td align="right"><?= number_format($monto,2,',','.') ?></td>
                                                <td class="<?= 'sta'.$recibo ?>"><?= $status ?></td>
                                                <td>
                                                    <button onclick="verPago('<?= $recibo ?>','<?= $son ?>','<?= $salio ?>')" type="button" title='Ver datos del Pago' data-toggle="modal" data-target="#verPago" class="btn btn-info btn-circle" ><i class="fas fa-eye fa-lg" ></i></button>
                                                </td>
                                            </tr><?php
                                        } ?>
                                    </tbody>
                                </table>
                            </div><?php 
                        }?>
                    </div>
                    <div class="form-row"><!--MONTOS A PAGAR-->
                        <div class="col-md-12" style="height: 40px;">
                            <button  class="btn btn-sm btn-block text-left" type="button" style="border-style: none;    background: #5499C7; color: white;" onclick="fnShowSecciones('#montos_1','#btn_3');"><i class="fas fa-chevron-right f26em" id="btn_3"> </i>
                            <strong class="card-title" style="font-size: 16px; color: black;">&nbsp; Montos a pagar</strong></button>
                        </div>
                        <style type="text/css">
                            .titulo{
                                background-color:#81C784;
                                color: white;
                             }
                             .titInsc{
                                background-color: #43A047;
                                color: white;
                             }
                             .tieneDesc{
                                background-color: #FFCDD2;
                             }
                             .montosDer{
                                text-align: right;
                             }
                        </style>
                        <div class="col-md-12" style="display: none; background-color: #C8E6C9; margin-bottom: 1%; padding: 5px; " id="montos_1">
                            <div class="form-row">
                                <div class="row col-md-10 offset-md-1">
                                    <div class="col-md-6 titInsc">
                                        <label>Concepto:</label>
                                        <p><?= $mes1 ?></p>
                                    </div>
                                    <div class="col-md-3 titInsc">
                                        <label>Monto:</label>
                                        <p><?= $monto1.' $' ?></p>
                                    </div>
                                    <div class="col-md-3 titInsc">
                                        <label>Descuen.Inscrip.:</label>
                                        <input type="text" value="<?= $desc1 ?>" onClick="this.select()" <?php if($desc1>0){echo 'style="color:red;"'; } ?> id="desc1" onchange="MASK(this,this.value,'-##,###,##0.00',1); actualDesc(1)" class="form-control montosDer">
                                    </div>
                                </div>
                                <div class="form-row col-md-12"></div><?php
                                for ($i=1; $i <=2 ; $i++) { ?>
                                    <div class="form-row col-md-6">
                                        <div class="col-md-3 titulo">
                                            <label>Mes</label>
                                        </div>
                                        <div class="col-md-4 titulo">
                                            <label>Fecha Vence</label>
                                        </div>
                                        <div class="col-md-2 titulo">
                                            <label>Monto</label>
                                        </div>
                                        <div class="col-md-3 titulo">
                                            <label>Descuen.</label>
                                        </div>
                                    </div><?php 
                                }
                                for ($i=1; $i <=$meses ; $i++) { 
                                    if(${'insc'.$i} == NULL)
                                    { ?>
                                        <div class="form-row col-md-6" >
                                            <div class="col-md-3 <?php if(${'desc'.$i}>0){echo 'tieneDesc'; } ?>" id="<?= 'divMes'.$i ?>">
                                                <label ><?= substr(${'mes'.$i},0,3).'/'.substr(${'f_vence'.$i}, 0,4) ?></label>
                                            </div>
                                            <div class="col-md-4 <?php if(${'desc'.$i}>0){echo 'tieneDesc'; } ?>" id="<?= 'divFec'.$i ?>">
                                                <label><?= date("d-m-Y", strtotime(${'f_vence'.$i})) ?></label>
                                            </div>
                                            <div class="col-md-2 <?php if(${'desc'.$i}>0){echo 'tieneDesc'; } ?>" id="<?= 'divMon'.$i ?>">
                                                <label><?= ${'monto'.$i}.'$' ?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" onClick="this.select(); montoVie(this.value)" class="form-control montosDer" <?php if(${'desc'.$i}>0){echo 'style="color:red; background-color:#FFCDD2; "'; } ?> id="<?= 'desc'.$i ?>" onchange="MASK(this,this.value,'-##,###,##0.00',1); actualDesc('<?= $i ?>'); prontoPag(this.value);" value="<?= ${'desc'.$i} ?>" >
                                                <input type="hidden" id="montoViejo">
                                            </div>
                                        </div><?php
                                    }
                                }?>
                                <input type="hidden" id="meses" name="meses" value="<?= $meses ?>">
                            </div>
                        </div>
                    </div>
                    <form id="facturaForm" method="POST" target="_blank" action="factura-pdf.php" autocomplete="off" onsubmit="return validacion()">
                        <div class="form-row">
                            <div class="col-md-3 text-center">
                                <h4>Monto x mes:<br>
                                <input type="text" value="<?= $montoMes ?>" readonly id="montoMes" style="text-align: right; width: 100px; ">$</h4>
                                <input type="hidden" name="periodo" value="<?= $nom_peri2 ?>">
                            </div>
                            <div class="col-md-3 text-center" style="background-color: red; color: yellow; "><!--TASA DEL DIA-->
                                <h4 >Tasa del dia:<br>
                                <input type="text" onClick="this.select()" onchange="MASK(this,this.value,'-##,###,##0.00',1); totalRecibo(); actualTasa()"  style="text-align: right; width: 100px;" name="tasaDolar" id="tasaDolar" value="<?= $tasa ?>">Bs.</h4>
                            </div>
                            <div class="col-md-3 text-center">
                                <h4>Monto x mes:<br>
                                <input type="text" value="<?= number_format(($montoMes*$tasa),2,'.',',') ?>" readonly id="montoMesBs" style="text-align: right; width: 100px; ">Bs.</h4>
                            </div>
                            <div class="col-md-3"><!--TASA DEL DIA-->
                                <h4>F. Pago:<br> 
                                    <select id="formaGen" onchange="todasForma();" class="form-control"><?php 
                                        $fpag_query = mysqli_query($link,"SELECT id,nombrePago FROM formas_pago WHERE status='1' ");
                                        while($row = mysqli_fetch_array($fpag_query))
                                        {
                                            $id=$row['id'];
                                            $nombrePago=$row['nombrePago'];
                                            echo '<option readonly value="'.$id.'" >'.utf8_encode($nombrePago)."</option>";
                                        }?>
                                    </select></h4>
                            </div>
                        </div>
                        <div class="form-row text-center" style="background-color: #C5CAE9; margin-bottom: 1%;"><!--TITULO-->
                            <div class="col-md-2 col-4">
                                <h5>Cod.</h5>
                            </div>
                            <div class="col-md-3 col-2 d-none d-md-block">
                                <h5>Detalle</h5>
                            </div>
                            <div class="col-md-2 col-4">
                                <h5>F.Pago</h5>
                            </div>
                            <div class="col-md-2 col-4">
                                <h5>Monto $</h5>
                            </div>
                            <div class="col-md-2 col-2 d-none d-md-block">
                                <h5>Monto Bs.</h5>
                            </div>
                            <div class="col-md-1 col-2 d-none d-md-block">
                                <h5></h5>
                            </div>
                        </div>
                        <div class="form-row" id="div_factura" ><?php  
                            for ($i=1; $i < 11; $i++) { ?>
                                <div class="col-md-2 col-12 linea<?= $i ?>" style="display: none;"><!--CONCEPTOS-->
                                    <select name="<?= 'conce'.$i ?>" id="<?= 'conce'.$i ?>" onchange="datos('<?= $i ?>'); totalRecibo(); nuevaLin('<?= $i ?>',1); " class="form-control">
                                        <option value="">Seleccione </option><?php 
                                        $pago=number_format($pagado,2,'.',',');
                                        $total=number_format($totalPeriodo,2,'.',',');
                                        $monIns=number_format($monto1,2,'.',',');
                                        $select1 = mysqli_query($link,"SELECT * FROM conceptos WHERE status='1' and IF($pago>=$total,afecta='N', IF($pago>=$monIns, id>1 , id>0 ) )");
                                        while($row = mysqli_fetch_array($select1))
                                        {
                                            $id=$row['id'];
                                            $concepto=$row['concepto'];
                                            $monto=$row['monto'];
                                            $afecta=$row['afecta'];
                                            //$abrev = (empty($row['abrev'])) ? 'nulo' : $row['abrev'] ;
                                            $abrev=$row['abrev'];
                                            $agost=$row['agosto'];
                                            $editar=$row['editar'];
                                            $abonos=$row['abonos'];
                                            echo '<option readonly value="'.$concepto.'" data-id='.$id.' data-monto='.$monto.' data-afecta='.$afecta.' data-ago='.$agost.' data-escribe='.$editar.' data-abono='.$abonos.' data-abrev='.$abrev.' >'.$id.'-'.($concepto)."</option>";
                                        }?>
                                    </select>
                                    <input type="hidden" name="<?= 'id_concepto'.$i ?>" id="<?= 'id_concepto'.$i ?>">
                                    <input type="hidden" name="<?= 'afecta'.$i ?>" id="<?= 'afecta'.$i ?>">
                                    <input type="hidden" name="<?= 'abrev'.$i ?>" id="<?= 'abrev'.$i ?>">
                                    <input type="hidden" name="<?= 'agosto'.$i ?>" id="<?= 'agosto'.$i ?>">
                                </div>
                                <div class="col-md-3 col-12 linea<?= $i ?>" style="display: none;">
                                    <textarea rows="1" name="<?= 'detalle'.$i ?>" id="<?= 'detalle'.$i ?>" class="form-control" readonly ></textarea>
                                </div>
                                <div class="col-md-2 col-6 linea<?= $i ?>" style="display: none;"><!--FORMA DE PAGO-->
                                    <select name="<?= 'fpag'.$i ?>" id="<?= 'fpag'.$i ?>" onchange="muestraForma('<?= $i ?>'); totalRecibo() " class="form-control"><?php 
                                        $fpag_query = mysqli_query($link,"SELECT id,nombrePago FROM formas_pago WHERE status='1' ");
                                        while($row = mysqli_fetch_array($fpag_query))
                                        {
                                            $id=$row['id'];
                                            $nombrePago=$row['nombrePago'];
                                            echo '<option readonly value="'.$id.'" >'.utf8_encode($nombrePago)."</option>";
                                        }?>
                                    </select>
                                </div>
                                <div class="col-md-2 col-6 linea<?= $i ?>" style="display: none; " ><!--MONTO DOLAR-->
                                    <input type="text" name="<?= 'montoDolar'.$i ?>" onClick="this.select();" onchange="MASK(this,this.value,'-##,###,##0.00',1); totalRecibo(); nuevaLin('<?= $i ?>',2)" onkeyup="bsXdolar('<?= $i ?>')" onkeypress="return ValMon(event)" style="text-align: right; " id="<?= 'montoDolar'.$i ?>" disabled class="form-control">
                                </div>
                                <div class="col-md-2 col-12 linea<?= $i ?>" style="display: none;"><!--MONTO BS-->
                                    <input type="text" class="form-control" readonly name="<?= 'montoBs'.$i ?>" id="<?= 'montoBs'.$i ?>" style="text-align: right; ">
                                </div>
                                <div class="col-md-1 col-2 linea<?= $i ?>" id="<?= 'btnTrash'.$i ?>" style="display: none; " >
                                    <button onclick="borraLinea('<?= $i ?>')" type="button" title='Borrar linea' class="btn btn-danger btn-circle" ><i class="fas fa-trash-alt fa-lg" ></i></button>
                                </div>
                                <div class="col-md-12" style="margin-top: 1%;"></div> <?php 
                            }?>
                        </div><?php 
                        if ($pago_va=='N') {?>
                            <script type="text/javascript">
                                document.getElementById("facturaForm").style.display = "none";
                                Swal.fire({
                                    icon: 'error',
                                    title: 'IMPORTANTE!',
                                    html: 'Este Periodo no esta activado para realizar facturas, por favor utilizar el sistema de facturacion local ',
                                    confirmButtonText:
                                    '<i class="fa fa-thumbs-up"></i> Entendido',
                                })
                            </script><?php
                        } ?>
                        <div class="form-row"><!--TOTALES-->
                            <div class="col-md-7 text-right">
                                <h4>Totales-></h4>
                            </div>
                            <div class="col-md-2">
                                <input type="text" readonly style="text-align: right; font-size: 18px; " name="totalReciboDolar" id="totalReciboDolar" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <input type="text" readonly style="text-align: right; font-size: 18px; " name="totalReciboBs" id="totalReciboBs" class="form-control">
                            </div>
                        </div>
                        <div class="form-row" style="margin-top:1%;">
                            <div class="col-md-8" style="background-color: #C5CAE9; padding-bottom: 5px; ">
                                <label>Concepto por Pronto Pago <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top" title="Para aplicar pronto Pago debe realizar el descuento al mes beneficiado en la tabla de monto a pagar."></i></label>
                                <input type="text" class="form-control" name="prontoPagText" id="prontoPagText" value="Beneficio por pronto pago">
                            </div>
                            <div class="col-md-2" style="background-color: #C5CAE9; padding-bottom: 5px; ">
                                <label>Monto en $</label>
                                <input type="text" style="text-align: right; " onClick="this.select()" class="form-control" readonly name="prontoPagMonDol" id="prontoPagMonDol" value="0.00">
                            </div>
                            <div class="col-md-2" style="background-color: #C5CAE9; padding-bottom: 5px; ">
                                <label>Monto en Bs.</label>
                                <input type="text" readonly style="text-align: right; " id="prontoPagMonBs" class="form-control" value="0.00">
                            </div>
                            <div class="col-md-12">
                                <textarea class="form-control" rows="2" name="comentario" placeholder="Si desea colocar un comentario interno del recibo puede hacerlo en esta area." id="comentario"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 text-center" style="background-color: #5499C7; color: black; margin-top: 1%; "><h4>Datos del Pago</h4></div>
                        <div class="form-row" style="margin-bottom: 1%; ">
                            <div class="col-md-4 offset-md-2 bg-gradient-success" id="divDolar" style="padding: 5px; color: black; ">
                                <label>Total Efectivo en Dolares ($)</label>
                                <input type="text" name="totalDolar" style="text-align: center; font-size: 18px; " id="totalDolar" class="form-control" value="0.00" readonly>
                            </div>
                            <div class="col-md-4 bg-gradient-danger" id="divBolivar" style="color:black; padding: 5px; ">
                                <label>Total Efectivo en Bolivares (Bs.)</label>
                                <input type="text" name="totalBolivar" style="text-align: center; font-size: 18px; " id="totalBolivar" class="form-control" value="0.00" readonly>
                            </div>    
                        </div>
                        <div class="bg-gradient-light" id="divTransf" style="display: none; padding: 5px; color: black; "><!--TRANSF-->
                            <div class="form-row">
                                <div class="col-md-12"><h4>Datos de la Transferencia</h4></div>
                                <div class="col-md-3">
                                    <label>Banco:</label>
                                    <select name="bancoTransf" id="bancoTransf" class="form-control" onchange="transRefe()">
                                        <option value="">Seleccione</option><?php 
                                        $transf_query = mysqli_query($link,"SELECT cod_banco,nom_banco,cuenta_nro FROM bancos WHERE banco_mio='X'");
                                        while($row = mysqli_fetch_array($transf_query))
                                        {
                                            $cod_banco=$row['cod_banco'];
                                            $nom_banco=$row['nom_banco'];
                                            $cuenta_nro=$row['cuenta_nro'];
                                            echo '<option readonly value="'.$cod_banco.'" >'.utf8_encode($nom_banco).' '.$cuenta_nro."</option>";
                                        }?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Monto Bs.</label>
                                    <input type="text" name="montoTransf" id="montoTransf" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Referencia</label>
                                    <input type="text" readonly onblur="buscaTran()" name="nroTransf" id="nroTransf" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Fecha Transferencia</label>
                                    <input type="date" name="fechaTransf" id="fechaTransf" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-primary" id="divDebito" style="display: none; padding: 5px; color: black; "><!--DEBITO-->
                            <div class="form-row">
                                <div class="col-md-12"><h4>Datos Tarjeta Debito</h4></div>
                                <div class="col-md-3">
                                    <label>Banco:</label>
                                    <select name="bancoDebito" id="bancoDebito" class="form-control">
                                        <option value="">Seleccione</option><?php 
                                        $debito_query = mysqli_query($link,"SELECT cod_banco,nom_banco,cuenta_nro FROM bancos WHERE banco_mio='X'");
                                        while($row = mysqli_fetch_array($debito_query))
                                        {
                                            $cod_banco=$row['cod_banco'];
                                            $nom_banco=$row['nom_banco'];
                                            $cuenta_nro=$row['cuenta_nro'];
                                            echo '<option readonly value="'.$cod_banco.'" >'.utf8_encode($nom_banco).' '.$cuenta_nro."</option>";
                                        }?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Monto Bs.</label>
                                    <input type="text" name="montoDebito" id="montoDebito" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Referencia</label>
                                    <input type="text" name="nroDebito" id="nroDebito" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Fecha Pago Movil</label>
                                    <input type="date" value="<?= $fechaHoy ?>" name="fechaDebito" id="fechaDebito" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-info" id="divPagMovil" style="display: none; color: black; padding: 5px;"><!--P.MOV-->
                            <div class="form-row">
                                <div class="col-md-12"><h4>Datos del Pago Movil</h4></div>
                                <div class="col-md-3">
                                    <label>Banco:</label>
                                    <select name="bancoPagMov" onchange="movilRefe()" id="bancoPagMov" class="form-control">
                                        <option value="">Seleccione</option><?php 
                                        $p_movil_query = mysqli_query($link,"SELECT cod_banco,nom_banco,cuenta_nro FROM bancos WHERE banco_mio='X'");
                                        while($row = mysqli_fetch_array($p_movil_query))
                                        {
                                            $cod_banco=$row['cod_banco'];
                                            $nom_banco=$row['nom_banco'];
                                            $cuenta_nro=$row['cuenta_nro'];
                                            echo '<option readonly value="'.$cod_banco.'" >'.utf8_encode($nom_banco).' '.$cuenta_nro."</option>";
                                        }?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Monto Bs.</label>
                                    <input type="text" name="montoPagMovil" id="montoPagMovil" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Referencia</label>
                                    <input type="text" readonly onblur="buscaMovil()" name="nroPagMovil" id="nroPagMovil" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>Fecha Pago Movil</label>
                                    <input type="date" name="fechaPagMovil" id="fechaPagMovil" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-center" style="background-color: #5499C7; color: black; margin-top: 1%; " id="tituloEmite"><h4>Emitir Recibo a Nombre de:</h4></div>
                        <div class="form-row" style="margin-bottom: 1%; ">
                            <div class="col-md-5">
                                <label>Nombre o Razón Social</label>
                                <input type="text" class="form-control" name="reciboNombre" id="reciboNombre" value="<?= $nom_reci ?>">
                            </div>
                            <div class="col-md-2">
                                <label>Cedula o RIF</label>
                                <input type="text" class="form-control" name="reciboRif" id="reciboRif" value="<?= $ced_reci ?>">
                            </div>
                            <div class="col-md-5">
                                <label>Dirección</label>
                                <input type="text" class="form-control" name="reciboDire" id="reciboDire" value="<?= $dir_reci ?>">
                            </div>
                        </div>
                        <input type="hidden" name="quien_paga" value="<?= encriptar($id_quienPaga) ?>">
                        <input type="hidden" id="idAlum" name="idAlum" value="<?= encriptar($idAlum) ?>">
                        <input type="hidden" name="tablaFactura" value="<?= $tablaPeriodo ?>" id="tablaFactura" >
                        <input type="hidden" name="pagado" id="pagado" value="<?= $pagado ?>">
                        <input type="hidden" name="morosida" id="morosida" value="<?= $morosida ?>">
                        <input type="hidden" name="totalPeriodo" id="totalPeriodo" value="<?= $totalPeriodo ?>">
                        <input type="hidden" name="pagaTransf" id="pagaTransf">
                        <input type="hidden" name="pagaDebito" id="pagaDebito">
                        <input type="hidden" name="pagaPagMov" id="pagaPagMov">
                        <input type="hidden" id="debeAgosto" value="<?= number_format($montoAgosto-$agosto,2,'.',',') ?>">
                        <input type="hidden" id="pagoAgosto" value="<?= $agosto ?>">
                        <input type="hidden" name="cedula" value="<?= $cedula ?>">
                        <input type="hidden" name="alumno" value="<?= $nombre ?>">
                        <input type="hidden" name="nombreGrado" value="<?= $nomGrado ?>">
                        <input type="hidden" name="linea" id="linea">
                        <input type="hidden" id="grado" name="grado" value="<?= $grado ?>">
                        <!--div class="col-md-12" style="background-color:#F7DC6F; color: black;">
                            <h3>Indique el tipo de papel para la impresión</h3>    
                        </div>
                        <div class="row" style="margin-bottom: 1%;">
                            <div class="col-md-4">
                                <label style="font-size: 22px;">Hoja en Blanco&nbsp;&nbsp;
                                <input type="hidden" name="salida" value="1" style="transform: scale(1.5);"></label>
                            </div>
                            <div class="col-md-4">
                                <label style="font-size: 22px;">Formato Litografia&nbsp;&nbsp;
                                <input type="radio" checked name="salida" value="2" style="transform: scale(1.5);"></label>
                            </div>
                        </div-->
                        <input type="hidden" name="salida" value="2">
                        <div class="form-row" style="margin-bottom:2%;"><!--BOTONES IMPRIMIR Y CERRAR-->
                            <div class="col-md-4 offset-md-2">
                                <button class="btn btn-success" id="btnPrint" type="submit" style="width:100%;" disabled ><i class="fas fa-print fa-sm"></i> Procesar Recibo</button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" style="width: 100%; color: black; " class="btn btn-warning" onclick="javascript:window.close();opener.window.focus();"><i class="fas fa-reply fa-sm"></i> Cerrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><?php 
}?>
<div class="modal fade" id="completaDato" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title">Completar Datos Requeridos: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Correo Estudiante</label>
                            <input type="email" id="mail_alum" placeholder="Ingrese un correo GMAIL necesario para recuperar clave" class="form-control" value="<?= $mai_alu ?>">
                        </div>
                        <div class="col-md-12">
                            <label>Correo Representante</label>
                            <input type="email" id="mail_rep" placeholder="Ingrese un correo GMAIL necesario para cobranza y comunicados" class="form-control" value="<?= $mai_rep ?>">
                        </div>
                        <div class="col-md-12">
                            <label>Celular Representante</label>
                            <input type="text" id="cel_rep" class="form-control" value="<?= $cel_rep ?>" placeholder="Ingrese Whatsapp necesario para cobranza y comunicados">
                        </div>
                        <div class="col-md-12" style="margin-top:1%;">
                            <p style="text-align: justify; ">Nota: Estos datos son requeridos para el buen funcionamiento del sistema de facturación, por favor completarlos!</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">No Actualizar</button>
                <button type="button" onclick="actualDato()" class="btn btn-danger">Actualizar</button>
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
                <button type="button" onclick="reimprimeRecibo()" id="btnPrint2" class="btn btn-info"><i class="fas fa-print fa-sm"></i> Reimprimir</button>
            </div>
            <!--input type="hidden" id="recibo"-->
            <input type="hidden" id="tablaPeriodo" value="<?= $tablaPeriodo ?>">
            <input type="hidden" id="linea">
            <input type="hidden" id="sale">
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
<div class="modal fade" id="detalleMsj" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                            <h4>Cargando Detalle Espere...</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                
            </div>
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
        $('#emisionFactura').addClass("active");
        $(".linea1"). css("display", "block")
        if (screen.width<600) {
            $("#btnTrash1"). css("display", "none")
        }
        //$("#montoDolar1").prop('disabled', false);
    });
    function actualDato() {
        maiAlu=$('#mail_alum').val()
        maiRep=$('#mail_rep').val()
        celRep=$('#cel_rep').val()
        cedRep=$('#ced_rep').val()
        id=$('#idAlum').val()
        $.post('actual-dato-ajax.php',{'idAl':id,'mailAlum':maiAlu,'mailRep':maiRep,'celuRep':celRep,'ceduRep':cedRep},function(data)
        {
            if(data.isSuccessful)
            {
                $('#completaDato').modal('hide')
                Swal.fire({
                    icon: 'success',
                    title: 'EXCELENTE!',
                    html: 'Datos requeridos actualizados correctamente...',
                    confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Entendido',
                })
            }else{
                $('#completaDato').modal('hide')
                Swal.fire({
                    icon: 'info',
                    title: 'Información!',
                    html: 'Datos requeridos NO fueron actualizados completamente...',
                    confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Entendido',
                })
            } 
        }, 'json'); 
    }
    function montoVie(mon) {
        $('#montoViejo').val(mon)
    }
    function prontoPag(mon) {
        Swal.fire({
            title: 'Agregar monto a Pronto Pago?',
            text: "",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI',
            cancelButtonText: 'NO'
        }).then((result) => {
            if (result.isConfirmed) {
                tasa=$('#tasaDolar').val()
                vie=$('#montoViejo').val()
                monPP=$('#prontoPagMonDol').val()
                if(monPP>0){monPP=parseFloat(monPP)-parseFloat(vie)}
                nueMon=parseFloat(monPP)+parseFloat(mon)
                nueMonBs=nueMon*tasa
                $('#prontoPagMonDol').val(nueMon.toFixed(2))
                $('#prontoPagMonBs').val(nueMonBs.toFixed(2))
            }
        })
    }
    function actualDesc(nro) {
        id=$('#idAlum').val()
        tabla=$('#tablaFactura').val()
        gra=$('#grado').val()
        mon=$('#desc'+nro).val()
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Almacenando descuento Espere...',
            showConfirmButton: false
        })
        $.post('actual-desc-ajax.php',{'idAl':id,'tab':tabla,'numero':nro,'grado':gra,'monto':mon},function(data)
        {
            if(data.isSuccessful)
            {
                if(nro==1){
                    swal.close();  
                }
            } 
        }, 'json'); 
    }
    function guardaConvenio() {
        idAlu=$('#idAlum').val()
        tabla=$('#tablaFactura').val()
        conve=$('#convenio').val()
        gra=$('#grado').val()
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Almacenando convenio Espere...',
            showConfirmButton: false
        })
        $.post('convenio-guarda-ajax.php',{'idAl':idAlu,'tab':tabla,'convenio':conve,'grado':gra},function(data)
        {
            if(data.isSuccessful)
            {
                swal.close();
            } 
        }, 'json'); 
    }
    function transRefe() {
        ban=$('#bancoTransf').val()
        if(ban>0){$("#nroTransf").removeAttr("readonly");}else 
        {$("#nroTransf").attr("readonly","readonly");}
    }
    function movilRefe() {
        ban=$('#bancoPagMov').val()
        if(ban>0){$("#nroPagMovil").removeAttr("readonly");}else 
        {$("#nroPagMovil").attr("readonly","readonly");}
    }
    function buscaTran() {
        ban=$('#bancoTransf').val()
        nro=$('#nroTransf').val()
        $.post('buscaTrans.php',{'banco':ban,'oper':nro},function(data)
        {
            if(data.isSuccessful){
                fec=data.fecha;
                alu=data.alumno;
                usu=data.usuario;
                rec=data.recibo;
                Swal.fire({
                    icon: 'error',
                    title: 'INFORMACION IMPORTANTE!',
                    html: 'El numero de referencia ya fue utilizado en otro recibo!<br>En fecha: '+fec+'<br>Alumno: '+alu+'<br>Procesado por: '+usu+'<br>Recibo nro. '+rec,
                    confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Entendido',
                })
            }else
            {
                
            }
        }, 'json');
    }
    function buscaMovil() {
        ban=$('#bancoPagMov').val()
        nro=$('#nroPagMovil').val()
        $.post('buscaMovil.php',{'banco':ban,'oper':nro},function(data)
        {
            if(data.isSuccessful){
                fec=data.fecha;
                alu=data.alumno;
                usu=data.usuario;
                rec=data.recibo;
                Swal.fire({
                    icon: 'error',
                    title: 'INFORMACION IMPORTANTE!',
                    html: 'El numero de referencia ya fue utilizado en otro recibo!<br>En fecha: '+fec+'<br>Alumno: '+alu+'<br>Procesado por: '+usu+'<br>Recibo nro. '+rec,
                    confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Entendido',
                })
            }else
            {
                
            }
        }, 'json');
    }
    function todasForma() {
        son=$('#formaGen').val()
        $('#fpag1').val(son)
        $('#fpag2').val(son)
        $('#fpag3').val(son)
        $('#fpag4').val(son)
        $('#fpag5').val(son)
        $('#fpag6').val(son)
        $('#fpag7').val(son)
        $('#fpag8').val(son)
        $('#fpag9').val(son)
        $('#fpag10').val(son)
        for (var i = 1; i < 11; i++) {
            if ($('#fpag'+i).val()==3) {
                $("#divTransf"). css("display", "block");
                document.querySelector('#nroTransf').required = true;
                document.querySelector('#fechaTransf').required = true;
                $('#pagaTransf').val(1)
            }else
            {
                $("#divTransf"). css("display", "none");
                document.querySelector('#nroTransf').required = false;
                document.querySelector('#fechaTransf').required = false;
                $('#pagaTransf').val('')
            }
            if ($('#fpag'+i).val()==4) {
                $("#divDebito"). css("display", "block");
                document.querySelector('#nroDebito').required = true;
                document.querySelector('#fechaDebito').required = true;
                $('#pagaDebito').val(1)
            }else
            {
                $("#divDebito"). css("display", "none");
                document.querySelector('#nroDebito').required = false;
                document.querySelector('#fechaDebito').required = false;
                $('#pagaDebito').val('')
            }
            if ($('#fpag'+i).val()==5) {
                $("#divPagMovil"). css("display", "block");
                document.querySelector('#nroPagMovil').required = true;
                document.querySelector('#fechaPagMovil').required = true;
                $('#pagaPagMov').val(1)
            }else
            {
                $("#divPagMovil"). css("display", "none");
                document.querySelector('#nroPagMovil').required = false;
                document.querySelector('#fechaPagMovil').required = false;
                $('#pagaPagMov').val('')
            }
        }
        totalRecibo()
    }
    function reimprimeRecibo() {
        reci=$('#reciboPrint').val()
        sale=$('#sale').val()
        window.open("factura-reimprime-pdf.php?recibo="+reci+"&sale="+sale)
    }
    function limpiar() {
        setTimeout(function(){
            window.parent.location.reload();
        }, 2000);
    }
    function validacion() 
    {
        var bancoT = document.getElementById("bancoTransf");
        var bancoB = document.getElementById("bancoDebito");
        var bancoP = document.getElementById("bancoPagMov");
        if (bancoT.value.length == 0 && $('#pagaTransf').val()>0 )
        {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Seleccione el banco de la transferencia',
                showConfirmButton: false,
                timer: 2500
            })
            return false;
        }
        if (bancoB.value.length == 0 && $('#pagaDebito').val()>0 )
        {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Seleccione el banco del pago con Tarjeta de Debito',
                showConfirmButton: false,
                timer: 2500
            })
            return false;
        }
        if (bancoP.value.length == 0 && $('#pagaPagMov').val()>0 )
        {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Seleccione el banco del pago movil',
                showConfirmButton: false,
                timer: 2500
            })
            return false;
        }
        for (var i = 1; i <=10; i++) {
            det=$('#detalle'+i).val()
            dol=$('#montoDolar'+i).val()
            if(dol>0 && det==''){
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Verifique detalle vacio en concepto linea #'+i,
                    showConfirmButton: false,
                    timer: 3500
                })
                return false;
            }
        }
        limpiar()
    }
    function actualTasa() {
        tasa=$('#tasaDolar').val()
        monMes=$('#montoMes').val()
        $.post('tasa-actual-ajax.php',{'tasa':tasa,'mes':monMes},function(data)
        {
            if(data.isSuccessful){
                $('#montoMesBs').val(data.montoBs)
                Swal.fire({
                    icon: 'success',
                    title: 'Tasa del día',
                    text: 'El monto de la tasa ha sido actualizado!',
                    confirmButtonText:
                    '<i class="fa fa-thumbs-up"></i> Entendido',
                })
            }else
            {
                
            }
        }, 'json');
    }
    function bsXdolar(lin) {
        dola=$('#montoDolar'+lin).val()
        monMes=$('#montoMes').val()*3
        afe=$('#afecta'+lin).val()
        if(dola>monMes && afe=='S')
        {
            $('#montoDolar'+lin).val(monMes)
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'El monto por linea no puede ser superior a la suma de 3 mensualidades',
                showConfirmButton: false,
                timer: 2500
            })
        }
        boliv=parseFloat(dola)*parseFloat($('#tasaDolar').val())
        if(boliv>0){$('#montoBs'+lin).val(boliv.toFixed(2))}else{$('#montoBs'+lin).val('0.00')}
    }
    function muestraForma(lin) {
        fpag='fpag'
        for (var i = 1; i < 11; i++) {
            window[fpag+i] = $('#fpag'+i).val();
        }
        if(fpag1=='3'||fpag2=='3'||fpag3=='3'||fpag4=='3'||fpag5=='3'||fpag6=='3'||fpag7=='3'||fpag8=='3'||fpag9=='3'||fpag10=='3')
        {
            $("#divTransf"). css("display", "block");
            document.querySelector('#nroTransf').required = true;
            document.querySelector('#fechaTransf').required = true;
            $('#pagaTransf').val(1)
        }else
        {
            $("#divTransf"). css("display", "none");
            document.querySelector('#nroTransf').required = false;
            document.querySelector('#fechaTransf').required = false;
            $('#pagaTransf').val('')
        }
        if(fpag1=='4'||fpag2=='4'||fpag3=='4'||fpag4=='4'||fpag5=='4'||fpag6=='4'||fpag7=='4'||fpag8=='4'||fpag9=='4'||fpag10=='4')
        {
            $("#divDebito"). css("display", "block");
            document.querySelector('#nroDebito').required = true;
            document.querySelector('#fechaDebito').required = true;
            $('#pagaDebito').val(1)
        }else
        {
            $("#divDebito"). css("display", "none");
            document.querySelector('#nroDebito').required = false;
            document.querySelector('#fechaDebito').required = false;
            $('#pagaDebito').val('')
        }
        if(fpag1=='5'||fpag2=='5'||fpag3=='5'||fpag4=='5'||fpag5=='5'||fpag6=='5'||fpag7=='5'||fpag8=='5'||fpag9=='5'||fpag10=='5')
        {
            $("#divPagMovil"). css("display", "block");
            document.querySelector('#nroPagMovil').required = true;
            document.querySelector('#fechaPagMovil').required = true;
            $('#pagaPagMov').val(1)
        }else
        {
            $("#divPagMovil"). css("display", "none");
            document.querySelector('#nroPagMovil').required = false;
            document.querySelector('#fechaPagMovil').required = false;
            $('#pagaPagMov').val('')
        }
    }
    function datos(lin) {
        conce=$('#conce'+lin).val()
        gra=$('#grado').val()
        id=$("#conce"+lin+" option:selected").attr('data-id')
        mon=$("#conce"+lin+" option:selected").attr('data-monto')
        afe=$("#conce"+lin+" option:selected").attr('data-afecta')
        abr=$("#conce"+lin+" option:selected").attr('data-abrev')
        ago=$("#conce"+lin+" option:selected").attr('data-ago')
        escribe=$("#conce"+lin+" option:selected").attr('data-escribe')
        abonos=$("#conce"+lin+" option:selected").attr('data-abono')
        $('#montoDolar'+lin).val(mon)    
        $('#id_concepto'+lin).val(id)
        $('#afecta'+lin).val(afe)
        $('#abrev'+lin).val(abr)
        $('#agosto'+lin).val(ago)
        if(escribe=='S')
        { $("#detalle"+lin).removeAttr("readonly"); }else{ $("#detalle"+lin).attr("readonly","readonly"); }
        if(abonos=='S')
        { $("#montoDolar"+lin).removeAttr("readonly"); }else{ $("#montoDolar"+lin).attr("readonly","readonly"); }
        if(ago=='S'){
            tabla=$('#tablaFactura').val()
            idAl=$('#idAlum').val()
            $.post('agosto-ajax.php',{'id':id,'tabl':tabla,'idAlu':idAl,'grado':gra},function(data)
            {
                if(data.isSuccessful)
                {
                    //$('#').val(data.pagAgo)
                    $('#debeAgosto').val(data.debeAgo)
                    $('#montoDolar'+lin).val(data.debeAgo)
                    $('#detalle'+lin).val('Paga '+conce)
                    totalRecibo()
                }
            }, 'json');
        }
        if(abr=='' ){$('#detalle'+lin).val(conce)}
        lin2=parseFloat(lin)+1
        if(conce!=''){ $(".linea"+lin2). css("display", "block") }
        boliv=parseFloat(mon)*parseFloat($('#tasaDolar').val())
        if(boliv>0){$('#montoBs'+lin).val(boliv.toFixed(2))}else{$('#montoBs'+lin).val('0.00')}
        if(id>0){$("#montoDolar"+lin).prop('disabled', false);}
    }
    function nuevaLin(lin,donde) {
        pag=parseFloat($('#pagado').val())
        tot=$('#totalPeriodo').val()
        van=0;
        idAlu=$('#idAlum').val()
        monLin=$('#montoDolar'+lin).val()
        tabla=$('#tablaFactura').val()
        $('#linea').val(lin)
        ago=$('#agosto'+lin).val()
        pagadoAgo=parseFloat($('#pagoAgosto').val())
        for (var i = 1; i < 11; i++) {
            afe=$('#afecta'+i).val()
            if(afe=='S')
            {
                van=van+parseFloat($('#montoDolar'+i).val())   
            }
        }
        todo=pag+van+pagadoAgo
        if(todo>tot)
        {
            $('#montoDolar'+lin).val('0.00')
            totalRecibo()
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'El monto ingresado es superior a la deuda del periodo!'
            })
        }else
        {
            if(monLin>0)
            {
                Swal.fire({
                  position: 'center',
                  icon: 'info',
                  title: 'Generando detalle Espere...',
                  showConfirmButton: false
                })
            }
            //$('#pagado').val(parseFloat(pag)+parseFloat(mon))
            $('#pagado').val(parseFloat(pag))
            lin2=parseFloat(lin)+1
            $(".linea"+lin2). css("display", "block") 
            if (screen.width<1025) {
                $("#btnTrash"+lin2). css("display", "none")
            }
            if($('#afecta'+lin).val()=='S')
            {
                $.post('detalle-ajax.php',$("#facturaForm").serialize(),function(data)
                {
                    if(data.isSuccessful)
                    {
                        $.each(data.detalle,function(index,value){
                            //console.log(index);
                            if(value != ''){
                                $('#detalle'+index).val(value);
                            }
                        }); 
                        swal.close();
                    }else
                    {
                      swal.close();
                    }
                }, 'json');
            }else 
            {
                if(ago!='S'){
                    idCon=$("#conce"+lin+" option:selected").attr('data-id')
                    conce=$('#conce'+lin).val()
                    $.post('restaMonto-ajax.php',{'idA':idAlu,'idC':idCon,'tab':tabla,'mont':monLin},function(data)
                    {
                        if(data.isSuccessful)
                        {
                            swal.close();
                            if(data.debeMonto==2 && data.nroPag==1)
                            {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'El concepto de '+conce+' ya fue pagado totalmente verifique en el historial'
                                })
                                $('#montoDolar'+lin).val(0)
                                $('#conce'+lin).val('')
                                $('#detalle'+lin).val('')
                            }else 
                            {
                                $('#detalle'+lin).val(data.conceNue);
                                $('#montoDolar'+lin).val(data.pagar)
                            }
                            
                          //$('#').val(data.)
                        }
                    }, 'json');
                }else{swal.close();}
            }
        }
        if(monLin==0 && donde==2)
        {
            $('#fpag'+lin).val(1)
            $('#conce'+lin).val('')
            $('#detalle'+lin).val('')
        }
        
        if(ago=='S')
        {
            pagAgo=$('#montoDolar'+lin).val()
            debAgo=$('#debeAgosto').val()
            resAgo=debAgo-pagAgo
            if(resAgo<0)
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El monto ingresado es superior a la deuda de Agosto!'
                })
                $('#montoDolar'+lin).val(debAgo)
            }else 
            {
                if (resAgo>0) 
                {
                    $('#detalle'+lin).val('Abona Agosto R. ('+resAgo.toFixed(2)+')')    
                }else 
                {
                    $('#detalle'+lin).val('Paga Agosto ')    
                }
                
            }
        }
    }
    function borraLinea(lin) {
        if (lin>1) 
        {
            $(".linea"+lin). css("display", "none")
            
        }
        $('#montoDolar'+lin).val('')
        $("#montoDolar"+lin).prop('disabled', true);
        $('#fpag'+lin).val(1)
        $('#conce'+lin).val('')
        $('#detalle'+lin).val('')
        fpag='fpag'
        for (var i = 1; i < 11; i++) {
            window[fpag+i] = $('#fpag'+i).val();
        }
        if(fpag1=='3'||fpag2=='3'||fpag3=='3'||fpag4=='3'||fpag5=='3'||fpag6=='3'||fpag7=='3'||fpag8=='3'||fpag9=='3'||fpag10=='3'){$("#divTransf"). css("display", "block");}else{$("#divTransf"). css("display", "none");}
        if(fpag1=='4'||fpag2=='4'||fpag3=='4'||fpag4=='4'||fpag5=='4'||fpag6=='4'||fpag7=='4'||fpag8=='4'||fpag9=='4'||fpag10=='4'){$("#divDebito"). css("display", "block");}else{$("#divDebito"). css("display", "none");}
        if(fpag1=='5'||fpag2=='5'||fpag3=='5'||fpag4=='5'||fpag5=='5'||fpag6=='5'||fpag7=='5'||fpag8=='5'||fpag9=='5'||fpag10=='5'){$("#divPagMovil"). css("display", "block");}else{$("#divPagMovil"). css("display", "none");}
        totalRecibo()
    }
    function totalRecibo() {
        var suma=0; totTra=0; totMov=0; totDeb=0; totDiv=0; totBol=0;
        for (var i = 1; i < 11; i++) {
            monto=$('#montoDolar'+i).val()
            if(monto>0){suma=suma+parseFloat(monto)}
            bsLin=monto*parseFloat($('#tasaDolar').val())
            if(bsLin>0){$('#montoBs'+i).val(bsLin.toFixed(2))}else{$('#montoBs'+i).val('0.00')}
            if($('#fpag'+i).val()=='3'){totTra=totTra+parseFloat(bsLin)}
            if($('#fpag'+i).val()=='4'){totDeb=totDeb+parseFloat(bsLin)}
            if($('#fpag'+i).val()=='5'){totMov=totMov+parseFloat(bsLin)}
            if($('#fpag'+i).val()=='1'){if (monto>0) {totDiv=totDiv+parseFloat(monto)} }
            if($('#fpag'+i).val()=='2'){totBol=totBol+parseFloat(bsLin)}
        }
        $('#montoTransf').val(totTra.toFixed(2))
        $('#montoDebito').val(totDeb.toFixed(2))
        $('#montoPagMovil').val(totMov.toFixed(2))
        $('#totalDolar').val(totDiv.toFixed(2))
        $('#totalBolivar').val(totBol.toFixed(2))
        totBs=suma*parseFloat($('#tasaDolar').val())
        $('#totalReciboDolar').val(suma.toFixed(2));
        if(suma>0)
        {
            $('#totalReciboBs').val(totBs.toFixed(2))
            $("#btnPrint").prop('disabled', false);
        }else
        {
            $('#totalReciboBs').val('0.00')
            $("#btnPrint").prop('disabled', true);
        }
    }
    function fnShowSecciones(div,btn) 
    {
        $(div).slideToggle();
        $(btn).toggleClass("fas fa-chevron-right");
        $(btn).toggleClass("fas fa-chevron-down");
    }
    function verPago(reci,lin,sale)
    {
        peri=$('#tablaPeriodo').val()
        $("#cuerpo").html("");
        $('#recibo').val(reci);
        $('#sale').val(sale);
        $('#linea').val(lin);
        $.post('../procesos/historia-buscar.php',{'recib':reci,'tabla':peri,'salio':sale},function(data)
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
                    $('#btnPrint2').hide();
                    $('#btnAnula').hide();
                    $('#btnRecupera').show();
                    document.getElementById('msjNulo').style.display = 'block';
                } else 
                {
                    $('#btnPrint2').show();
                    $('#btnAnula').show();
                    $('#btnRecupera').hide();
                    document.getElementById('msjNulo').style.display = 'none';
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
        $.post('../procesos/historia-anula.php',{'recib':reci,'tabla':peri,'motivo':mot,'grado':gra },function(data)
        {
            if(data.isSuccessful)
            {
                window.parent.location.reload();
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
        $.post('../procesos/historia-recupera.php',{'recib':reci,'tabla':peri,'motivo':mot,'grado':gra },function(data)
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
include_once "../include/footer.php";
if(empty($mai_alu) || empty($mai_rep) || empty($cel_rep)){ ?>
    <script type="text/javascript">
        $('#completaDato').modal('show');
    </script><?php
}
mysqli_free_result($tasa_query);
mysqli_free_result($puede_query);
mysqli_free_result($agosto_query);
mysqli_free_result($datos_query);
mysqli_free_result($matri_query);
mysqli_free_result($montos_query);
mysqli_free_result($pagos_query);
mysqli_free_result($peri2);
mysqli_free_result($select1);
mysqli_free_result($fpag_query);
mysqli_free_result($transf_query);
mysqli_free_result($debito_query);
mysqli_free_result($p_movil_query);
 ?>