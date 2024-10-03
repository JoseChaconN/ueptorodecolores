<?php
if(!isset($_SESSION['usuario']) || !isset($_SESSION['password']) )
{
	if(isset($_COOKIE['usuario']) && isset($_COOKIE['password']))
    {
    	include_once "../../../conexion.php";
    	include_once "../../include/funciones.php";
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
		$margen_query = mysqli_query($link,"SELECT margen_izq,margen_sup FROM preinscripcion WHERE id = '1' ");
        $row2=mysqli_fetch_array($margen_query);
        $_SESSION['margen_izq']=$row2['margen_izq'];
        $_SESSION['margen_sup']=$row2['margen_sup'];
    }else
    {
        session_destroy();
        header("location:../../login.php");
    }
}

?>