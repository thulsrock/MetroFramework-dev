<?php

class Logger {
	
	private $sessionID;
	private $username;
	
	public function __construct( $sessionID, $username ) {
		$dao = new Dao();
		$this->pdo = $dao->getPdo();
		
		$this->sessionID = $sessionID;
		$this->username = $username;
	}
	
	public function log( $msg ) {
		
	}
}