<?php

class SessionNoGlobalsException extends Exception {

	protected $message = "Impossibile inizializzare le variabili.";
	
	public function __construct( $code = NULL ) {
		parent::__construct( $this->message, $code );
	}

}