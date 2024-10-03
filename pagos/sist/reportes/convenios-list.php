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
    $secci1_query = mysqli_query($link,"SELECT * FROM secciones ORDER BY id LIMIT 1 ");
    while($row1 = mysqli_fetch_array($secci1_query))
    {
        $secciVer=$row1['id'];
    }
    $gradoVer='1';
    $nomEspe='Inicial y Primaria';
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
    $nomEspe = ($gradoVer=='1') ? 'Inicial y Primaria' : 'Bachillerato' ;
}
//$gradoVer=2;
if($gradoVer==1)
{
    $query = mysqli_query($link,"SELECT A.idAlum, A.cedula as 'cedalu',A.nombre as 'nomalu', A.morosida, B.nombreGrado as 'nomgra', A.apellido, A.Periodo, E.*, C.nombre as 'nomsec', D.representante, D.tlf_celu FROM alumcer A, grado".$tablaPeriodo." B, secciones C, represe D, notaprimaria".$tablaPeriodo." E WHERE E.convenio!='' and E.grado<61 and E.idSeccion='$secciVer' and E.idAlumno=A.idAlum and E.grado=B.grado and E.idSeccion=C.id and A.ced_rep=D.cedula ORDER BY E.grado,E.idSeccion, A.apellido ASC ");   
}
if($gradoVer==2)
{
    $query = mysqli_query($link,"SELECT A.idAlum, A.cedula as 'cedalu',A.nombre as 'nomalu', A.morosida, B.nombreGrado as 'nomgra', A.apellido, A.Periodo, E.*, C.nombre as 'nomsec', D.representante, D.tlf_celu FROM alumcer A, grado".$tablaPeriodo." B, secciones C, represe D, matri".$tablaPeriodo." E WHERE E.convenio!='' and E.grado>60 and E.idSeccion='$secciVer' and E.idAlumno=A.idAlum and E.grado=B.grado and E.idSeccion=C.id and A.ced_rep=D.cedula ORDER BY E.grado,E.idSeccion, A.apellido ASC ");   
} ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9">
            <h1 class="h3 mb-2 text-gray-800">Alumnos en Convenio de Pago (<?= $nomEspe ?>)</h1>    
        </div>    
        <div class="col-md-3 col-xs-12 col-sm-12">
            <!--button type="button"  data-toggle="modal" data-target="#imprime" class="btn btn-primary" style="width: 100%;"><span class="fas fa-print fa-sm" ></span> Imprimir</button><br><br-->
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <!--div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div-->
        <div class="card-body">
            <div class="table-responsive">
                <form role="form" method="POST" enctype="multipart/form-data" action="convenios-list.php">
                    <div class="form-row">
                        <div class="col-md-3 col-md-offset-md-3 col-12" style="margin-bottom: 2%; ">
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
                        <div class="col-md-3 col-md-offset-md-3 col-12" style="margin-bottom: 2%; ">
                            <select name="gradoVer" onchange="pulsaBuscar()" class="form-control" id="gradoVer">
                                <option value="1" <?php if($gradoVer==1){echo "selected";} ?>>Toda Inicial y Primaria</option>
                                <option value="2" <?php if($gradoVer==2){echo "selected";} ?>>Toda Media General</option><?php
                                /*$gradoVer_query = mysqli_query($link,"SELECT grado, nombreGrado FROM grado".$tablaPeriodo." ORDER BY grado ");
                                while($row = mysqli_fetch_array($gradoVer_query))
                                {
                                    $nom_gradsd=utf8_encode($row['nombreGrado']);
                                    $id_gradsd=$row['grado'];
                                    $selected = ($id_gradsd==$gradoVer) ? 'selected' : '' ;
                                    echo '<option '.$selected.' value="'.$id_gradsd.'">'.$nom_gradsd."</option>";
                                }*/?>                                
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
                            <th title="Fecha plazo de solvencia sobre la morosidad">Exonerado</th>
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
                            <th title="Fecha plazo de solvencia sobre la morosidad">Exonerado</th>
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
                            $nomRep=$row['representante'];
                            $celular=$row['tlf_celu'];
                            $pago=$row['pagado'];
                            $suma_a_pagado=$row['suma_a_pagado'];
                            $grado=$row['grado'];
                            $exoneraMorosidad=$row['exoneraMorosidad'];
                            $convenio = (empty($row['convenio'])) ? '' : ' / Convenio: '.$row['convenio'] ;
                            //$=$row[''];
                            $btn_class = ($statusAlum == 1) ? 'btn btn-primary btn-circle' : 'btn btn-danger btn-circle';
                            $btn_i_class = ($statusAlum == 1) ? 'fas fa-check' : 'fas fa-lock';
                            $titulo = ($statusAlum== 1) ? 'ACTIVO' : 'ALUMNO DESACTIVADO EN ESTE PERIODO';
                            for ($i=1; $i <14 ; $i++) { 
                                ${'desc'.$i} = $row['desc'.$i];
                                //$totDesc=$totDesc+$row['desc'.$i];
                            }
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
                                $deudatotal=$deudatotal+($row['monto']-${'desc'.$meses});
                                ${'insc'.$meses} = $row['insc'];
                                ${'mes'.$meses} = $row['mes'];
                                ${'f_vence'.$meses} = $row['fecha_vence'];
                                ${'monto'.$meses} = $row['monto']-${'desc'.$meses};
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
                            $morosida=$morosida-$pagado; ?>
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
                                <td><input style="text-align:right;" readonly type="text" class="form-control" id="deuda<?= $son ?>" value="<?= number_format($deudatotal,2,',','.').' $' ?>"></td>
                                <td><input style="text-align:right;" readonly type="text" class="form-control" id="pagado<?= $son ?>" value="<?= number_format($pagado,2,',','.').' $' ?>"></td>
                                <td><input style="text-align:right;" readonly type="text" class="form-control" id="morosida<?= $son ?>" value="<?= number_format($morosida,2,',','.').' $' ?>"></td>
                                <td><input style="text-align:right;" onblur="actualFecha('<?= encriptar($idAlum) ?>','<?= $son ?>','<?= $grado ?>')" onClick="this.select()" type="date" title="Fecha limite para poder ver notas en caso de Morosidad" class="form-control" id="exoneraMorosidad<?= $son ?>" value="<?= $exoneraMorosidad ?>"></td>
                                
                                <td style="width:17%;">
                                    <div class="btn-group">
                                        <button onclick='window.open("../factura/facturar.php?id=<?= encriptar($idAlum).'&peri='.$tablaPeriodo.'&gra='.$grado ?>")' type="button" title='Facturar ' class="btn btn-success btn-circle" ><i class="fas fa-dollar-sign fa-lg" ></i></button>

                                        <button onclick='window.open("../procesos/historia-pagos.php?id=<?= encriptar($idAlum) ?>&peri=<?= $tablaPeriodo ?> ")' data-toggle="tooltip" data-placement="top" title="Historial de Pagos" type="button" class="btn btn-secondary btn-circle" ><i class="fas fa-folder fa-lg"></i></button><?php
                                        if( !empty($celular))
                                        { ?>
                                            <button onclick='window.open("https://api.whatsapp.com/send?phone=<?= '+58'.$celular ?>&text=Estimado(a)%20<?= $nomRep ?>%20junto%20con%20saludarle%20desde%20la%20U.E.P.%20<?= EKKS ?>%20me dirijo a usted muy respetuosamente para informarle su situación administrativa para con nuestra institución, recordándole que nuestros únicos ingresos para el mantenimiento de la institución dependen únicamente del pago oportuno de sus mensualidades, el monto vencido a la fecha es de $.<?= number_format($morosida,2,",",".") ?>. Agradeciendo de antemano solventar esta situación en un lapso de 24 horas. Atentamente la Administración.")' style="background-color: #43A047; color: #fff;" class="btn btn-success btn-circle" type="button" data-toggle="tooltip" data-placement="top" title="Mensaje al Whatsapp <?= $convenio ?>"><i class="fab fa-whatsapp fa-lg" ></i></button><?php
                                        } ?>
                                        <button  id="boton_<?= $son; ?>" title="<?= $titulo; ?>" onclick="statusAlum('<?= $idAlum ?>',<?= $son ?>,'<?= $grado ?>')" type="button" class="<?= $btn_class ?>" ><i id="btnI_<?= $son; ?>" class="<?= $btn_i_class ?> " ></i></button>
                                    </div>
                                </td>         
                            </tr><?php
                        } ?>
                    </tbody>
                </table>
                <input type="hidden" id="tabla" value="<?= $tablaPeriodo ?>">
                <input type="hidden" id="nombre_periodo" value="<?= $nombre_periodo ?>" >
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="sumarPago" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Estudiante:&nbsp;&nbsp;<h4 id="aquien"></h4></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Pagado Procesado</label>
                            <input type="text" id="pagado" readonly class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Sumar al pagado</label>
                            <input type="text" id="sumar" onClick="this.select()" class="form-control">
                        </div>
                        <input type="hidden" id="idAlum">
                        <input type="hidden" id="linea">
                        <input type="hidden" id="total">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="actualiza()" class="btn btn-primary">Actualizar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="imprime" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Imprimir Reporte:&nbsp;&nbsp;<h4 id="aquien"></h4></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Morosos</label>
                            <input type="radio" checked name="salida" value="1" id="morosos" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Todos</label>
                            <input type="radio" name="salida" value="2" id="todos" class="form-control">
                        </div>
                        <hr>
                        <div class="col-md-12" style="margin-top: 4%; ">
                            <h4>Indique datos en el reporte</h4>
                        </div>
                        <div class="col-md-3">
                            <label>Cedula</label><br>
                            <input type="checkbox" id="cedImp" checked value="1" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Estudiante</label><br>
                            <input type="checkbox" id="aluImp" checked class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Representante</label><br>
                            <input type="checkbox" id="repImp" checked class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Total Año</label><br>
                            <input type="checkbox" id="totImp" checked class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Pagado</label><br>
                            <input type="checkbox" id="pagImp" checked class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Morosidad</label><br>
                            <input type="checkbox" id="morImp" checked class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Telefono</label><br>
                            <input type="checkbox" id="tlfImp" checked class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="imprimePdf()" class="btn btn-primary">Enviar</button>
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
        $('#listadoConvenios').addClass("active");
    });
    function imprimePdf()
    {
        gra=$('#gradoVer').val()
        sec=$('#secciVer').val()
        per=$('#nombre_periodo').val()
        mor=$('#morosos').val()
        tod=$('#todos').val()
        tab=$('#tabla').val()
        if($("#morosos").prop('checked')) {sale=1;}else{sale=2;}
        if($("#cedImp").prop('checked')) {ced=1;}else{ced=2;}
        if($("#aluImp").prop('checked')) {alu=1;}else{alu=2;}
        if($("#repImp").prop('checked')) {rep=1;}else{rep=2;}
        if($("#totImp").prop('checked')) {tot=1;}else{tot=2;}
        if($("#pagImp").prop('checked')) {pag=1;}else{pag=2;}
        if($("#morImp").prop('checked')) {mor=1;}else{mor=2;}
        if($("#tlfImp").prop('checked')) {tlf=1;}else{tlf=2;}
        datos=ced+' '+alu+' '+rep+' '+tot+' '+pag+' '+mor+' '+tlf;
        window.open('morosos-pdf.php?idG='+gra+'&idS='+sec+'&peri='+tab+'&nomP='+per+'&sale='+sale+'&datos='+datos)
    }
    function sumarPago(id,nom,pag,sum,lin,tot) {
        document.querySelector('#aquien').innerText = nom;
        $('#idAlum').val(id)
        $('#pagado').val(pag+' $')
        $('#sumar').val(sum)
        $('#linea').val(lin)
        $('#total').val(tot)
    }
    function actualiza() {
        id=$('#idAlum').val()
        sum=$('#sumar').val()
        tab=$('#tabla').val()
        gra=$('#gradoVer').val()
        lin=$('#linea').val()
        tot=$('#total').val()
        pag=$('#pagado').val()
        $.post('sumarPago.php',{'idAlum':id, 'suma':sum,'tabla':tab,'grado':gra,'pagado':pag,'total':tot},function(data)
        {
            if(data.isSuccessful)
            {
                $('#sumarPago').modal('hide')
                $('#morosida'+lin).val(data.moro)
                $('#pagado'+lin).val(data.pago)
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
    function actualFecha(id,lin,gra) {
        fec=$('#exoneraMorosidad'+lin).val()
        tabPer=$('#tabla').val();
        $.post('exonera-actual.php',{'idAlum':id, 'fecha':fec,'tabla':tabPer,'grado':gra},function(data)
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
                title: 'Almacenando Fecha Espere...'
              })
            } 
        }, 'json');
    }
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
mysqli_free_result($gradoVer_query);
mysqli_free_result($secciVer_query);
mysqli_free_result($agosto_query);
mysqli_free_result($montos_query);
mysqli_free_result($pagos_query);
?>
           