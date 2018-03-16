<?php

class Module {

	protected	$defaultPage;
	private		$landingPage;
	protected	$path;
		
	public function init( Session $session ) {
		if( $this->formOrActionSubmitted() ) {
			$action = $this->getFormOrAction();
			$this->checkPermission( $session, $action );
			$this->actionHandler( $action );
		} else {
			$this->toDefaultPage();
		}
	}
	
	public function formOrActionSubmitted() {
		return ( $this->isActionSubmitted() || $this->isFormSubmitted() );
	}
	
	private function isActionSubmitted() {
		return isset( $_GET['action'] );
	}
	
	private function isFormSubmitted() {
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}
	
	public function getFormOrAction() {
		if( $this->isFormSubmitted() ) {
			$action = $this->getSubmittedForm();
		} else {
			$action = $this->getSubmittedAction();
		}
		return $action;
	}
		
	private function getSubmittedForm() {
		return $_POST['form'];
	}
	
	private function getSubmittedAction() {
		return $_GET['action'];
	}
	
	private function checkPermission( Session $session, String $action ) {
		try {
			$session->hasPrivilege( $action );
		} catch (Exception $e) {
			throw new Exception( FORBIDDEN_ACTION . ' ' . $action );
		}
	}
	
	public function actionHandler( String $action ) {}
	
	public function toDefaultPage() {
		$this->setLandingPage( $this->getDefaultPage() );
	}
	
	public function getDefaultPage() {
		return $this->defaultPage;
	}
	
	public function getLandingPage() {
		return $this->landingPage;
	}
	
	public function setLandingPage( String $landingPage ) {
		$this->landingPage = $landingPage;
	}
	
	public function getPath() {
		return $this->path;
	}

}