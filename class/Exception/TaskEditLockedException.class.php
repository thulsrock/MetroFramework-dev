<?php

class TaskEditLockedException extends Exception {
	
	private $message = "Attività conclusa: non è possibile apportare ulteriori modifiche.";
	
	public function __construct( $message, $code = NULL ) {
		$this->message = $message;
		parent::__construct( $this->message, $code );
	}
}