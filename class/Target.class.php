<?php

class Target extends Module {
	
	protected $defaultPage	= TARGET_DEFAULT_PAGE;
	protected $path			= TARGET_DIR;
	
	public function actionHandler( String $action ) {
		switch( $action ) {
			case TARGET_EDIT:
				$this->setLandingPage( $action );
				break;
			default: throw new Exception( FORBIDDEN_ACTION . ' ' . $this->getActionToPerform() );
				break;
		}
	}
		
	public function register( array $newTarget, $status ) {
		try {
			$this->validateNewTarget( $newTarget );
			$sanitizedTarget = $this->sanitizeNewTarget( $newTarget );
			$this->upload( $sanitizedTarget, $status );
		} catch (Exception $e) {
			$this->pdo->rollBack();
			echo $e->getMessage();
		}
	}
	
	public function validateNewTarget( array $newTarget ) {
		foreach ( $newTarget as $key => $value ) {
			$this->validateNewTargetField($key, $value);
		}
	}
	
	public function validateNewTargetField( $key, $value ) {
		if( $value == NULL ) throw new Exception( 'Campo ' . $key . ' nullo.' );
	}
	
	public function sanitizeNewTarget( array $newTarget ) {
		$sanitizedTarget = [];
		foreach( $newTarget as $key => $value ) {
			$sanitizedTarget[$key] = preg_replace( '/\r|\n/', ' ', $value );
		}
		return $sanitizedTarget;
	}
	
}