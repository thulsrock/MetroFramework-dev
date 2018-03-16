<?php

$pdf = new \setasign\Fpdi\Fpdi();

$taskManager = new Task();
$task = $taskManager->getTaskDetail( $target, $code );
$task->department = $taskManager->getDepartment( $target );
$fileDir = UPLOAD_DIR . $task->department->name . DIRECTORY_SEPARATOR . $target . DIRECTORY_SEPARATOR . $code;

$currentYearPath =	UPLOAD_REPORTS  . DIRECTORY_SEPARATOR . CURRENT_YEAR;
$departmentPath =	$currentYearPath. DIRECTORY_SEPARATOR . $task->department->name;
$targetPath = 		$departmentPath . DIRECTORY_SEPARATOR . $target;

if( !file_exists( $currentYearPath ) )	mkdir( $currentYearPath );
if( !file_exists( $departmentPath ) )	mkdir( $departmentPath );
if( !file_exists( $targetPath ) ) 		mkdir( $targetPath );

$mergedFilePath = $targetPath . DIRECTORY_SEPARATOR;
$mergedFilename = $target . $code .'-report.pdf';

$sourcefilePathList = '';

if( file_exists( $fileDir ) ) {
	
	$files = scandir( $fileDir );
	
	$filename = $target . $code . '.pdf';
	$filenamePosition = array_search( $filename, $files );
	
	unset( $files[$filenamePosition]);
	array_unshift($files, $filename);
	
	foreach ( $files as $file ) {
		if ($file == '.' || $file == '..' ) continue;
		$pageCount = $pdf->setSourceFile($fileDir . DIRECTORY_SEPARATOR . $file);
		// iterate through all pages
		for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
			// import a page
			$templateId = $pdf->importPage($pageNo);
			// get the size of the imported page
			$size = $pdf->getTemplateSize($templateId);
			// create a page (landscape or portrait depending on the imported page size)
			if ($size['width'] > $size['height']) {
				$pdf->AddPage('L', array($size['width'], $size['height']));
			} else {
				$pdf->AddPage('P', array($size['width'], $size['height']));
			}
			
			// use the imported page
			$pdf->useTemplate($templateId);
		}
	}
}

$pdf->Output($mergedFilePath . $mergedFilename,'F');