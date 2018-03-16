<?php

class Login {
	
	private $username;
	private $password;
	private $loggedUser;
	
	public function __construct( String $username, String $password ) {
		$this->username = $username;
		$this->password = $password;
		$this->validation();
	}
		
	public function validation() {
		$userDao = new UserDAO();
		try {
			$this->credentialsAreNotNull();
			$userDao->userExists($this->username);
			$user = $userDao->getUserFromUsername($this->username);
			$account = new Account();
			$account->verifyPassword( $this->username, $this->password );
			$this->loggedUser = $user;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}

	}
	
	public function credentialsAreNotNull() {
		if ( fieldIsEmpty($this->username) || fieldIsEmpty($this->password) ) {
			throw new Exception(LOGIN_NULL_CREDENTIALS);
		}
	}
	
	public function getLoggedUser() {
		return $this->loggedUser;
	}
}