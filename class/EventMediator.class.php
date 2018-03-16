<?php

class EventMediator {
	
	private static $instance = NULL;
	private $session;
	private $module;
	
	private function __construct() {
		$this->session = Session::getInstance();
	}
	
	public static function getInstance() {
		if( self::$instance == NULL ) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}
		
	public function main() {
		$this->requestHandler();
		$GUI = new GUI( $this->session, $this->module );
		$GUI->render();
	}
	
	public function requestHandler() {
		$this->module = new Core();
		if ( $this->session->isNotLogged() && $this->isLoginFormSubmitted() ) {
			$this->login();		
		} elseif( $this->isLogoutSubmitted() ) {
			$this->session->logout();	
		} elseif ( $this->session->isNotLogged() ) {
			$this->module->toLoginScreen();
		} else {
			$this->moduleSelector();
		}
	}
	
	public function isLoginFormSubmitted() {
		return $_SERVER['REQUEST_METHOD'] == 'POST' && $_REQUEST['form'] == LOGIN_FORM;
	}
	
	public function login() {
		try {
			$login = new Login( $_REQUEST['username'], $_REQUEST['password'] );
			$user = $login->getLoggedUser();
			$this->session->updateAfterLogin( $user );
			$this->module->toDefaultPage();
		} catch ( Exception $e ) {
			$render = new Render( $e );
			$this->module->toLoginScreen();
			return FALSE;
		}
	}
	
	public function isLogoutSubmitted() {
		if( isset( $_REQUEST['logout'] ) && $_REQUEST['logout'] == TRUE ) {
			return TRUE;
		}
	}
	
	public function isModuleSubmitted() {
		return isset( $_GET['module'] );
	}
	
	public function moduleSelector() {
		try {
			if( $this->isModuleSubmitted() ) {
				$module = $this->getModule();
				$this->module = new $module();
			}
			$this->module->init( $this->session );			
		} catch (Exception $e) {
			$Render = new Render( $e );
		}
	}
	
	public function getModule() {
		return $_GET['module'];
	}
}