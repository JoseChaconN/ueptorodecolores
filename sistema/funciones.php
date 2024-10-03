<?php

function encriptar($cadena)
{
    $secret_key='1%&#2?3%7-\?76=&(';
    $method='AES-256-CBC';
    $secret_iv='10340266+25662937+9918713_S#a&m%y*';

    $output=FALSE;
    $key=hash('sha256', $secret_key);
    $iv=substr(hash('sha256', $secret_iv), 0,16);
    $output=openssl_encrypt($cadena, $method, $key,0,$iv);
    $output=base64_encode($output);
    return $output;
}
function desencriptar($cadena)
{
    $secret_key='1%&#2?3%7-\?76=&(';
    $method='AES-256-CBC';
    $secret_iv='10340266+25662937+9918713_S#a&m%y*';
    $key=hash('sha256', $secret_key);
    $iv=substr(hash('sha256', $secret_iv), 0,16);
    $output=openssl_decrypt(base64_decode($cadena), $method, $key,0,$iv);
    return $output;
}

function sanear_string($string)
{
 
    $string = trim($string);
 
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
 
    /*//Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\", "¨", "º", "-", "~",
             "#", "@", "|", "!", """,
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "<code>", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":",
             ".", " "),
        '',
        $string
    );*/
 
 
    return $string;
}


?>