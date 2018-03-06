<?php

class Render {
	
	protected $message;
	
	public function __construct( $message, $log = FALSE, $sessionID = NULL, $username = NULL ) {
		
		$this->message = $message;
		
		if( $log ) $this->log( $sessionID, $username );
	}
	
	public function log( $sessionID, $username ) {
		$logger = new Logger( $session->getID(), $session->getUsername() );
		$logger->log( $this->message->getMessage(), $message);
	}
	
	public function exceptionRender() {
		echo "<div class='exception'>" . $this->message->getMessage() ."</div>";
	}
	
	public function noticeRender() {
		echo "<div class='notice'>" . $this->message->getMessage() ."</div>";
	}
	
}