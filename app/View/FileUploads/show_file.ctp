	<?php		
		$file = new File(Configure::read('MediaPath').str_replace('<>','/',$this->request->params['pass'][0]));
		$contents = $file->read();
		require_once str_replace('webroot/','',Configure::read('MediaPath')).'Vendor/Excel/PHPExcel/IOFactory.php';
		$objPHPExcel = PHPExcel_IOFactory::load($file->path);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
		$objWriter->save('php://output');		
		
	?>
