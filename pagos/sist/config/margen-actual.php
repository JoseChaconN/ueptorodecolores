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
$margen_sup_fis=$_POST['mgSupF'];
$margen_izq_fis=$_POST['mgIzqF'];
$margen_cop_fis=$_POST['mgCopF'];
$margen_sup_HB=$_POST['mgSupH'];
$margen_izq_HB=$_POST['mgIzqH'];
$margen_cop_HB=$_POST['mgCopH'];
mysqli_query($link,"UPDATE preinscripcion SET margen_sup_fis='$margen_sup_fis', margen_izq_fis='$margen_izq_fis',margen_cop_fis='$margen_cop_fis',margen_sup_HB='$margen_sup_HB', margen_izq_HB='$margen_izq_HB',margen_cop_HB='$margen_cop_HB' WHERE id = '1' ");
$_SESSION['margen_izq_fis']=$margen_izq_fis;
$_SESSION['margen_sup_fis']=$margen_sup_fis;
$_SESSION['margen_cop_fis']=$margen_cop_fis;
$_SESSION['margen_izq_HB']=$margen_izq_HB;
$_SESSION['margen_sup_HB']=$margen_sup_HB;
$_SESSION['margen_cop_HB']=$margen_cop_HB;
$json = ['isSuccessful' => TRUE ] ;
echo json_encode($json);
?>