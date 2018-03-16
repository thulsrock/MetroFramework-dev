<?php

class Task extends Module {

	protected $defaultPage	= TASK_DEFAULT_PAGE;
	protected $path			= TASK_DIR;
	
	public function actionHandler( String $action ) {
		switch( $action ) {
			case TASK_DELETE:
				break;
			case TASK_NEW:
			case TASK_EDIT:
				$this->register( $_REQUEST, $action );
				$this->setLandingPage( $action );
				break;

			default: throw new Exception( FORBIDDEN_ACTION . ' ' . $action );
				break;
		}
	}
	
	public function register( array $element, $status ) {
		try {
			$this->validate( $element );
			$sanitizedTask = $this->sanitizeNewTask( $element );
			$this->save( $sanitizedTask, $status );
		} catch (Exception $e) {
			throw new Exception( $e->getMessage() );
		}
	}
	
	public function validate( array $element ) {
		foreach ( $element as $key => $value ) {
			$this->validateField($key, $value);
		}
	}
	
	public function validateField( $key, $value ) {
		if( $key == 'endDate' ) return;
		elseif ( $value == NULL ) throw new Exception( 'Campo ' . $key . ' nullo.' );
	}
	
	public function sanitizeNewTask( array $newTask ) {
		$sanitizedTask = [];
		foreach( $newTask as $key => $value ) {
			$sanitizedTask[$key] = preg_replace( '/\r|\n/', ' ', $value );
		}
		return $sanitizedTask;
	}
}