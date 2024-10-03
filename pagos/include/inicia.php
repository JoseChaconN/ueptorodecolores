<?php
$link = Conectarse();
$plantel_query = mysqli_query($link,"SELECT * FROM colegio WHERE id='1'"); 
while ($row = mysqli_fetch_array($plantel_query))
{
    $id = $row['id'];  
    $nkxs = desencriptar($row['nkxs']);
    $ekks = desencriptar($row['ekks']);
    $ckls = desencriptar($row['ckls']);
    $dominio = desencriptar($row['dominio']);
    $sucorreo = desencriptar($row['sucorreo']);  
    $dominio = desencriptar($row['dominio']);
    $direccm = desencriptar($row['direccm']);
    $telefono = desencriptar($row['telefono']);
    $clavemail = desencriptar($row['clavemail']);
    $correom=desencriptar($row['correom']);
    $rifcolm = desencriptar($row['rifcolm']);
    $ciudadm=desencriptar($row['ciudadm']);
    $estadom = desencriptar($row['estadom']);
    $tasa = $row['tasa'];
    $logoPlantel = $row['logoPlantel'];
}
define("NKXS", $nkxs);
define("EKKS", $ekks);
define("CKLS", $ckls);
define("DOMINIO", 'https://'.$dominio);
define("SUCORREO", $sucorreo);
define("DIRECCM", utf8_decode($direccm));
define("TELEFONO", $telefono);
define("CLAVEMAIL", $clavemail);
define("CORREOM", $correom);
define("RIFCOLM", $rifcolm);
define("CIUDADM", $ciudadm);
define("ESTADOM", $estadom);
define("TASA", $tasa);
define("LOGO", $logoPlantel);
//define("", $);
?>