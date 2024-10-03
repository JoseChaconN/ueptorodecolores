<?php
include_once "../include/header.php";
$link = Conectarse();
if(isset($_GET['desde']))
{
    $desde=$_GET['desde'];
    $hasta = strftime( "%Y-%m-%d");
}else
{
    if(!isset($_POST['desde']) && !isset($_POST['hasta']))
    {
        $recibos_query = mysqli_query($link,"SELECT fecha FROM pagos WHERE recibo is NULL and statusPago='2' ");
        if(mysqli_num_rows($recibos_query) > 0)
        {
            $row2=mysqli_fetch_array($recibos_query);
            $desde=$row2['fecha'];
        }else{ $desde = strftime( "%Y-%m-%d"); }
        $hasta = strftime( "%Y-%m-%d");
    } else
    {
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
    }
}
$recibos_query = mysqli_query($link,"SELECT A.*,B.nombre,B.apellido FROM pagos A, alumcer B WHERE A.fecha>='$desde' AND A.fecha<='$hasta' and A.recibo is NULL and A.ced_alu=B.cedula order by A.fecha ");
if(isset($_GET['pend']))
{ ?>
    <script type="text/javascript">
        Swal.fire({
            title: "Información!",
            text: "Tiene pagos pendientes por verificar!",
            icon: "info",
            button: "Entiendo",
          });
    </script><?php
} ?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-12">
            <h1 class="h3 mb-2 text-gray-800">Transferencias por confirmar</h1>    
        </div>    
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <!--div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div-->
        <div class="card-body">
            <div class="table-responsive">
                <form role="form" method="POST" enctype="multipart/form-data" action="confirmar.php">
                    <div class="form-row">
                        <div class="col-md-3 col-xs-6 col-sm-6">
                            <label>Desde el:</label>
                            <input type="date" id="desde" name="desde" class="form-control" value="<?= $desde ?>">
                        </div>
                        <div class="col-md-3 col-xs-6 col-sm-6">
                            <label>Hasta el:</label>
                            <input type="date" id="hasta" name="hasta" class="form-control" value="<?= $hasta ?>">
                        </div>
                        <div class="col-md-3 col-xs-12 col-sm-12">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-info" style="width: 100%;"><span class="fas fa-search fa-sm" ></span> Buscar</button><br><br>
                        </div>
                        <div class="col-md-3 col-xs-12 col-sm-12">
                            <label>&nbsp;</label>
                            <button type="button" onclick="btnPdf()" class="btn btn-primary" style="width: 100%;"><span class="fas fa-print fa-sm" ></span> Imprimir</button><br><br>
                        </div>
                    </div>
                </form>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cedula</th>
                            <th>Reportado</th>
                            <th>Realizado</th>
                            <th>Tipo</th>
                            <th>Nro.</th>
                            <th>Monto</th>
                            <th>Boton</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Cedula</th>
                            <th>Reportado</th>
                            <th>Realizado</th>
                            <th>Tipo</th>
                            <th>Nro.</th>
                            <th>Monto</th>
                            <th>Boton</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row=mysqli_fetch_array($recibos_query)) 
                        {
                            $idPago=$row['id'];
                            $cedula=$row['ced_alu'];
                            $fecha = date("d-m-Y", strtotime($row['fecha']));
                            $fechadepo = date("d-m-Y", strtotime($row['fechadepo']));
                            $opera='Otra';
                            if ($row['operacion']=='T'){$opera='Transferencia';}
                            if ($row['operacion']=='D'){$opera='Deposito';}
                            if ($row['operacion']=='Pa'){$opera='Pago Movil';}
                            $nrope=$row['nrodeposito'];
                            $monto=$row['monto'];
                            $bancorec=$row['banco'];
                            $bancoemi=$row['bancoemisor'];
                            $concepto=$row['concepto'];
                            $comentario=$row['comentario'];
                            $alumno=($row['apellido'].' '.$row['nombre']);
                            $statusPago=$row['statusPago'];

                            $btn_class = ($statusPago == 1) ? 'btn btn-primary btn-circle' : 'btn btn-danger btn-circle';
                            $btn_i_class = ($statusPago == 1) ? 'fas fa-check' : 'fas fa-exclamation-triangle';
                            $titulo = ($statusPago== 1) ? 'OPERACION REVISADA' : 'PENDIENTE POR VERIFICAR';?>
                            <tr>
                                <td><?= $son+=1; ?></td>
                                <td><span data-toggle="tooltip" data-placement="right" title="<?= 'Alumno: '.$alumno.' del Banco: '.$bancoemi.' hacia el banco: '.$bancorec.' por concepto: '.$concepto.' nota: '.$comentario ?>"><?= $cedula ?></span></td>
                                <td><?= $fecha ?></td>
                                <td><?= $fechadepo ?></td>
                                <td><span data-toggle="tooltip" data-placement="right" title="<?= 'Alumno: '.$alumno.' del Banco: '.$bancoemi.' hacia el banco: '.$bancorec.' por concepto: '.$concepto.' nota: '.$comentario ?>"><?= $opera ?></span></td>
                                <td><?= $nrope ?></td>
                                <td align="right"><?= number_format($monto,2,',','.') ?></td>
                                
                                <td>
                                    <button  id="boton_<?= $son; ?>" title="<?= $titulo; ?>" onclick="statusPago('<?= $idPago ?>','<?= $son ?>')" type="button" class="<?= $btn_class ?>" ><i id="btnI_<?= $son; ?>" class="<?= $btn_i_class ?> fa-lg" ></i></button>
                                </td>        
                            </tr><?php
                        } ?>
                    </tbody>
                </table>
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
            $('#procesos').addClass("show");
        }
        $('#confirmaPagos').addClass("active");
    });
    function  statusPago(id, Van)
    {
        $.post('confirmar-status.php',{'idSta':id},function(data)
        {
            if(data.isSuccessful)
            {
              if(data.status=='1')
              {
                $('#boton_'+Van).addClass("btn-primary").removeClass("btn-danger");
                $('#btnI_'+Van).addClass("fa-check").removeClass("fa-exclamation-triangle");
                $('#boton_'+Van).prop('title', 'OPERACION REVISADA');
              }else
              {
                $('#boton_'+Van).removeClass("btn-success").addClass("btn-danger");
                $('#btnI_'+Van).removeClass("fa-check").addClass("fa-exclamation-triangle");
                $('#boton_'+Van).prop('title', 'PENDIENTE POR VERIFICAR');
              }
            } 
        }, 'json');
    }
    
    function btnPdf()
    {
        des=$('#desde').val()
        has=$('#hasta').val()
        window.open('confirmar-pdf.php?desde='+des+'&hasta='+has+'&filtro='+$('#example_filter').find('input').val())
    }
    /*function excelBtn() 
    {
        window.open('ingresos-excel.php?desde=<?= $desde ?>&hasta=<?= $hasta ?>')
    }*/
    
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php"; 
mysqli_free_result($recibos_query);               
?>
           