<?php

class Form {
	
	private $module;
	private $action;
	private $formName;
	
	public function __construct() {
		
		$this->formName = $_POST['form'];
		$form = explode('-', $_POST['form']);

		$this->module = $form[0];
		if ( isset( $form[1] ) ) {
			$this->action = $form[1];
		}
	}
	
	public function formManager() {
		try {
			$this->formRequestHandler();
			unset( $_REQUEST );
		} catch (Exception $e) {
			throw new Exception( $e->getMessage() );
		}
	}
	
	public function formRequestHandler() {
		switch( $this->formName ) {
			case TARGET_NEW:
			case TARGET_EDIT:
			case TASK_NEW:
			case TASK_EDIT:
				$module = $this->module;
				$this->module = new $module();
				$this->module->register( $_REQUEST, $this->action );
				if( strpos( $this->formName, EDIT_PAGE ) ) $this->module->setLandingPage( $this->module->getDefaultPage() ); 
				break;
			case INDICATOR_EDIT:
				$indicator = new Indicator();
				$indicator->register( $_REQUEST );
				$this->module = new Task();
				break;
			case USER_NEW:
				$userDAO = new UserDAO();
				$userDAO->registerUserInit( $_REQUEST );
				break;
			case USER_EDIT:
				$userDAO = new UserDAO();
				$userDAO->update( $_REQUEST );
				$this->module = new User();
				break;
			case USER_EDIT_FEATURE:
				$jobDAO = new JobDAO();
				$jobDAO->updateUserjobDetails( $_REQUEST );
				$this->module = new User();
				break;
			case PASSWORD_CHANGE:
				$userAccount = new Account();
				$userAccount->passwordChange();
				$this->module = new Core();
				break;
			default:
				$this->module = new Core();
				throw new Exception( 'Default per funzione ' . __FUNCTION__ );
				break;
		}
	}
	
	public function getModule() {
		return $this->module;
	}
	
	public function getAction() {
		return $this->action;
	}
}