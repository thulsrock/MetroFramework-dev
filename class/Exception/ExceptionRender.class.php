<?php

class ExceptionRender extends Render {
		
	public function __construct( $message, $log = FALSE, $sessionID = NULL, $username = NULL ) {
		
		parent::__construct($message, $log, $sessionID, $username );
		$this->exceptionRender();
	}
}