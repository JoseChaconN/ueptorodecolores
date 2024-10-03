<?php 
function Conectarse()  
{ 
   $host="mysql3001.mochahost.com";
   $user="jesistem_admin";
   $pw="2wC-D1%K%m_%";
   $bd="jesistem_colegio";

   if (!($link = mysqli_connect($host,$user,$pw,$bd)))
   {  
      echo "Error conectando a la base de datos.";  
      exit();  
   }
   
   if (!mysqli_select_db($link,$bd)) 
   {  
      echo "Error seleccionando la base de datos.";  
      exit();  
   } 
   return $link;  
}

  
?>
