<?php
session_start();
$url_actual =  $_SERVER["SERVER_NAME"];
if(!isset($_SESSION['usuario']) || !isset($_SESSION['password']) )
{   
    if(isset($_COOKIE['usuario']) && isset($_COOKIE['password']))
    {
        include_once "../../../conexion.php";
        include_once "../../include/funciones.php";
        include_once ('../../../inicia.php');
        $link = Conectarse();
        $user=$_COOKIE['usuario'];
        $clave=desencriptar($_COOKIE['password']);
        $result = mysqli_query($link,"SELECT * FROM user WHERE cedulaUser = '$user' and claveUser = '$clave' and activoUser='1' ");  
        while ($row = mysqli_fetch_array($result))
        { 
            $_SESSION['idUser'] = $row['idUser']; 
            $_SESSION['nombreUser'] = $row['nombreUser']; 
            $_SESSION['emailUser'] = $row['emailUser']; 
            $_SESSION['cargo'] = $row['cargoUser'];
            $_SESSION['usuario'] = $row['nombreUser'];
            $_SESSION['password'] = $row['claveUser'];
            $_SESSION['impresora']=$row['impresora'];
        }
        $periodo_query=mysqli_query($link,"SELECT nombre_periodo, tablaPeriodo,id_periodo FROM  periodos where periodoActivo='1'"); 
        while($row=mysqli_fetch_array($periodo_query))
        {
            $_SESSION['tablaPeriodo']=trim($row['tablaPeriodo']);
            $_SESSION['nomPeriAct']=trim($row['nombre_periodo']);
            $_SESSION['id_periodo']=$row['id_periodo'];
        }
    }else{ header("location:../../login.php"); 
}
}else
{
    include ('../../../conexion.php');
    include ('../../include/funciones.php');    
    include ('../../../inicia.php');
}
if($_SESSION['cargo']=='' || $_SESSION['impresora']<1){header("location:../../logout.php");}
setlocale(LC_TIME, "spanish");
date_default_timezone_set("America/Caracas");

$fechaHoy = strftime( "%Y-%m-%d");
$fechaEsp = strftime( "%d-%m-%Y");
$idUserAct=$_SESSION['idUser'];
$cargoAct=$_SESSION['cargo'];
$nomPeriAct=$_SESSION['nomPeriAct'];
$tablaPeriodo=$_SESSION['tablaPeriodo'];
$usuarioAct=$_SESSION['nombreUser']; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="sistema control de estudiantes">
    <meta name="author" content="JE Sistemas">
    <link rel="shortcut icon" href="../../img/logo.png?3">
    <title>Pagos <?= $_SESSION['nombreUser'] ?></title>
    <!-- Custom fonts for this template -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="../../sweealert/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="../../sweealert/animate.min.css">
    <!--link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet"-->
    <!-- Custom styles for this template -->
    <link href="../../css/sb-admin-2.min.css?2" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css">
    <!-- Custom styles for this page -->
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../sweealert/sweetalert2@10"></script>

    <script type="text/javascript" src="../../js/bootstrap-filestyle.min.js"> </script><?php
    if($_SESSION['activoChat']==1 && $idUserAct>1)
    { ?>
        <script type="text/javascript">
            
            var _smartsupp = _smartsupp || {};
            _smartsupp.key = '204a52fe22cf0bc6a2946217a1dfa17ad91c8629';
            window.smartsupp||(function(d) {
              var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
              s=d.getElementsByTagName('script')[0];c=d.createElement('script');
              c.type='text/javascript';c.charset='utf-8';c.async=true;
              c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
            })(document);
            
        </script><?php 
    }?>
</head>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
</script>
<style type="text/css">
    .img-circle {
    width: 150px;
    height: 150px;
    border: 1px ;
    margin: 10px 5px 0 0;
    border-radius: 30%;
    }
