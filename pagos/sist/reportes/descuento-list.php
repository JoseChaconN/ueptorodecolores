<?php
include_once "../include/header.php";
$link = Conectarse();

if(!isset($_POST['gradoVer']) && !isset($_POST['secciVer']))
{
    $periodo_query = mysqli_query($link,"SELECT nombre_periodo,tablaPeriodo FROM periodos WHERE activoPeriodo='1' ");
    while($row = mysqli_fetch_array($periodo_query))
    {
        $nombre_periodo=$row['nombre_periodo'];
        $tablaPeriodo=$row['tablaPeriodo'];
    }
    $grado1_query = mysqli_query($link,"SELECT grado, nombreGrado FROM grado".$tablaPeriodo." ORDER BY grado LIMIT 1 ");
    while($row = mysqli_fetch_array($grado1_query))
    {
        $gradoVer=$row['grado'];
    }
    $secci1_query = mysqli_query($link,"SELECT * FROM secciones ORDER BY id LIMIT 1 ");
    while($row1 = mysqli_fetch_array($secci1_query))
    {
        $secciVer=$row1['id'];
    }
    
} else
{
    $nombre_periodo=$_POST['periodoVer'];
    $periodo_query = mysqli_query($link,"SELECT tablaPeriodo FROM periodos WHERE nombre_periodo='$nombre_periodo' ");
    while($row = mysqli_fetch_array($periodo_query))
    {
        $tablaPeriodo=$row['tablaPeriodo'];
    }
    
    $gradoVer = $_POST['gradoVer'];
    $secciVer = $_POST['secciVer'];
}
if($gradoVer==0)
{
    $query = mysqli_query($link,"SELECT A.idAlum, A.cedula as 'cedalu',A.nombre as 'nomalu', A.morosida, B.nombreGrado as 'nomgra', A.apellido, A.Periodo, E.statusAlum,E.pagado,E.suma_a_pagado,E.grado,E.desc1,E.desc2,E.desc3,E.desc4,E.desc5,E.desc6,E.desc7,E.desc8,E.desc9,E.desc10,E.desc11,E.desc12,E.desc13, C.nombre as 'nomsec' FROM alumcer A, grado".$tablaPeriodo." B, secciones C, notaprimaria".$tablaPeriodo." E WHERE E.grado<61 and E.idSeccion='$secciVer' and E.idAlumno=A.idAlum and E.grado=B.grado and E.idSeccion=C.id ORDER BY E.grado,E.idSeccion, A.apellido ASC ");   

    $query2 = mysqli_query($link,"SELECT A.idAlum, A.cedula as 'cedalu',A.nombre as 'nomalu', A.morosida, B.nombreGrado as 'nomgra', A.apellido, A.Periodo, E.statusAlum,E.pagado,E.suma_a_pagado,E.grado,E.desc1,E.desc2,E.desc3,E.desc4,E.desc5,E.desc6,E.desc7,E.desc8,E.desc9,E.desc10,E.desc11,E.desc12,E.desc13, C.nombre as 'nomsec' FROM alumcer A, grado".$tablaPeriodo." B, secciones C, matri".$tablaPeriodo." E WHERE E.grado>60 and E.idSeccion='$secciVer' and E.idAlumno=A.idAlum and E.grado=B.grado and E.idSeccion=C.id ORDER BY E.grado,E.idSeccion, A.apellido ASC ");   
}
if($gradoVer>40 && $gradoVer<61)
{
    $query = mysqli_query($link,"SELECT A.idAlum, A.cedula as 'cedalu',A.nombre as 'nomalu', A.morosida, B.nombreGrado as 'nomgra', A.apellido, A.Periodo, E.statusAlum,E.pagado,E.suma_a_pagado,E.grado,E.desc1,E.desc2,E.desc3,E.desc4,E.desc5,E.desc6,E.desc7,E.desc8,E.desc9,E.desc10,E.desc11,E.desc12,E.desc13, C.nombre as 'nomsec' FROM alumcer A, grado".$tablaPeriodo." B, secciones C, notaprimaria".$tablaPeriodo." E WHERE E.grado='$gradoVer' and E.idSeccion='$secciVer' and E.idAlumno=A.idAlum and E.grado=B.grado and E.idSeccion=C.id ORDER BY E.grado,E.idSeccion, A.apellido ASC ");
}
if($gradoVer>60)
{
    $query = mysqli_query($link,"SELECT A.idAlum, E.statusAlum,E.pagado,E.suma_a_pagado,E.grado,E.desc1,E.desc2,E.desc3,E.desc4,E.desc5,E.desc6,E.desc7,E.desc8,E.desc9,E.desc10,E.desc11,E.desc12,E.desc13, A.cedula as 'cedalu',A.nombre as 'nomalu', A.morosida, B.nombreGrado as 'nomgra', A.apellido, A.Periodo, C.nombre as 'nomsec' FROM alumcer A, grado".$tablaPeriodo." B, secciones C, matri".$tablaPeriodo." E WHERE E.grado='$gradoVer' and E.idSeccion='$secciVer' and E.idAlumno=A.idAlum and 
        E.grado=B.grado and E.idSeccion=C.id ORDER BY E.grado,E.idSeccion, A.apellido ASC ");
} ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9">
            <h1 class="h3 mb-2 text-gray-800">Listado de Alumnos con Descuento</h1>    
        </div>    
        <div class="col-md-3 col-xs-12 col-sm-12">
            <button type="button"  onclick='window.open("descuento-pdf.php?idG=<?= $gradoVer ?>&idS=<?= $secciVer ?>&peri=<?= $tablaPeriodo ?>&nomP=<?= $nombre_periodo ?>")' class="btn btn-primary" style="width: 100%;"><span class="fas fa-print fa-sm" ></span> Imprimir</button><br><br>
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <!--div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div-->
        <div class="card-body">
            <div class="table-responsive">
                <form role="form" method="POST" enctype="multipart/form-data" action="descuento-list.php">
                    <div class="form-row">
                        <div class="col-md-3 col-md-offset-md-3 col-12" style="margin-bottom:2%;">
                            <select name="periodoVer" onchange="pulsaBuscar()" class="form-control" id="periodoVer" ><?php
                                $periodo_query = mysqli_query($link,"SELECT tablaPeriodo,nombre_periodo FROM periodos WHERE pagos='S' ORDER BY nombre_periodo ");
                                while($row = mysqli_fetch_array($periodo_query))
                                {
                                    $nombre_periodo1=$row['nombre_periodo'];
                                    $periodoVer=$row['nombre_periodo'];
                                    $selected = ($nombre_periodo1==$nombre_periodo) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$nombre_periodo1.'">'.$nombre_periodo1."</option>";
                                }?>                                
                            </select>
                        </div>
                        <div class="col-md-3 col-md-offset-md-3 col-12" style="margin-bottom:2%;">
                            <select name="gradoVer" onchange="pulsaBuscar()" class="form-control" id="gradoVer">
                                <option value="0" <?php if($gradoVer==1){echo "selected";} ?>>Todos</option><?php
                                $gradoVer_query = mysqli_query($link,"SELECT grado, nombreGrado FROM grado".$tablaPeriodo." ORDER BY grado ");
                                while($row = mysqli_fetch_array($gradoVer_query))
                                {
                                    $nom_gradsd=($row['nombreGrado']);
                                    $id_gradsd=$row['grado'];
                                    $selected = ($id_gradsd==$gradoVer) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$id_gradsd.'">'.$nom_gradsd."</option>";
                                }?>                                
                            </select>
                        </div>
                        <div class="col-md-3 col-12" style="margin-bottom: 2%;">
                            <select name="secciVer" onchange="pulsaBuscar()" class="form-control" id="secciVer"><?php
                                $secciVer_query = mysqli_query($link,"SELECT * FROM secciones ORDER BY id ");
                                while($row1 = mysqli_fetch_array($secciVer_query))
                                {
                                    $nom_secdsd=utf8_encode($row1['nombre']);
                                    $id_secdsd=$row1['id'];
                                    $selected = ($id_secdsd==$secciVer) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$id_secdsd.'">'.$nom_secdsd."</option>";
                                }?>                                
                            </select>  
                        </div>
                        <div class="col-md-3 col-12">
                            <button type="submit" class="btn btn-info" style="width: 100%;"><span class="fas fa-search fa-sm" ></span> Buscar</button><br><br>
                        </div>
                    </div>
                </form>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cedula</th>
                            <th>Estudiante</th>
                            <th>Periodo</th>
                            <th>Pagado</th>
                            <th>Morosidad</th>
                            <th>Descuento</th>
                            <th>Botones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Cedula</th>
                            <th>Estudiante</th>
                            <th>Periodo</th>
                            <th>Pagado</th>
                            <th>Morosidad</th>
                            <th>Descuento</th>
                            <th>Botones</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row=mysqli_fetch_array($query)) 
                        {
                            $ced_alu=$row['cedalu'];
                            $alumno=$row['apellido'].' '.$row['nomalu'];
                            $nomgra=($row['nomgra']).' '.$row['nomsec'];
                            //$morosida=$row['morosida'];
                            $periodo=$row['Periodo'];
                            $statusAlum=$row['statusAlum'];
                            $idAlum=$row['idAlum'];
                            $pago=$row['pagado'];
                            $suma_a_pagado=$row['suma_a_pagado'];
                            $grado=$row['grado'];
                            //$=$row[''];
                            $btn_class = ($statusAlum == 1) ? 'btn btn-primary btn-circle' : 'btn btn-danger btn-circle';
                            $btn_i_class = ($statusAlum == 1) ? 'fas fa-check' : 'fas fa-lock';
                            $titulo = ($statusAlum== 1) ? 'ACTIVO' : 'ALUMNO DESACTIVADO EN ESTE PERIODO';
                            $sale=0; $descuento=0;
                            for ($i=1; $i <14 ; $i++) { 
                                ${'desc'.$i} = $row['desc'.$i];
                                $sale = ($row['desc'.$i]>0) ? $sale+1 : $sale ;
                                $descuento=$descuento+$row['desc'.$i];
                            }
                            if($sale>0)
                            {
                                $agosto_query = mysqli_query($link,"SELECT SUM(A.montoDolar) as monto FROM pagos".$tablaPeriodo." A, conceptos B WHERE A.idAlum ='$idAlum' and A.id_concepto=B.id and B.agosto='S' and A.statusPago='1' GROUP BY A.recibo "); 
                                $agosto=0;
                                while ($row = mysqli_fetch_array($agosto_query))
                                {
                                    $agosto=$agosto+$row['monto'];
                                }
                                $montos_query = mysqli_query($link,"SELECT monto,fecha_vence,mes,insc FROM montos".$tablaPeriodo." WHERE id_grado ='$grado' "); 
                                $deudatotal=0; $meses=0; $morosida=0; $exonera=0;
                                while ($row = mysqli_fetch_array($montos_query))
                                {
                                    $meses++;
                                    $deudatotal=$deudatotal+($row['monto']);
                                    ${'insc'.$meses} = $row['insc'];
                                    ${'mes'.$meses} = $row['mes'];
                                    ${'f_vence'.$meses} = $row['fecha_vence'];
                                    ${'monto'.$meses} = $row['monto'];
                                    if($row['fecha_vence']<$fechaHoy)
                                    {
                                        $morosida=$morosida+($row['monto']-${'desc'.$meses});
                                    }
                                }
                                $pagos_query = mysqli_query($link,"SELECT A.*,B.nombrePago,C.nom_banco,D.afecta FROM pagos".$tablaPeriodo." A, formas_pago B, bancos C, conceptos D WHERE A.idAlum = '$idAlum' and A.recibo <> '' and A.operacion=B.id and A.banco=C.cod_banco and A.id_concepto=D.id ORDER BY A.id ");
                                $pagado=0; $pagos=0;
                                while ($row = mysqli_fetch_array($pagos_query))
                                {
                                    if($row['statusPago']=='1' and $row['afecta']=='S' )
                                    {
                                        $pagado=$pagado+$row['montoDolar'];
                                        $pagos++;
                                    }
                                }
                                $pagado=$pagado+$agosto+$suma_a_pagado;
                                //$deudatotal=$deudatotal-$totDesc;
                                $morosida=$morosida-$pagado;
                                $morosida = ($morosida<0) ? 0 : $morosida ; ?>
                                <tr <?php if($gradoVer<3){ echo 'title="Cursante del: '.$nomgra.'"'; } ?>>
                                    <td><?= $son+=1; ?></td>
                                    <td><?= $ced_alu; ?></td>
                                    <input type="hidden" name="" id="nom_pac<?=$son?>" value="<?= $row["nombre"]; ?>">
                                    <input type="hidden" name="cedula" value="<?= $ced_alu ?>" id="ced<?=$son?>"><?php 
                                    if($grado<61)
                                    {?>
                                        <td style='cursor: pointer' onclick='window.open("../alumnos/perfil-pri-alumno.php?id=<?= encriptar($idAlum) ?>&peri=<?= $tablaPeriodo ?>&gra=<?= $gradoVer ?>&sec=<?= $secciVer ?>&nomP=<?= $periodoVer ?> ")'><?= $alumno ?></td><?php
                                    }else
                                    {?>
                                        <td style='cursor: pointer' onclick='window.open("../alumnos/perfil-alumno.php?id=<?= encriptar($idAlum) ?>&peri=<?= $tablaPeriodo ?>&gra=<?= $gradoVer ?>&sec=<?= $secciVer ?>&nomP=<?= $periodoVer ?> ")'><?= $alumno ?></td><?php
                                    }?>
                                    <td align="right"><?= number_format($deudatotal,2,',','.').' $' ?></td>
                                    <td align="right"><?= number_format($pagado,2,',','.').' $' ?></td>
                                    <td align="right"><?= number_format($morosida,2,',','.').' $' ?></td>
                                    <td align="right"><?= number_format($descuento,2,',','.').' $' ?></td>
                                    
                                    <td style="width:17%;">
                                        <div class="btn-group">
                                            <button onclick='window.open("../factura/facturar.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo.'&gra='.$grado ?>")' type="button" title='Facturar ' class="btn btn-success btn-circle" ><i class="fas fa-dollar-sign fa-lg" ></i></button>

                                            <button onclick='window.open("../procesos/historia-pagos.php?id=<?= encriptar($idAlum) ?>&peri=<?= $tablaPeriodo ?> ")' data-toggle="tooltip" data-placement="top" title="Historial de Pagos" type="button" class="btn btn-secondary btn-circle" ><i class="fas fa-folder fa-lg"></i></button><?php
                                            if( !empty($celular))
                                            { ?>
                                                <button onclick='window.open("https://api.whatsapp.com/send?phone=<?= '+58'.$celular ?>&text=Estimado(a)%20<?= $nomRep ?>%20junto%20con%20saludarle%20desde%20la%20U.E.P.%20<?= EKKS ?>%20me dirijo a usted muy respetuosamente para informarle su situación administrativa para con nuestra institución, recordándole que nuestros únicos ingresos para el mantenimiento de la institución dependen únicamente del pago oportuno de sus mensualidades, el monto vencido a la fecha es de $.<?= number_format($morosida,2,",",".") ?>. Agradeciendo de antemano solventar esta situación en un lapso de 24 horas. Atentamente la Administración.")' style="background-color: #43A047; color: #fff;" class="btn btn-success btn-circle" type="button" data-toggle="tooltip" data-placement="top" title="Mensaje al Whatsapp"><i class="fab fa-whatsapp fa-lg" ></i></button><?php
                                            } ?>
                                            <button  id="boton_<?= $son; ?>" title="<?= $titulo; ?>" onclick="statusAlum('<?= $idAlum ?>','<?= $son ?>','<?= $grado ?>')" type="button" class="<?= $btn_class ?>" ><i id="btnI_<?= $son; ?>" class="<?= $btn_i_class ?> " ></i></button>
                                        </div>
                                    </td>         
                                </tr><?php
                            }
                        }
                        if($gradoVer==0)
                        {
                            while($row=mysqli_fetch_array($query2)) 
                            {
                                $ced_alu=$row['cedalu'];
                                $alumno=$row['apellido'].' '.$row['nomalu'];
                                $nomgra=utf8_encode($row['nomgra']).' '.$row['nomsec'];
                                //$morosida=$row['morosida'];
                                $periodo=$row['Periodo'];
                                $statusAlum=$row['statusAlum'];
                                $idAlum=$row['idAlum'];
                                $pago=$row['pagado'];
                                $suma_a_pagado=$row['suma_a_pagado'];
                                $grado=$row['grado'];
                                //$=$row[''];
                                $btn_class = ($statusAlum == 1) ? 'btn btn-primary btn-circle' : 'btn btn-danger btn-circle';
                                $btn_i_class = ($statusAlum == 1) ? 'fas fa-check' : 'fas fa-lock';
                                $titulo = ($statusAlum== 1) ? 'ACTIVO' : 'ALUMNO DESACTIVADO EN ESTE PERIODO';
                                $sale=0; $descuento=0;
                                for ($i=1; $i <14 ; $i++) { 
                                    ${'desc'.$i} = $row['desc'.$i];
                                    $sale = ($row['desc'.$i]>0) ? $sale+1 : $sale ;
                                    $descuento=$descuento+$row['desc'.$i];
                                }
                                if($sale>0)
                                {
                                    $agosto_query = mysqli_query($link,"SELECT SUM(A.montoDolar) as monto FROM pagos".$tablaPeriodo." A, conceptos B WHERE A.idAlum ='$idAlum' and A.id_concepto=B.id and B.agosto='S' and A.statusPago='1' GROUP BY A.recibo "); 
                                    $agosto=0;
                                    while ($row = mysqli_fetch_array($agosto_query))
                                    {
                                        $agosto=$agosto+$row['monto'];
                                    }
                                    $montos_query = mysqli_query($link,"SELECT monto,fecha_vence,mes,insc FROM montos".$tablaPeriodo." WHERE id_grado ='$grado' "); 
                                    $deudatotal=0; $meses=0; $morosida=0; $exonera=0;
                                    while ($row = mysqli_fetch_array($montos_query))
                                    {
                                        $meses++;
                                        $deudatotal=$deudatotal+($row['monto']);
                                        ${'insc'.$meses} = $row['insc'];
                                        ${'mes'.$meses} = $row['mes'];
                                        ${'f_vence'.$meses} = $row['fecha_vence'];
                                        ${'monto'.$meses} = $row['monto'];
                                        if($row['fecha_vence']<$fechaHoy)
                                        {
                                            $morosida=$morosida+($row['monto']-${'desc'.$meses});
                                        }
                                    }
                                    $pagos_query = mysqli_query($link,"SELECT A.*,B.nombrePago,C.nom_banco,D.afecta FROM pagos".$tablaPeriodo." A, formas_pago B, bancos C, conceptos D WHERE A.idAlum = '$idAlum' and A.recibo <> '' and A.operacion=B.id and A.banco=C.cod_banco and A.id_concepto=D.id ORDER BY A.id ");
                                    $pagado=0; $pagos=0;
                                    while ($row = mysqli_fetch_array($pagos_query))
                                    {
                                        if($row['statusPago']=='1' and $row['afecta']=='S' )
                                        {
                                            $pagado=$pagado+$row['montoDolar'];
                                            $pagos++;
                                        }
                                    }
                                    $pagado=$pagado+$agosto+$suma_a_pagado;
                                    //$deudatotal=$deudatotal-$totDesc;
                                    $morosida=$morosida-$pagado;
                                    $morosida = ($morosida<0) ? 0 : $morosida ; ?>
                                    <tr <?php if($gradoVer<3){ echo 'title="Cursante del: '.$nomgra.'"'; } ?>>
                                        <td><?= $son+=1; ?></td>
                                        <td><?= $ced_alu; ?></td>
                                        <input type="hidden" name="" id="nom_pac<?=$son?>" value="<?= $row["nombre"]; ?>">
                                        <input type="hidden" name="cedula" value="<?= $ced_alu ?>" id="ced<?=$son?>"><?php 
                                        if($grado<61)
                                        {?>
                                            <td style='cursor: pointer' onclick='window.open("../alumnos/perfil-pri-alumno.php?id=<?= encriptar($idAlum) ?>&peri=<?= $tablaPeriodo ?>&gra=<?= $gradoVer ?>&sec=<?= $secciVer ?>&nomP=<?= $periodoVer ?> ")'><?= $alumno ?></td><?php
                                        }else
                                        {?>
                                            <td style='cursor: pointer' onclick='window.open("../alumnos/perfil-alumno.php?id=<?= encriptar($idAlum) ?>&peri=<?= $tablaPeriodo ?>&gra=<?= $gradoVer ?>&sec=<?= $secciVer ?>&nomP=<?= $periodoVer ?> ")'><?= $alumno ?></td><?php
                                        }?>
                                        <td align="right"><?= number_format($deudatotal,2,',','.').' $' ?></td>
                                        <td align="right"><?= number_format($pagado,2,',','.').' $' ?></td>
                                        <td align="right"><?= number_format($morosida,2,',','.').' $' ?></td>
                                        <td align="right"><?= number_format($descuento,2,',','.').' $' ?></td>
                                        
                                        <td style="width:17%;">
                                            <div class="btn-group">
                                                <button onclick='window.open("../factura/facturar.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo.'&gra='.$grado ?>")' type="button" title='Facturar ' class="btn btn-success btn-circle" ><i class="fas fa-dollar-sign fa-lg" ></i></button>

                                                <button onclick='window.open("../procesos/historia-pagos.php?id=<?= encriptar($idAlum) ?>&peri=<?= $tablaPeriodo ?> ")' data-toggle="tooltip" data-placement="top" title="Historial de Pagos" type="button" class="btn btn-secondary btn-circle" ><i class="fas fa-folder fa-lg"></i></button><?php
                                                if( !empty($celular))
                                                { ?>
                                                    <button onclick='window.open("https://api.whatsapp.com/send?phone=<?= '+58'.$celular ?>&text=Estimado(a)%20<?= $nomRep ?>%20junto%20con%20saludarle%20desde%20la%20U.E.P.%20<?= EKKS ?>%20me dirijo a usted muy respetuosamente para informarle su situación administrativa para con nuestra institución, recordándole que nuestros únicos ingresos para el mantenimiento de la institución dependen únicamente del pago oportuno de sus mensualidades, el monto vencido a la fecha es de $.<?= number_format($morosida,2,",",".") ?>. Agradeciendo de antemano solventar esta situación en un lapso de 24 horas. Atentamente la Administración.")' style="background-color: #43A047; color: #fff;" class="btn btn-success btn-circle" type="button" data-toggle="tooltip" data-placement="top" title="Mensaje al Whatsapp"><i class="fab fa-whatsapp fa-lg" ></i></button><?php
                                                } ?>
                                                <button  id="boton_<?= $son; ?>" title="<?= $titulo; ?>" onclick="statusAlum('<?= $idAlum ?>','<?= $son ?>','<?= $grado ?>')" type="button" class="<?= $btn_class ?>" ><i id="btnI_<?= $son; ?>" class="<?= $btn_i_class ?> " ></i></button>
                                            </div>
                                        </td>         
                                    </tr><?php
                                }
                            }
                        } ?>
                    </tbody>
                </table>
                <input type="hidden" id="tabla" value="<?= $tablaPeriodo ?>">
                <input type="hidden" id="nombre_periodo" value="<?= $nombre_periodo ?>" >
            </div>
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
            $('#listados').addClass("show");
        }
        $('#descuentoList').addClass("active");
    });
    function  statusAlum(idAlumno, Van,gra)
    {
        idA = idAlumno;
        tabPer=$('#tabla').val();
        $.post('../alumnos/statusAlumno.php',{'idAlu':idA, 'tabPer':tabPer,'grado':gra},function(data)
        {
            if(data.isSuccessful)
            {
              if(data.status=='1')
              {
                $('#boton_'+Van).removeClass("btn-danger").addClass("btn-primary");
                $('#btnI_'+Van).removeClass("fa-lock").addClass("fa-check");
                $('#boton_'+Van).prop('title', 'ACTIVO');
              }else
              {
                $('#boton_'+Van).removeClass("btn-primary").addClass("btn-danger");
                $('#btnI_'+Van).removeClass("fa-check").addClass("fa-lock");
                $('#boton_'+Van).prop('title', 'ALUMNO DESACTIVADO EN ESTE PERIODO');
              }
            } 
        }, 'json');
    }
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";
mysqli_free_result($periodo_query);
mysqli_free_result($grado1_query);
mysqli_free_result($secci1_query);
mysqli_free_result($query);
mysqli_free_result($query2);
mysqli_free_result($gradoVer_query);
mysqli_free_result($secciVer_query);
mysqli_free_result($agosto_query);
mysqli_free_result($montos_query);
mysqli_free_result($pagos_query);


?>
           