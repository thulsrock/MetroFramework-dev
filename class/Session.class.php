<?php

class Session {
	
	private static $instance = NULL;

	private function __construct() {
		session_start();
		$_SESSION['sessionID'] = session_id();
		if( !isset( $_SESSION['user_is_logged'] ) ) {
			$_SESSION['user_is_logged'] = FALSE;
		}
	}
	
	public static function getInstance() {
		if( self::$instance == NULL ) {
			$c = __CLASS__;
			self::$instance = new $c; 
		}
		return self::$instance;
	}
		
	public function updateAfterLogin ( $user ) {
		$_SESSION['user'] = $user;
		$_SESSION['user_is_logged'] = TRUE;
		$userDAO = new UserDAO();
		
		try {
			$_SESSION['user']->jobs = $userDAO->getJobsAndFeaturesFromUserID( $_SESSION['user']->getID() );
		} catch (Exception $e) {
			throw new Exception(SESSION_NO_GLOBAL_INIT);
		}
	}
	
	public function getActiveUserJobs() {
		return $_SESSION['user']->jobs;
	}

	public function isLogged() {
		return $_SESSION['user_is_logged'] == TRUE;
	}
	
	public function isNotLogged() {
		return !$this->isLogged();
	}

	public function getUsername() {
		return $_SESSION['user']->username;
	}
	
	public function getID() {
		return $_SESSION['sessionID'];
	}
	
	public function hasPrivilege( $privilege ) {
		foreach( $this->getActiveUserJobs() as $job ) {
			if( isset( $job->features ) ) {
				foreach ( $job->features as $feature ) {
					if( strpos( $feature, $privilege ) !== FALSE ) {
						return TRUE;
					}
				}
			}
		} 
	}

	public function getDepartments() {
		foreach( $this->getActiveUserJobs() as $job ) {
			$deps[] = $job->department;
		}
		return $deps;
	}
	
	public function logout() {
		$this->setUserNotLogged();
		session_destroy();
		redirectToRoot();
	}
	public function setUserNotLogged() {
		$_SESSION['user_is_logged'] = FALSE;
	}
}