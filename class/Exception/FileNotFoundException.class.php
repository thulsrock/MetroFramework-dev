<?php 

class FileNotFoundException extends Exception {
		
	protected $message;
	
	public function __construct( $message, $code = NULL ) {
		$this->message = $message;
		parent::__construct( $this->message, $code );
	}
}
