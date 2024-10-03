<?php
session_start();
if(!isset($_SESSION['usuario']) || !isset($_SESSION['password']))
{ ?>
  <script type='text/javascript'>                                
        window.location='../index.php';
  </script><?php
} 
include_once ('../inicia.php');
include_once ('../conexion.php');
include_once ('../includes/funciones.php');
$tablaPeriodo=$_SESSION['tablaPeriodo'];
$link = Conectarse();
$lapso=$_GET['lapso'];
$id_gra=desencriptar($_GET['grado']);
$id_sec=desencriptar($_GET['secc']);
$id_mat=desencriptar($_GET['mate']);
$nomb_Pro=$_GET['prof'];
$nomb_Mat=$_GET['nmate'];
$nomb_Gra=($_GET['ngra']);
$nomb_Sec=$_GET['nsec'];
$matCursa=intval(substr($id_mat, 2,2));
if($lapso=='1'){ $nomlapso='Primero';}
if($lapso=='2'){ $nomlapso='Segundo';}
if($lapso=='3'){ $nomlapso='Tercero';}

$periAct_query=mysqli_query($link,"SELECT B.id_periodo, B.nombre_periodo, B.tablaPeriodo FROM periodoactivo A, periodos B where B.tablaPeriodo='$tablaPeriodo'"); 
while($row=mysqli_fetch_array($periAct_query))
{
    $nombrePeriodo=$row['nombre_periodo'];
    $tablaPeriodo=trim($row['tablaPeriodo']);
}
$porce_query=mysqli_query($link,"SELECT * FROM cortes1".$tablaPeriodo." WHERE cod_materia='$id_mat' and cod_seccion='$id_sec'"); 
$porce1=''; $porce2=''; $porce3=''; $porce4=''; $porce5='';
$porc1=''; $porc2=''; $porc3=''; $porc4=''; $porc5='';
$fecha1=''; $fecha2=''; $fecha3=''; $fecha4='';$fecha5='';
while($row=mysqli_fetch_array($porce_query))
{
    $porce1=$row['porcentaje1'.$lapso];
    $porce2=$row['porcentaje2'.$lapso];
    $porce3=$row['porcentaje3'.$lapso];
    $porce4=$row['porcentaje4'.$lapso];
    $porce5=$row['porcentaje5'.$lapso];
    $obser1=$row['obser1'.$lapso];
    $obser2=$row['obser2'.$lapso];
    $obser3=$row['obser3'.$lapso];
    $obser4=$row['obser4'.$lapso];
    $obser5=$row['obser5'.$lapso];
    $fecha1 = (empty($row['fecha1'.$lapso])) ? '' : date("d-m-Y", strtotime($row['fecha1'.$lapso])) ;
    $fecha2 = (empty($row['fecha2'.$lapso])) ? '' : date("d-m-Y", strtotime($row['fecha2'.$lapso])) ;
    $fecha3 = (empty($row['fecha3'.$lapso])) ? '' : date("d-m-Y", strtotime($row['fecha3'.$lapso])) ;
    $fecha4 = (empty($row['fecha4'.$lapso])) ? '' : date("d-m-Y", strtotime($row['fecha4'.$lapso])) ;
    $fecha5 = (empty($row['fecha5'.$lapso])) ? '' : date("d-m-Y", strtotime($row['fecha5'.$lapso])) ;
}
for ($i=1; $i <=5 ; $i++) { 
    
    ${'obser'.$i} = (${'porce'.$i}>0) ? ${'obser'.$i} : '********' ;
    ${'fecha'.$i} = (${'porce'.$i}>0) ? ${'fecha'.$i} : '********' ;
    ${'porce'.$i} = (${'porce'.$i}>0) ? ${'porce'.$i} : '********' ;
}
$alumnos_query=mysqli_query($link,"SELECT A.*, B.nacion, B.cedula, B.apellido, B.nombre FROM matri".$tablaPeriodo." A, alumcer B where A.statusAlum='1' and A.idAlumno=B.idAlum and A.grado='$id_gra' and A.idSeccion='$id_sec' and IF(A.escola='2',A.mat".$matCursa."='X',A.grado='$id_gra') ORDER BY B.apellido"); 
$resultado = $alumnos_query;  
require '../../colegio/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// Se crea el objeto PHPExcel
$objPHPExcel = new Spreadsheet();
// Se asignan las propiedades del libro
$objPHPExcel
    ->getProperties()->setCreator("Jesistemas") //Autor
    ->setLastModifiedBy(NKXS." ".EKKS) //Ultimo usuario que lo modificó
    ->setTitle("Reporte Excel desde Pagina WEB")
    ->setSubject("Reporte Excel desde Pagina WEB")
    ->setDescription("Estrategias")
    ->setKeywords("reporte de estudiantes")
    ->setCategory("Reporte excel");
