<?php 

	include_once "functions.php";
	
	error_reporting( ERROR_REPORTING );
	
	$em = EventMediator::getInstance();
	$em->main();
