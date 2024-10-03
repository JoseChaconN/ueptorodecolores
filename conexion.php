<?php 
$host="localhost";
$user="jesistem";
$pw="gum1Wn8Gf6k1XXth";
$bd="toro_colores";

function Conectarse()  
{ 
   $host="localhost";
   $user="jesistem";
   $pw="gum1Wn8Gf6k1XXth";
   $bd="toro_colores";
   
   if (!($link = mysqli_connect($host,$user,$pw,$bd)))
   //if (!($link=mysql_connect($host,$user,$pw)))  
   {  
      echo "Error conectando a la base de datos.";  
      exit();  
   }
   
   if (!mysqli_select_db($link,$bd)) 
   //if (!mysql_select_db($bd,$link))  
   {  
      echo "Error seleccionando la base de datos.";  
      exit();  
   } 
   return $link;  
}
function Conectarse3()  
{ 
   $host3="localhost";
   $user3="jesistem";
   $pw3="gum1Wn8Gf6k1XXth";
   $bd3='jesistemas';
   if (!($link3 = mysqli_connect($host3,$user3,$pw3,$bd3)))
   {  
      echo "Error conectando a la base de datos.";  
      exit();  
   }
   
   if (!mysqli_select_db($link3,$bd3)) 
   {  
      echo "Error seleccionando la base de datos.";  
      exit();  
   } 
   return $link3;  
}?>
