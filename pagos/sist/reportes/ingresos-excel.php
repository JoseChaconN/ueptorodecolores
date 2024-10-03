<?php
session_start();
if((!isset($_SESSION['usuario']) && !isset($_SESSION['password'])))
{
	include_once ('../include/sesion.php');
}else
{
	include_once ('../../../conexion.php');
}
$link = Conectarse();

$desde = $_GET["desde"] ;
$hasta = $_GET["hasta"];
$desBus=$desde.' 00:00:00';
$hasBus=$hasta.' 24:59:59';
$desde1=date("d-m-Y", strtotime($_GET['desde']));
$hasta1=date("d-m-Y", strtotime($_GET['hasta']));
$recDesde=$_POST['recDes'];
$recHasta=$_POST['recHas'];
//echo $desBus.' '.$hasBus.' '.$recDesde.' '.$recHasta;
//die();
if($recDesde>0)
{
    $consulta = mysqli_query($link,"SELECT id, fecha, tabla FROM ingresos WHERE id>='$recDesde' and id<='$recHasta' ");    
}else
{
    $consulta = mysqli_query($link,"SELECT id, fecha, tabla FROM ingresos WHERE fecha>='$desBus' AND fecha<='$hasBus' ");
}
$bancos_query = mysqli_query($link,"SELECT cod_banco,nom_banco FROM bancos WHERE banco_mio='X' ");
$van=0;
while($row3=mysqli_fetch_array($bancos_query)) 
{
	$van++;
	${'banco'.$van}=$row3['nom_banco'];
	${'cod_banco'.$van}=$row3['cod_banco'];
	${'totBanco'.$van}=0;
}