$tituloReporte = NKXS." ".EKKS;
$linea2=$nomb_Gra." Seccion ".$nomb_Sec;
$linea3='Lapso '.$nomlapso;
$linea4='Docente '.$nomb_Pro;
$linea5='Materia '.$nomb_Mat;
$linea6='Codigo Materia '.$id_mat;
$tit1 = ($porce1>0) ? 'Nota 1' : 'No Usar' ;
$tit2 = ($porce2>0) ? 'Nota 2' : 'No Usar' ;
$tit3 = ($porce3>0) ? 'Nota 3' : 'No Usar' ;
$tit4 = ($porce4>0) ? 'Nota 4' : 'No Usar' ;
$tit5 = ($porce5>0) ? 'Nota 5' : 'No Usar' ;
$titulosColumnas = array('Cedula', 'Estudiante', $tit1, $tit2, $tit3, $tit4, $tit5);
if($resultado->num_rows > 0 )
{
    if (PHP_SAPI == 'cli')
        die('Este archivo solo se puede ver desde un navegador web');
    $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells('A1:C1')
                ->mergeCells('A2:C2')
                ->mergeCells('A3:C3')
                ->mergeCells('G1:K1')
                ->mergeCells('G2:K2')
                ->mergeCells('G3:K3')
                ->mergeCells('G4:K4')
                ->mergeCells('G5:K5')
                
                ->mergeCells('A6:B6')
                ->mergeCells('A7:B7')
                ->mergeCells('A8:B8')
                ->mergeCells('A9:B9')
                ->mergeCells('A10:B10')
                ->mergeCells('A11:B11')

                ->mergeCells('C6:F6')
                ->mergeCells('C7:F7')
                ->mergeCells('C8:F8')
                ->mergeCells('C9:F9')
                ->mergeCells('C10:F10')
                ->mergeCells('C11:F11')
                
                ->mergeCells('G6:H6')
                ->mergeCells('G7:H7')
                ->mergeCells('G8:H8')
                ->mergeCells('G9:H9')
                ->mergeCells('G10:H10')
                ->mergeCells('G11:H11')

                ->mergeCells('I6:K6')
                ->mergeCells('I7:K7')
                ->mergeCells('I8:K8')
                ->mergeCells('I9:K9')
                ->mergeCells('I10:K10')
                ->mergeCells('I11:K11')

                ->mergeCells('I13:O13')
                ->mergeCells('I14:O14')
                ->mergeCells('I15:O15')
                ->mergeCells('I16:O16')
                ->mergeCells('I17:O17');
    // Se agregan los titulos del reporte
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1',$tituloReporte)
                ->setCellValue('A2','M.P.P.E. '.CKLS)
                ->setCellValue('A3','Corte de Notas Periodo '.$nombrePeriodo)
                
                ->setCellValue('G1',$nomb_Gra." Seccion ".$nomb_Sec)
                ->setCellValue('G2','Lapso '.$nomlapso)
                ->setCellValue('G3','Docente '.$nomb_Pro)
                ->setCellValue('G4','Materia '.$nomb_Mat)
                ->setCellValue('G5','Codigo Materia '.$id_mat)

                ->setCellValue('A6','Estrategias')
                ->setCellValue('A7','1- '.$obser1)
                ->setCellValue('A8','2- '.$obser2)
                ->setCellValue('A9','3- '.$obser3)
                ->setCellValue('A10','4- '.$obser4)
                ->setCellValue('A11','5- '.$obser5)
                ->setCellValue('C6','Objetivos')
                ->setCellValue('G6','Fechas')
                ->setCellValue('G7',$fecha1)
                ->setCellValue('G8',$fecha2)
                ->setCellValue('G9',$fecha3)
                ->setCellValue('G10',$fecha4)
                ->setCellValue('G11',$fecha5)
                ->setCellValue('I6','Ponderacion')
                ->setCellValue('I7',$porce1.'%')
                ->setCellValue('I8',$porce2.'%')
                ->setCellValue('I9',$porce3.'%')
                ->setCellValue('I10',$porce4.'%')
                ->setCellValue('I11',$porce5.'%')

                ->setCellValue('A12',  $titulosColumnas[0])
                ->setCellValue('B12',  $titulosColumnas[1])
                ->setCellValue('C12',  $titulosColumnas[2])
                ->setCellValue('D12',  $titulosColumnas[3])
                ->setCellValue('E12',  $titulosColumnas[4])
                ->setCellValue('F12',  $titulosColumnas[5])
                ->setCellValue('G12',  $titulosColumnas[6])

                ->setCellValue('I13',  'IMPORTANTE!')
                ->setCellValue('I14',  'Solo debe agregar o modificar notas en las columnas sin')
                ->setCellValue('I15',  'asterisco (**), recuerde NO modificar ninguna otra celda')
                ->setCellValue('I16',  'ni la estructura de la hoja ya que puede ocasionar fallas')
                ->setCellValue('I17',  'en sus notas');
    $i = 13;
    while ($row = $resultado->fetch_array()) 
    {
        $nacion=$row['nacion'].'-'.$row['cedula'];
        $alumno=$row['apellido'].' '.$row['nombre'];
        $ced_alu=$row['cedula'];
        $corte_query=mysqli_query($link,"SELECT nota1".$lapso." as nota1, nota2".$lapso." as nota2, nota3".$lapso." as nota3, nota4".$lapso." as nota4, nota5".$lapso." as nota5 FROM cortes".$tablaPeriodo." WHERE ced_alu='$ced_alu' and cod_materia='$id_mat'"); 
        $nota1 = ($porce1>0) ? '' : '**' ;
        $nota2 = ($porce2>0) ? '' : '**' ;
        $nota3 = ($porce3>0) ? '' : '**' ;
        $nota4 = ($porce4>0) ? '' : '**' ;
        $nota5 = ($porce5>0) ? '' : '**' ;
        while($row2=mysqli_fetch_array($corte_query))
        {
            $nota1 = ($porce1>0) ? $row2['nota1'] : '**' ;
            $nota2 = ($porce2>0) ? $row2['nota2'] : '**' ;
            $nota3 = ($porce3>0) ? $row2['nota3'] : '**' ;
            $nota4 = ($porce4>0) ? $row2['nota4'] : '**' ;
            $nota5 = ($porce5>0) ? $row2['nota5'] : '**' ;
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  $nacion)
            ->setCellValue('B'.$i,  $alumno)
            ->setCellValue('C'.$i,  $nota1)
            ->setCellValue('D'.$i,  $nota2)
            ->setCellValue('E'.$i,  $nota3)
            ->setCellValue('F'.$i,  $nota4)
            ->setCellValue('G'.$i,  $nota5);
            $i++;
    }
    $b=$i-1;
    for($i = 'A'; $i <= 'B'; $i++){
        $objPHPExcel->setActiveSheetIndex(0)            
            ->getColumnDimension($i)->setAutoSize(TRUE);
    }
    // Se asigna el nombre a la hoja
    $objPHPExcel->getActiveSheet()->setTitle('Estudiantes');
    // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
    $objPHPExcel->setActiveSheetIndex(0);
    // Inmovilizar paneles 
    //$objPHPExcel->getActiveSheet(0)->freezePane('A4');
    $objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,13);
    $estiloTituloReporte = [
        'font' => [
            'name'      => 'Verdana',
            'bold'      => true,
            'italic'    => false,
            'strike'    => false,
            'size' =>12,
                'color'     => [
                    'argb' => 'FFFFFF'
                ]
        ],
        'alignment' =>  [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'rotation'   => 0,
                'wrap'          => TRUE
        ],
        'borders' => [
            'allborders' => [
                'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE                    
            ]
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
            'rotation' => 90,
            'startColor' => [
                'argb' => '2874A6',
            ],
            'endColor' => [
                'argb' => '2874A6',
            ],
        ],
    ];
    $estiloTituloColumnas = [
        'font' => [
            'name'      => 'Arial',
            'bold'      => true,                          
            'color'     => [
                'rgb' => 'FFFFFF'
            ]
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'wrap'          => FALSE
        ],
        'borders' => [
            'top'     => [
                'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE ,
                'color' => [
                    'rgb' => '143860'
                ]
            ],
            'bottom'     => [
                'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE ,
                'color' => [
                    'rgb' => '143860'
                ]
            ]
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
            'rotation' => 45,
            'startColor' => [
                'argb' => 'E0E0E0',
            ],
            'endColor' => [
                'argb' => '757575',
            ],
        ],
        'alignment' =>  [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrap'          => FALSE
        ]
    ];
    $estiloColorCeldas = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
            'rotation' => 90,
            'startColor' => [
                'argb' => 'EAEDED',
            ],
            'endColor' => [
                'argb' => 'EAEDED',
            ],
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
    ];
    $estiloInformacion1 = [
        'font' => [
            'name'      => 'Times',
            'italic'    => false,
            'strike'    => false,
            'size' =>10,
            'color'     => [
                    'argb' => '000000'
                ]
        ],
        'alignment' =>  [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'rotation'   => 0,
                'wrap'          => TRUE
        ]
    ];
    $estiloInformacion2 = [
        'font' => [
            'name'      => 'Times',
            'italic'    => false,
            'strike'    => false,
            'color'     => [
                    'argb' => '000000'
                ]
        ],
        'alignment' =>  [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'rotation'   => 0,
                'wrap'          => TRUE
        ],
    ];
    $estiloColorMensaje = [
        'font' => [
            'name'      => 'Verdana',
            'italic'    => false,
            'strike'    => false,
            'size' =>11,
                'color'     => [
                    'argb' => 'F4D03F'
                ]
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
            'rotation' => 90,
            'startColor' => [
                'argb' => 'C0392B',
            ],
            'endColor' => [
                'argb' => 'C0392B',
            ],
        ]
    ];
    $objPHPExcel->getActiveSheet()->getStyle('A6:K11')->applyFromArray($estiloColorCeldas);
    $objPHPExcel->getActiveSheet()->getStyle('G1:K5')->applyFromArray($estiloColorCeldas);
    $objPHPExcel->getActiveSheet()->getStyle('I13:O17')->applyFromArray($estiloColorMensaje);

    $objPHPExcel->getActiveSheet()->getStyle('A13:G'.$b)->applyFromArray($estiloColorCeldas);
    $objPHPExcel->getActiveSheet()->getStyle('A6:K11')->applyFromArray($estiloInformacion1);
    $objPHPExcel->getActiveSheet()->getStyle('G1:K5')->applyFromArray($estiloInformacion1);

    $objPHPExcel->getActiveSheet()->getStyle('A13:B'.$b)->applyFromArray($estiloInformacion1);
    $objPHPExcel->getActiveSheet()->getStyle('C13:G'.$b)->applyFromArray($estiloInformacion2);

    
    $objPHPExcel->getActiveSheet()->getStyle('A1:B3')->applyFromArray($estiloTituloReporte);
    $objPHPExcel->getActiveSheet()->getStyle('A12:G12')->applyFromArray($estiloTituloColumnas);

    $nombreDelDocumento = "Estrategias de ".$id_gra.$nomb_Sec." ".$nomb_Mat.".xlsx";
    // Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
    header('Cache-Control: max-age=0');

    $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
    $objWriter->save('php://output');
    exit;
}else 
{
    print_r('No hay resultados para mostrar '.$filtro);
}



