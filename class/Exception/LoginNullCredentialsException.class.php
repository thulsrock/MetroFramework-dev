<?php

class LoginNullCredentialsException extends Exception {
	
	protected $message = "Credenziali Nulle.";
	
	public function __construct( $code = NULL ) {
		parent::__construct( $this->message, $code );
	}
	
}