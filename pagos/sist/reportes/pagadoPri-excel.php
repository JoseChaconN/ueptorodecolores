<?php
session_start();
#error_reporting(E_ALL);
#ini_set('display_errors', '1');
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])))
{
    include ('../include/sesion.php');
}else
{
    include ('../../../conexion.php');
}
include ('../../../inicia.php');
$link = Conectarse();
$nombre_periodo=$_GET['peri'];
$grado=$_GET['grado'];
$secci=$_GET['secc'];
$idUser=$_SESSION['idUser'];
$grado = ($grado==1) ? 0 : $grado ;
$periAct_query=mysqli_query($link,"SELECT id_periodo, nombre_periodo, tablaPeriodo FROM  periodos where nombre_periodo='$nombre_periodo' "); 
while($row=mysqli_fetch_array($periAct_query))
{
    $idPeriodo=trim($row['id_periodo']);
    $nombrePeriodo=$row['nombre_periodo'];
    $tablaPeriodo=trim($row['tablaPeriodo']);
}
#echo $idPeriodo.' '.$nombrePeriodo.' '.$tablaPeriodo.' '.$grado.' '.$secci;
#die();

if ($grado>0) {
    $montos_query=mysqli_query($link,"SELECT * FROM montos".$tablaPeriodo." where id_grado='$grado' "); 
}else{
    $montos_query=mysqli_query($link,"SELECT * FROM montos".$tablaPeriodo." where id_grado='51' "); 
}
$va=1;
while($row=mysqli_fetch_array($montos_query))
{
    ${'monto'.$va}=$row['monto'];
    ${'total'.$va}=0;
    $va++;
}
if($grado>0 && $secci>0){
    $alumnos_query=mysqli_query($link,"SELECT A.idAlumno,A.desc1,A.desc2,A.desc3,A.desc4,A.desc5,A.desc6,A.desc7,A.desc8,A.desc9,A.desc10,A.desc11,A.desc12,A.desc13,A.grado, B.nacion, B.cedula, B.apellido, B.nombre, B.ced_rep, C.nombreGrado as nomGra, D.nombre as nombreSeccion FROM notaprimaria".$tablaPeriodo." A, alumcer B, grado".$tablaPeriodo." C, secciones D where A.statusAlum='1' and A.idAlumno=B.idAlum and A.idSeccion=D.id and A.grado=C.grado and A.grado='$grado' and A.idSeccion='$secci' ORDER BY A.grado, A.idSeccion, B.apellido"); 
}
if($grado==0 && $secci==0){
    $alumnos_query=mysqli_query($link,"SELECT A.idAlumno,A.desc1,A.desc2,A.desc3,A.desc4,A.desc5,A.desc6,A.desc7,A.desc8,A.desc9,A.desc10,A.desc11,A.desc12,A.desc13,A.grado, B.nacion, B.cedula, B.apellido, B.nombre, B.ced_rep, C.nombreGrado as nomGra, D.nombre as nombreSeccion FROM notaprimaria".$tablaPeriodo." A, alumcer B, grado".$tablaPeriodo." C, secciones D where A.statusAlum='1' and A.idAlumno=B.idAlum and A.idSeccion=D.id and A.grado=C.grado ORDER BY A.grado, A.idSeccion, B.apellido"); 
}
if($grado==0 && $secci>0){
    $alumnos_query=mysqli_query($link,"SELECT A.idAlumno,A.desc1,A.desc2,A.desc3,A.desc4,A.desc5,A.desc6,A.desc7,A.desc8,A.desc9,A.desc10,A.desc11,A.desc12,A.desc13,A.grado, B.nacion, B.cedula, B.apellido, B.nombre, B.ced_rep, C.nombreGrado as nomGra, D.nombre as nombreSeccion FROM notaprimaria".$tablaPeriodo." A, alumcer B, grado".$tablaPeriodo." C, secciones D where A.statusAlum='1' and A.idAlumno=B.idAlum and A.idSeccion=D.id and A.grado=C.grado and A.idSeccion='$secci' ORDER BY A.grado, A.idSeccion, B.apellido"); 
}
if($grado>0 && $secci==0){
    $alumnos_query=mysqli_query($link,"SELECT A.idAlumno,A.desc1,A.desc2,A.desc3,A.desc4,A.desc5,A.desc6,A.desc7,A.desc8,A.desc9,A.desc10,A.desc11,A.desc12,A.desc13,A.grado, B.nacion, B.cedula, B.apellido, B.nombre, B.ced_rep, C.nombreGrado as nomGra, D.nombre as nombreSeccion FROM notaprimaria".$tablaPeriodo." A, alumcer B, grado".$tablaPeriodo." C, secciones D where A.statusAlum='1' and A.idAlumno=B.idAlum and A.idSeccion=D.id and A.grado=C.grado and A.grado='$grado' ORDER BY A.grado, A.idSeccion, B.apellido"); 
}
$resultado = $alumnos_query;  
if($resultado->num_rows > 0 )
{
	if (PHP_SAPI == 'cli')
		die('Este archivo solo se puede ver desde un navegador web');

	/** Se agrega la libreria PHPExcel */
	require_once '../../../includes/PHPExcel/PHPExcel.php';

	// Se crea el objeto PHPExcel
	$objPHPExcel = new PHPExcel();

	// Se asignan las propiedades del libro
	$objPHPExcel->getProperties()->setCreator("Jesistemas") //Autor
    							 ->setLastModifiedBy(NKXS." ".EKKS) //Ultimo usuario que lo modificó
    							 ->setTitle("Reporte Excel desde Pagina WEB")
    							 ->setSubject("Reporte Excel desde Pagina WEB")
    							 ->setDescription("Listado de Pagos")
    							 ->setKeywords("reporte de Administracion")
    							 ->setCategory("Reporte excel");
	$tituloReporte = "Primaria Montos Pagados Periodo Escolar ".$nombrePeriodo;
    
    $titulosColumnas = array('Apellido','Nombre','Grado','Telefono','Insc.','Sep.','Oct.','Nov.','Dic.','Ene.','Feb.','Mar.','Abr.','May.','Jun.','Jul.','Ago.');
    
    $a=0;
    $objPHPExcel->setActiveSheetIndex(0)
    ->mergeCells('A1:Q1');
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1',$tituloReporte)
        ->setCellValue('A2',  $titulosColumnas[0])
        ->setCellValue('B2',  $titulosColumnas[1])
        ->setCellValue('C2',  $titulosColumnas[2])
        ->setCellValue('D2',  $titulosColumnas[3])
        ->setCellValue('E2',  $titulosColumnas[4])
        ->setCellValue('F2',  $titulosColumnas[5])
        ->setCellValue('G2',  $titulosColumnas[6])
        ->setCellValue('H2',  $titulosColumnas[7])
        ->setCellValue('I2',  $titulosColumnas[8])
        ->setCellValue('J2',  $titulosColumnas[9])
        ->setCellValue('K2',  $titulosColumnas[10])
        ->setCellValue('L2',  $titulosColumnas[11])
        ->setCellValue('M2',  $titulosColumnas[12])
        ->setCellValue('N2',  $titulosColumnas[13])
        ->setCellValue('O2',  $titulosColumnas[14])
        ->setCellValue('P2',  $titulosColumnas[15])
        ->setCellValue('Q2',  $titulosColumnas[16]);
    $i = 3;
	while ($row = $resultado->fetch_array()) 
    {
        $idAlum=$row['idAlumno'];
        $graSec=substr($row['grado'], 1,1).'° '.$row['nombreSeccion'];
        $nacionAlum=$row['nacion'].'-'.$row['cedula'];
        $apellidoAlum=$row['apellido'];
        $nombreAlum=$row['nombre'];
        $estudiante=$row['apellido'].' '.$row['nombre'];
        $cedulaRepre=$row['ced_rep'];
        $nomGra=$row['nomGra'];
        $nombreSeccion=$row['nombreSeccion'];
        $pagos_query = mysqli_query($link,"SELECT SUM(montoDolar) as pagado FROM pagos".$tablaPeriodo."  WHERE idAlum='$idAlum' and (id_concepto=1 or id_concepto=2 or id_concepto=8) and statusPago=1 ");
        if(mysqli_num_rows($pagos_query) > 0)
        {
            $row2=mysqli_fetch_array($pagos_query);
            $pagado=$row2['pagado'];
        }else{
            $pagado=0;
        }
        for ($i2=1; $i2 <=13 ; $i2++) { 
            ${'desc'.$i2}=$row['desc'.$i2];
            if ($pagado>0) {
                ${'pago'.$i2}=${'monto'.$i2}-$row['desc'.$i2]; 
                if($pagado<${'pago'.$i2}){
                    ${'pago'.$i2}=$pagado;
                }
                $pagado=$pagado-${'pago'.$i2};
            }else{
                ${'pago'.$i2}=0;
            }
            ${'total'.$i2}=${'total'.$i2}+${'pago'.$i2};
            ${'pago'.$i2}=${'pago'.$i2};
        }
        $nombreRepre=''; $celularRepre=''; 
	    $repre_query=mysqli_query($link,"SELECT * FROM represe where cedula='$cedulaRepre'");
	    while($row = mysqli_fetch_array($repre_query))
	    {
	        $nombreRepre=($row['representante']);
	        $celularRepre=' '.$row['tlf_celu'];
	    }
        $datosColumnas = array($apellidoAlum,$nombreAlum,$graSec,$celularRepre,$pago1, $pago2, $pago3, $pago4, $pago5, $pago6, $pago7, $pago8, $pago9, $pago10, $pago11, $pago12,$pago13);
        $a=0;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  $datosColumnas[0])
            ->setCellValue('B'.$i,  $datosColumnas[1])
            ->setCellValue('C'.$i,  $datosColumnas[2])
            ->setCellValue('D'.$i,  $datosColumnas[3])
            ->setCellValue('E'.$i,  $datosColumnas[4])
            ->setCellValue('F'.$i,  $datosColumnas[5])
            ->setCellValue('G'.$i,  $datosColumnas[6])
            ->setCellValue('H'.$i,  $datosColumnas[7])
            ->setCellValue('I'.$i,  $datosColumnas[8])
            ->setCellValue('J'.$i,  $datosColumnas[9])
            ->setCellValue('K'.$i,  $datosColumnas[10])
            ->setCellValue('L'.$i,  $datosColumnas[11])
            ->setCellValue('M'.$i,  $datosColumnas[12])
            ->setCellValue('N'.$i,  $datosColumnas[13])
            ->setCellValue('O'.$i,  $datosColumnas[14])
            ->setCellValue('P'.$i,  $datosColumnas[15])
            ->setCellValue('Q'.$i,  $datosColumnas[16]);
		$i++;
	}
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i,  '')
            ->setCellValue('B'.$i,  '')
            ->setCellValue('C'.$i,  '')
            ->setCellValue('D'.$i,  'Totales->')
            ->setCellValue('E'.$i,  $total1)
            ->setCellValue('F'.$i,  $total2)
            ->setCellValue('G'.$i,  $total3)
            ->setCellValue('H'.$i,  $total4)
            ->setCellValue('I'.$i,  $total5)
            ->setCellValue('J'.$i,  $total6)
            ->setCellValue('K'.$i,  $total7)
            ->setCellValue('L'.$i,  $total8)
            ->setCellValue('M'.$i,  $total9)
            ->setCellValue('N'.$i,  $total10)
            ->setCellValue('O'.$i,  $total11)
            ->setCellValue('P'.$i,  $total12)
            ->setCellValue('Q'.$i,  $total13);
	$estiloTituloReporte = array(
        'font' => array(
            'name'      => 'Verdana',
            'bold'      => true,
            'italic'    => false,
            'strike'    => false,
            'size' =>16,
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
            'rotation'   => 90,
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
                'wrap'          => TRUE
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
            'type'      => PHPExcel_Style_Fill::FILL_SOLID
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
    $estiloInformacion2 = new PHPExcel_Style();
    $estiloInformacion2->applyFromArray(
        array(
            'font' => array(
            'name'      => 'Arial',               
            'color'     => array(
                'rgb' => '000000'
            )
        ),
        'fill'  => array(
            'type'      => PHPExcel_Style_Fill::FILL_SOLID
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
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'          => TRUE
        )
    ));
	$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->applyFromArray($estiloTituloReporte);
    $objPHPExcel->getActiveSheet()->getStyle('A2:Q2')->applyFromArray($estiloTituloColumnas);       
    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A3:G".($i-1)); 
    $objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion2, "E3:Q".($i-1)); 
    $objPHPExcel->getActiveSheet()->getStyle('E3:Q600') ->getNumberFormat() ->setFormatCode('#,##0.00');
    //$objPHPExcel->getActiveSheet()->getStyle('H3:T800') ->getNumberFormat() ->setFormatCode('0'); 
	for($i = 'A'; $i <= 'Q'; $i++){
		$objPHPExcel->setActiveSheetIndex(0)			
			->getColumnDimension($i)->setAutoSize(TRUE);
	}
	// Se asigna el nombre a la hoja
	$objPHPExcel->getActiveSheet()->setTitle('Estudiantes');
	// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
	$objPHPExcel->setActiveSheetIndex(0);
	// Inmovilizar paneles 
	//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
	$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,3);
	// Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="Listado de Pagos.xlsx"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output'); 
    exit;
}
else 
{
	print_r('No hay resultados para mostrar ');
}?>