<?php

class User extends Module {
	
	protected $defaultPage	= USER_DEFAULT_PAGE;
	protected $path			= USER_DIR;
	
	public function actionHandler( String $action ) {
		switch( $action ) {
			case USER_DELETE:
				$this->toDefaultPage();
				break;
			case USER_NEW:
			case USER_EDIT:
			case USER_EDIT_FEATURE:
				$this->setLandingPage( $action );
				break;
			default: throw new Exception( FORBIDDEN_ACTION . ' ' . $action );
				break;
		}
	}
	
}