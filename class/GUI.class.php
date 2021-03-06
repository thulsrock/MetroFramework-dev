<?php

class GUI {
		
	private $module;
	private $session;
		
	public function __construct( Session &$session, Module &$module ) {
		$this->module = &$module;
		$this->session = &$session;
	}

	public function render() {
		ob_start();
		$this->getComponent("header");		
		$this->getBody();
		$this->getComponent("footer");
		$html = ob_get_clean();		
		echo $html;
	}
	
	public function getBody() {
		try {
			$file = $this->module->getPath() . $this->module->getLandingPage();
			$this->getFile( $file );
		}  catch (Exception $e) {
			$render = new Render( $e );
			$this->fileNotFound();
		}
	}

	public function getComponent( String $module ){
		$this->getFile( COMPONENT_DIR . $module );
	}
	
	public function getFile( String $file ) {
		$filename = $file . ".php";
		if( !file_exists( $filename ) ) {
			throw new Exception( "File " . $file . " non trovato." );
		} else {
			include ( $filename );
		}
	}
	
	public function fileNotFound() {
		include CONTENT_DIR . 404 . ".php";
	}
	
	public function getLoginPage(){
		try {
			$this->getComponent('header');
			$this->getFile( CONTENT_DIR . 'login' );
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	
	public function getMaterialIconStyle() {
		return './' . STYLE_DIR . 'materialicons.css';
	}
	
	public function getStyle() {	
		return './' . STYLE_DIR . 'style.css';
	}
	
	public function getPrintStyle() {
		return './' . STYLE_DIR . 'print.css';
	}
	
	public function buttonNewItem() {
		if( $this->session->hasPrivilege( $_GET['module'] .'-new' ) ) {
			//echo "<a id='buttonNewItem' class='round_border' href='". esc( $_SERVER['PHP_SELF'] ."?module=".$_GET['module'] ) . "&action=new'>Nuovo</a>";
			$this->getFile( COMPONENT_DIR . 'button_new_item' );
		}
	}

	public function buttonToTop() {
		$this->getFile( COMPONENT_DIR . 'button_to_top' );
	}
	
	public function buttonFormSend( $module, $action = NULL ) {
		$formValue = $module;
		if( isset( $action ) && $action != NULL ) {
			$formValue .= '-' . $action;
		}
		echo "<button id='button' class='formSend bold' type='submit' name='form' value=".$formValue." >Invia</button>";
	}
	
	public function navigationMenu() {
		if( $this->session->isLogged() ) {
			$nav = new Nav( $this->session );
			$nav->render();
		}
	}
}