$resultado = $consulta; $subDiv=0; $subBs=0; 
if($resultado->num_rows > 0 )
{
					
	date_default_timezone_set('America/Caracas');

	if (PHP_SAPI == 'cli')
		die('Este archivo solo se puede ver desde un navegador web');

	require_once '../include/excel/lib/PHPExcel/PHPExcel.php';

	$objPHPExcel = new PHPExcel();

	$objPHPExcel->getProperties()->setCreator("Jesistemas") //Autor
		 ->setLastModifiedBy("Jesistemas") //Ultimo usuario que lo modificó
		 ->setTitle("Reporte Excel desde Pagina WEB")
		 ->setSubject("Reporte Excel desde Pagina WEB")
		 ->setDescription("Ingresos Diarios")
		 ->setKeywords("aaaaaaaa")
		 ->setCategory("Reporte excel");

	$tituloReporte = "Resumen de Ingresos Diarios Desde: ".$desde1.' Hasta: '.$hasta1;
	$titulosColumnas = array('Fecha','Cedula','Estudiante', 'Representante', 'N° Factura', 'Codigos', 'Descripcion','Monto en $','Monto Tasa','Monto Bs.','Forma de Pago');
	
	$objPHPExcel->setActiveSheetIndex(0)
    		    ->mergeCells('A1:K1');
					
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1',$tituloReporte)
    		    ->setCellValue('A3',  $titulosColumnas[0])
	            ->setCellValue('B3',  $titulosColumnas[1])
    		    ->setCellValue('C3',  $titulosColumnas[2])
        		->setCellValue('D3',  $titulosColumnas[3])
        		->setCellValue('E3',  $titulosColumnas[4])
        		->setCellValue('F3',  $titulosColumnas[5])
        		->setCellValue('G3',  $titulosColumnas[6])
        		->setCellValue('H3',  $titulosColumnas[7])
        		->setCellValue('I3',  $titulosColumnas[8])
        		->setCellValue('J3',  $titulosColumnas[9])
        		->setCellValue('K3',  $titulosColumnas[10]);
    
    $i = 4;
	$totEfe=0;$totTra=0;$totDep=0;$totDeb=0; $efecBs=0;
	while ($fila = $resultado->fetch_array()) 
	{
		$recibo=$fila['id'];
	    $tablaPeriodo=$fila['tabla'];
	    $fechaOpe=date("d-m-Y", strtotime($fila['fecha']));
	    $recibos=' '.str_pad($recibo, 6, "0", STR_PAD_LEFT);
	    $pagos_query = mysqli_query($link,"SELECT A.montoDolar,A.monto, A.montoTasa,A.operacion,A.banco,A.concepto,A.id_concepto, B.cedula,B.nombre, B.apellido,B.ced_rep, C.abrev FROM pagos".$tablaPeriodo." A, alumcer B, formas_pago C WHERE A.recibo='$recibo' and A.idAlum=B.idAlum and A.operacion=C.id ");
		$montoDolar=0; 	$montoBs=0; $fPago=''; $concepto=''; $tipo='';
		while($row2=mysqli_fetch_array($pagos_query)) 
	    {
	    	$cedula=$row2['cedula'];
	        $alumno=($row2['apellido'].' '.$row2['nombre']);
	        $ced_rep=$row2['ced_rep'];
	        $tipo.=str_pad($row2['id_concepto'], 2, "0", STR_PAD_LEFT).', ' ;
	        $concepto.=$row2['concepto'].', ' ;

	        if($row2['operacion']=='1'){$montoDolar=$montoDolar+$row2['montoDolar'];}
	        if($row2['operacion']=='2'){$efecBs=$efecBs+$row2['monto'];}
	        if($row2['operacion']!='1'){$montoBs=$montoBs+$row2['monto'];}
	        $tasaDolar=$row2['montoTasa'];
	        for ($x=1; $x <= $van; $x++) { 
	        	if(${'cod_banco'.$x}==$row2['banco']){
	        		${'totBanco'.$x}=${'totBanco'.$x}+$row2['monto'];
	        	}
	        }
	    }
	    $formaPago_query = mysqli_query($link,"SELECT C.abrev FROM pagos".$tablaPeriodo." A, formas_pago C WHERE A.recibo='$recibo' and A.operacion=C.id GROUP BY A.operacion ");
	    while($row3=mysqli_fetch_array($formaPago_query)) 
	    {
	    	$fPago.=$row3['abrev'].', ';
	    }
	    $subDiv=$subDiv+$montoDolar;
    	$subBs=$subBs+$montoBs;
		$repre_query = mysqli_query($link,"SELECT representante FROM represe WHERE cedula='$ced_rep'");
		$representante='';
		while ($row4 = $repre_query->fetch_array()) 
		{
			$representante=$row4['representante'];
		}
		
		$objPHPExcel->setActiveSheetIndex(0)
	    		    ->setCellValue('A'.$i,  $fechaOpe)
	    		    ->setCellValue('B'.$i,  $cedula) 
		            ->setCellValue('C'.$i,  $alumno)
		            ->setCellValue('D'.$i,  $representante)
		            ->setCellValue('E'.$i,  $recibos)
		            ->setCellValue('F'.$i,  $tipo)
		            ->setCellValue('G'.$i,  $concepto)
		            ->setCellValue('H'.$i,  $montoDolar)
		            ->setCellValue('I'.$i,  $tasaDolar)
		            ->setCellValue('J'.$i,  $montoBs)
	        		->setCellValue('K'.$i,  $fPago);
					$i++;
		
	}
	$i++;
	$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('G'.$i,  'TOTALES ->')
	    		    ->setCellValue('H'.$i,  $subDiv)
		            ->setCellValue('J'.$i,  $subBs);
	
    $i=$i+2;
    $objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('H'.$i,  'Efectivo Bs. ->')
	    		    ->setCellValue('J'.$i,  $efecBs);
	$i++;
	for ($y=1; $y <=$van ; $y++) { 
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('H'.$i,  'Banco '.${'banco'.$y}.'-> ') 
    		->setCellValue('J'.$i,  number_format(${'totBanco'.$y},2,'.',','));
    		$i++;
	}

	

	
	
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
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => '4285F4')
		),
        'borders' => array(
           	'allborders' => array(
            	'style' => PHPExcel_Style_Border::BORDER_NONE                    
           	)
        ), 
        'alignment' =>  array(
    			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
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
        'fill' 	=> array(
			'type'		=> PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
			'rotation'   => 90,
    		'startcolor' => array(
        		'rgb' => '0d47a1'
    		),
    		'endcolor'   => array(
        		'argb' => '0d47a1'
    		)
		),
        'borders' => array(
        	'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
                'color' => array(
                    'rgb' => 'FFFFFF'
                )
            ),
            'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
                'color' => array(
                    'rgb' => 'FFFFFF'
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
           	'size' =>10,               
           	'color'     => array(
            'rgb' => '000000'
           	)
       	),
       	'fill' 	=> array(
			'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'		=> array('argb' => 'E5E8E8')
		),
       	'borders' => array(
           	'allborders'     => array(
               	'style' => PHPExcel_Style_Border::BORDER_THIN ,
                'color' => array(
	            	'rgb' => 'F2F4F4'
               	)
           	)             
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
       	'fill' 	=> array(
			'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'		=> array('argb' => 'E5E8E8')
		),
       	'borders' => array(
           	'left'     => array(
               	'style' => PHPExcel_Style_Border::BORDER_THIN ,
                'color' => array(
	            	'rgb' => '3a2a47'
               	)
           	)             
       	)
    ));
	 
	$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($estiloTituloReporte);
	$objPHPExcel->getActiveSheet()->getStyle('A3:K3')->applyFromArray($estiloTituloColumnas);

	$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:K".($i-1));
	$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion2, "I4:K".($i-1));
	$objPHPExcel->getActiveSheet()->getStyle('H4:J1000') ->getNumberFormat() ->setFormatCode('#,##0.00');
	for($i = 'A'; $i <= 'K'; $i++){
		$objPHPExcel->setActiveSheetIndex(0)			
			->getColumnDimension($i)->setAutoSize(TRUE);
	}
	
	// Se asigna el nombre a la hoja
	$objPHPExcel->getActiveSheet()->setTitle('Ingresos');

	// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
	$objPHPExcel->setActiveSheetIndex(0);
	// Inmovilizar paneles 
	//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
	$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

	// Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="IngresosDiarios.xlsx"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
	
}
else {
	print_r('No hay resultados para mostrar');
}



?>