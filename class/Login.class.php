<?php

class Login {
	
	private $pdo;
	
	public function __construct() {
		$dao = new Dao();
		$this->pdo = $dao->getPdo();
	}
		
	public function validation( String $username, String $password ) {
		$user = new User();
		try {
			$this->credentialsAreNotNull( $username, $password );
			$user->userExists( $username );
			$user->verifyPassword( $username, $password );
			$user->userIsActive( $username );
		} catch ( Exception $e ) {
			throw new LoginValidationFailedException( $e->getMessage() );
			return FALSE;
		}
	}
	
	public function credentialsAreNotNull( String $username, String $password ) {
		if( $username == '' || $password == '' ) {
			throw new LoginNullCredentialsException();
		}
	}
	
}