/*if($resultado->num_rows > 0 )
{
	if (PHP_SAPI == 'cli')
		die('Este archivo solo se puede ver desde un navegador web');
	//require_once '../includes/PHPExcel/PHPExcel.php';
	
	$estiloTituloReporte = array(
        'font' => array(
            'name'      => 'Verdana',
            'bold'      => true,
            'italic'    => false,
            'strike'    => false,
            'size' =>12,
                'color'     => array(
                    'rgb' => 'FFFFFF'
                )
        ),
        'fill' => array(
            'type'  => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('argb' => '2874A6')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_NONE                    
            )
        ), 
        'alignment' =>  array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'rotation'   => 0,
                'wrap'          => TRUE
        )
    );

    $estiloTituloColumnas = array(
        'font' => array(
            'name'      => 'Arial',
            'bold'      => true,                          
            'color'     => array(
                'rgb' => 'FFFFFF'
            )
        ),
        'fill'  => array(
            'type'      => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
            'rotation'   => 45,
            'startcolor' => array(
                'rgb' => 'E0E0E0'
            ),
            'endcolor'   => array(
                'argb' => '757575'
            )
        ),
        'borders' => array(
            'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_NONE ,
                'color' => array(
                    'rgb' => '143860'
                )
            ),
            'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_NONE ,
                'color' => array(
                    'rgb' => '143860'
                )
            )
        ),
        'alignment' =>  array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'          => FALSE
        ));
        
    $estiloInformacion = new PHPExcel_Style();
    $estiloInformacion->applyFromArray(
        array(
            'font' => array(
            'name'      => 'Arial',               
            'color'     => array(
                'rgb' => '000000'
            )
        ),
        'fill'  => array(
            'type'      => PHPExcel_Style_Fill::FILL_SOLID,
            'color'     => array('argb' => 'D5DBDB')
        ),
        'borders' => array(
            'allborders'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN ,
                'color' => array(
                    'rgb' => '757575'
                )
            )             
        ),
        'alignment' =>  array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'          => TRUE
        )
    ));
	$objPHPExcel->getActiveSheet()->getStyle('A1:B3')->applyFromArray($estiloTituloReporte);
    $objPHPExcel->getActiveSheet()->getStyle('A12:G12')->applyFromArray($estiloTituloColumnas);

    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A6:J11");        
    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A13:G".($i-1)); 
    //$objPHPExcel->getActiveSheet()->getStyle('J3:J500') ->getNumberFormat() ->setFormatCode('0'); 
	

	// Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
	/*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="Estrategias de '.$id_gra.$nomb_Sec.' '.$nomb_Mat.'.xlsx"');
	header('Cache-Control: max-age=0');

	$objWriter = new Xlsx($documento); //PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('Estrategias de '.$id_gra.$nomb_Sec.' '.$nomb_Mat.'.xlsx'); 
    exit;
	
}
else 
{
	print_r('No hay resultados para mostrar '.$filtro);
}*/

?>