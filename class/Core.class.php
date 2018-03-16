<?php

class Core extends Module {
	
	protected $defaultPage	= CORE_DEFAULT_PAGE;
	protected $path			= CORE_DIR;
	
	public function actionHandler( String $action ) {
		switch( $action ) {
			case PASSWORD_CHANGE:
				$this->setLandingPage( $action );
				break;
			default: throw new Exception( FORBIDDEN_ACTION . ' ' . $action );
			break;
		}
	}
	
	public function isSystemCoreFunction( String $page ) {
		foreach( SYSTEM_CORE_FUNCIONS as $v1 ) {
			foreach ( $v1 as $v2 ) {
				if( strpos( $v2, $page ) !== FALSE ) return TRUE;
			}
		}
	}
	
	public function toLoginScreen() {
		$this->setLandingPage( LOGIN_FORM );
	}	
	
}