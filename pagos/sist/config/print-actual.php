<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])) )
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
	include_once ('../../include/funciones.php');
}
$link = Conectarse();
$id=($_POST['id']);
$nombre=$_POST['nombre'];
$superior_FIS=$_POST['sup_fis'];
$izquierdo_FIS=$_POST['izq_fis'];
$copia_FIS=$_POST['cop_fis'];
$superior_HB=$_POST['sup_hb'];
$izquierdo_HB=$_POST['izq_hb'];
$copia_HB=$_POST['cop_hb'];
mysqli_query($link,"UPDATE impresora SET nombre='$nombre', superior_FIS='$superior_FIS',izquierdo_FIS='$izquierdo_FIS',copia_FIS='$copia_FIS',superior_HB='$superior_HB',izquierdo_HB='$izquierdo_HB',copia_HB='$copia_HB' WHERE id = '$id' ");
$json = ['isSuccessful' => TRUE ] ;
echo json_encode($json);
?>