<?php 
include_once 'conexion.php';
$link = Conectarse();

$id = $_GET['id'];
$eventos_calendario_query = mysqli_query($link,"SELECT * FROM eventos_calendario WHERE id = '$id' ");
foreach ($eventos_calendario_query as $key => $value) {
    $data_calendario=['id' => $value['id'], 'titulo' => $value['titulo'], 'fecha_ini' => $value['fecha_ini'], 'fecha_fin' => $value['fecha_fin'], 'texto' => $value['texto'], 'grado_des' => $value['grado_des'], 'grado_has' => $value['grado_has'], 'sec_des' => $value['sec_des'], 'sec_has' => $value['sec_has']];
}

echo json_encode($data_calendario);