</style>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" target="_blank" href="https://jesistemas.com">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">JESISTEMAS.COM</div>
            </a>
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="tooltip" data-placement="right" title="Abrir pagina web como Estudiante" target="_blank" href="https://<?= DOMINIO ?>/logout.php">
                    <i class="fas fa-fw fa-home"></i>
                    <span><?= EKKS ?></span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- Nav Item - Dashboard -->
            <div class="sidebar-heading">
                Busqueda Rapida
            </div>
            <li class="nav-item" id="buscaEstudiante">
                <a class="nav-link " data-toggle="tooltip" data-placement="right" title="Puede buscar un alumno por su numero de cedula, nombre o apellido" href="../alumnos/buscar-alumno.php">
                    <i class="fas fa-fw fa-search"></i>
                    <span>Buscar Estudiante</span></a>
            </li>
            <li class="nav-item" id="buscarRecibo">
                <a class="nav-link " data-toggle="tooltip" data-placement="right" title="Ubicar un recibo por su numero" href="../factura/factura-buscar.php">
                    <i class="fas fa-fw fa-search"></i>
                    <span>Buscar Recibo</span></a>
            </li>
            <li class="nav-item" id="corregirCedula">
                <a class="nav-link" data-toggle="tooltip" data-placement="right" title="Permite corregir el numero de cedula de un estudiante" href="../alumnos/corrige-cedula.php">
                    <i class="fas fa-user"></i>
                    <span>Corregir Cedula</span></a>
            </li>
            <li class="nav-item" id="corregirEspecial">
                <a class="nav-link" data-toggle="tooltip" data-placement="right" title="Permite cambiar a un estudiante de Primaria a Bachillerato y viceversa" href="../alumnos/cambiar-especial.php"><i class="fas fa-user"></i><span>Cambiar Especialidad</span></a>
                
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Estudiantes
            </div>
            <!-- Menu Listados -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#listados"
                    aria-expanded="true" aria-controls="listados">
                    <i class="fas fa-fw fa-list-ul"></i>
                    <span>Listados</span>
                </a>
                <div id="listados" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <!--h6 class="collapse-header">Opciones:</h6-->
                        <a id="listadoPrim" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Listado de Alumnos por Grado y Seccion donde puede ver: Ficha, Historia de Pagos, Facturar, Inactivar, Enviar Correo" href="../alumnos/listado-pri.php">Alumnos Primaria</a>
                        <a id="listadoAlum" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Listado de Alumnos por Grado y Seccion donde puede ver: Ficha, Historia de Pagos, Facturar, Inactivar, Enviar Correo" href="../alumnos/listado.php">Alumnos Bachill.</a>
                        <a id="listadoDeuPagMor" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Listado de Alumnos con Deuda total, Pagado y Morosidad" href="../reportes/pagos-list.php">Pagado/Morosidad</a>
                        <a id="listadoCedCorrige" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Listado de las cedula modificadas en el sistema" href="../alumnos/corrige-list.php">Cedulas Corregidas</a><?php  
                        if($cargoAct=='1')
                        {?>
                            <a id="listadoConvenios" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Listado de Alumnos en Convenio" href="../reportes/convenios-list.php">Convenios</a>
                            <a id="pagosConcepto" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Listado de Alumnos Pagos por conceptos" href="../alumnos/pagosConcepto.php">Pagos x concepto</a>
                            <a id="descuentoList" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Listado de Alumnos con descuentos" href="../reportes/descuento-list.php">Becados</a>
                            <a id="sumaPagosList" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Listado de Alumnos con montos registrados pero pagados en otro sistema" href="../reportes/suma-pagos-list.php">Pagos Añadidos</a>
                            <a id="represeList" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Listado de Representantes" href="../reportes/represe-list.php">Representantes</a><?php 
                        }?>
                        
                    </div>
                </div>
            </li><?php  
            if($cargoAct=='1')
            {?>
                <hr class="sidebar-divider">
                <!-- Menu Movimientos -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#movimiento"
                        aria-expanded="true" aria-controls="collapseUtilities">
                        <i class="fas fa-fw fa-credit-card "></i>
                        <span>Movimientos</span>
                    </a>
                    <div id="movimiento" class="collapse" aria-labelledby="headingUtilities"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a id="listadoIngresos" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Resumen de ingresos diarios" href="../reportes/ingresos-list.php">Ingresos Diarios</a>
                            <a id="listadoIngresoBanco" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Ingresos y Egresos del banco segun fecha" href="../reportes/bancos-list.php">Ingresos/Egresos Banco</a>
                            <a id="listadoEgresos" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Resumen de egresos diarios" href="../reportes/egresos-list.php">Egresos Diarios</a>
                            <a id="desgloceEgresos" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Desgloce para el pago del personal" href="../reportes/desgloce-list.php">Como pagar Personal</a>
                            <a id="inventarioList" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Listado de articulos" href="../reportes/inventario-list.php">Inventario</a>
                            
                        </div>
                    </div>
                </li><?php 
            }?>
            <hr class="sidebar-divider">
            <!-- Menu Procesos -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#procesos"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-play"></i>
                    <span>Procesos</span>
                </a>
                <div id="procesos" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <!--h6 class="collapse-header">Opciones:</h6-->
                        <a id="correoMorosos" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Envio de correo a representantes con deuda pendiente" href="../procesos/correo-morosos.php">Correo a Morosos</a>
                        <a id="provedores" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Listado de personal, docentes y proveedores" href="../procesos/provedor-list.php">Proveedores</a>
                        <a id="confirmaPagos" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Listado de notificaciones de pagos por representante" href="../reportes/confirmar.php">Confirmar Pagos</a>
                        
                    </div>
                </div>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- Menu Configuracion -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#configura"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Configuración</span>
                </a>
                <div id="configura" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <!--h6 class="collapse-header">Opciones:</h6--><?php 
                        if( $_SESSION['idUser']==1){?> 
                            <a id="usuarioListado" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Listado de usuarios" href="../user/usuario-list.php">Usuarios</a><?php
                        }
                        if($cargoAct=='1')
                        { ?>
                            <a id="montoMesListado" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Montos a cobrar por Mes" href="../config/meses-list.php">Monto Mes</a>
                            <a id="conceptoListado" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Conceptos de Recibos" href="../config/concepto-list.php">Conceptos Factura</a>
                            <a id="conceptoEgreso" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Conceptos de Egresos" href="../config/concepto-egreso-list.php">Conceptos Egresos</a>
                            <a id="gruposEgreso" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Cuentas o grupo al cual pertenecen los egresos" href="../config/cuenta-egreso-list.php">Grupos Egresos</a>
                            <!--a id="conceptoMisce" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Conceptos de Miscelaneos" href="../config/miscela-list.php">Conceptos Miscelaneos</a--><?php 
                        }
                        if($idUserAct=='1')
                        {?>
                            <a id="datoColegio" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Datos del Plantel" href="../config/plantel-perfil.php">Ficha del Plantel</a>
                            <a id="subeDatos" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Enviar datos de pagos" href="../config/pagos-cargar.php">Subir data del local</a>
                            <?php 
                        }?>
                        <a id="margenFactura" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Impresoras para la factura" href="../config/print-list.php">Impresoras</a>
                    </div>
                </div>

            </li>
            <hr class="sidebar-divider">
            <!-- Menu Caja Chica -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#cajaChicaMenu"
                        aria-expanded="true" aria-controls="collapseUtilities">
                        <i class="fas fa-fw fa-box"></i>
                        <span>Caja Chica</span>
                    </a>
                    <div id="cajaChicaMenu" class="collapse" aria-labelledby="headingUtilities"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <!--h6 class="collapse-header">Opciones:</h6--><?php
                                if($cargoAct=='1')
                                { ?>
                                    <a id="cajaChicaConfig" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Configuracion de Nueva Caja Chica" href="../cajaChica/list-nombres.php">Cajas Chica</a>
                                    <a id="cajaChicaTip" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Tipos de Movimientos de caja chica" href="../cajaChica/list-tipos.php">Tipos Movimientos</a><?php
                                } ?>
                                <a id="cajaChicaMov" class="collapse-item" data-toggle="tooltip" data-placement="right" title="Maneja el flujo de caja chica" href="../cajaChica/list-movimien.php">Movimientos</a>
                        </div>
                    </div>
                </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block"><?php
            if($cargoAct=='1')
            {?>
                <li class="nav-item" id="accesos-list">
                    <a class="nav-link " data-toggle="tooltip" data-placement="right" title="Ingresos al sistema de facturacion" href="../user/accesos-list.php">
                        <i class="fas fa-fw fa-search"></i>
                        <span>Accesos al Sistema</span></a>
                </li>
                <li class="nav-item" id="bitaco-list">
                    <a class="nav-link " data-toggle="tooltip" data-placement="right" title="Modificacion en monto pagado" href="../reportes/bitacora-list.php">
                        <i class="fas fa-fw fa-search"></i>
                        <span>Bitacora</span></a>
                </li><?php 
            }?>
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>
                    <div class="col-md-10 text-center" id="facilComp"><h2>Bienvenidos a Sistema FacilFact</h2></div>
                    <div class="col-md-10 text-center" id="facilCort" style="display:none;"><h2>JeSistemas.com</h2></div>
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['nombreUser'] ?></span>
                                <img class="img-profile rounded-circle"
                                    src="../../img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" onclick='window.open("../user/usuario-perfil.php?id=<?= encriptar($idUserAct) ?>")' >
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Perfil
                                </a>
                                
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Salir
                                </a>
                            </div>
                        </li>

                    </ul>
                </nav>
                <!-- End of Topbar -->