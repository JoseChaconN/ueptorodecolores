<?php
include_once "../include/header.php";
$link = Conectarse();
if($idUserAct=='1')
{
    $alumno_query = mysqli_query($link,"SELECT A.idUser, A.cedulaUser, A.nombreUser, A.apellidoUser, A.cargoUser, A.activoUser, A.emailUser,A.claveUser, B.nomcargo FROM user A, cargo_admin B WHERE A.idUser>1 and A.cargoUser=B.idcargo ORDER BY A.apellidoUser ASC ");    
}else
{
    $alumno_query = mysqli_query($link,"SELECT A.idUser, A.cedulaUser, A.nombreUser, A.apellidoUser, A.cargoUser, A.activoUser, A.emailUser,A.claveUser, B.nomcargo FROM user A, cargo_admin B WHERE A.idUser='$idUserAct' and A.cargoUser=B.idcargo");
}?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="form-row">
        <div class="col-md-9 col-xs-12 col-sm-8">
            <h1 class="h3 mb-2 text-gray-800">Listado de Usuarios</h1>
        </div><?php
        if($idUserAct=='1')
        { ?>
            <div class="col-md-3 col-xs-12 col-sm-4">
                <a onclick='window.open("usuario-perfil.php")' class="btn btn-success" style="width: 100%;"><span class="fas fa-plus fa-sm" ></span> Nuevo Usuario</a><br><br>
            </div><?php
        } ?>
    </div>        
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <!--div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div-->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cedula</th>
                            <th>Usuario</th>
                            <th>Cargo</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Cedula</th>
                            <th>Usuario</th>
                            <th>Cargo</th>
                            <th style="width: 15%;">Botones</th>
                        </tr>
                    </tfoot>
                    <tbody><?php 
                        $son=0;
                        while($row=mysqli_fetch_array($alumno_query)) 
                        {
                            $idUser=$row['idUser'];
                            $cedulaUser=$row['cedulaUser'];
                            $usuario=$row['apellidoUser'].' '.$row['nombreUser'];
                            $nombreCargo=$row['nomcargo'];
                            $emailUser=$row['emailUser'];
                            $claveUser=$row['claveUser'];
                            $activoUser=$row['activoUser'];
                            $btn_class = ($activoUser == 1) ? 'btn-primary' : 'btn-danger';
                            $btn_i_class = ($activoUser == 1) ? 'fas fa-check' : 'fas fa-lock';
                            $titulo = ($activoUser== 1) ? 'ACTIVO' : 'DESACTIVADO'; ?>
                            <tr>
                                <td><?= $son+=1; ?></td>
                                <td><?= $cedulaUser; ?></td>
                                <td style='cursor: pointer' data-toggle="tooltip" title="<?= 'Correo: '.$emailUser.' Clave:'.$claveUser ?>" onclick='window.open("usuario-perfil.php?id=<?= encriptar($idUser) ?>")'><?= $usuario ; ?></td>
                                <td><?= $nombreCargo ?></td>
                                <td>
                                    <div class="dropdown mb-4 btn-group ">
                                        <button data-toggle="tooltip" title="Perfil de Usuario" type="button" class="btn btn-info btn-circle" onclick='window.open("usuario-perfil.php?id=<?= encriptar($idUser) ?>")' ><i class="fas fa-user " ></i></button>

                                        <button <?php if($cargoAct!=1){echo "disabled";} ?> id="boton_<?= $son; ?>" title="<?= $titulo; ?>" onclick="statusUser('<?= $idUser ?>',<?= $son ?>)" type="button" class="<?= $btn_class ?>" ><i id="btnI_<?= $son; ?>" class="<?= $btn_i_class ?> fa-lg" ></i></button>
                                    </div>
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
        $('#configura').addClass("show");
        $('#usuarioListado').addClass("active");
    });
    function  statusUser(id, Van)
    {
        $.post('usuario-status.php',{'idUser':id},function(data)
        {
            if(data.isSuccessful)
            {
              if(data.status=='1')
              {
                $('#boton_'+Van).addClass("btn-primary").removeClass("btn-danger");
                $('#btnI_'+Van).addClass("fa-check").removeClass("fa-lock");
                $('#boton_'+Van).prop('title', 'ACTIVO');
              }else
              {
                $('#boton_'+Van).removeClass("btn-success").addClass("btn-danger");
                $('#btnI_'+Van).removeClass("fa-check").addClass("fa-lock");
                $('#boton_'+Van).prop('title', 'DESACTIVADO');
              }
            } 
        }, 'json');
    }
</script>
<!-- /.container-fluid --><?php
include_once "../include/footer.php";                
?>
           