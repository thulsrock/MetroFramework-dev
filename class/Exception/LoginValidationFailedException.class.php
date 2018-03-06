<?php

class LoginValidationFailedException extends Exception {
	
	protected $message;
	
	public function __construct( $message, $code = NULL ) {
		$this->message .= $message . '<br>';
		parent::__construct( $this->message, $code );
	}
	
}