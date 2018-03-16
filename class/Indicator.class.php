<?php

class Indicator extends Module {
	
	protected $defaultPage	= TASK_DEFAULT_PAGE;
	protected $path			= INDICATOR_DIR;
	
	private $departmentName;
	private $departmentID;
	private $target;
	private $code;
	
	public function __construct( $target = NULL, $code = NULL ) {
		$this->target = $target ? $target : $_GET['target'];
		$this->code = $code ? $code : $_GET['code'];
	}
	
	public function actionHandler( String $action ) {
		switch( $action ) {
			case INDICATOR_OPEN:
				$indicatorDao = new IndicatorDAO();
				if( $indicatorDao->isComplete( $this->target, $this->code) ) {
					$this->setLandingPage( INDICATOR_VIEW ) ;
				} else {
					$this->setLandingPage( INDICATOR_EDIT );
				}
			break;
			case INDICATOR_EDIT:
				
				break;
			case INDICATOR_ATTACHMENT_DELETE:
				$this->fileDelete( $_GET['file'] );
				$this->setLandingPage( INDICATOR_EDIT );
				break;
			default: throw new Exception( FORBIDDEN_ACTION . ' ' . $action );
				break;
		}
	}
	
	public function register( array $newIndicator ) {
		$indicatorDao = new IndicatorDAO();
		try {
			$this->validation( $newIndicator );
			$indicatorDao->update( $newIndicator );
			$this->file_receiver( $newIndicator['target'], $newIndicator['code'] );
			if( $indicatorDao->isComplete($newIndicator['target'], $newIndicator['code'] ) ) {
				$this->buildPdfFiles( $newIndicator );
			}
		} catch (Exception $e) {
			throw new Exception( $e->getMessage() );
		}
	}

	public function validation( array $newIndicator ) {
		if( !isset( $_FILES ) ) $files = NULL;	
		if( !isset( $_FILES['file']['name']) && !is_uploaded_file( $_FILES['file']['name']['tmp_name']) ) {
			throw new Exception( 'File non allegati - validazione fallita.' );
		} else {
			return TRUE;
		}
	}
	
	public function file_receiver( String $target, String $code ) {
		$upload = TRUE;
		
		for ( $i = 0; $i < count( $_FILES['file']['name'] ); $i++ ) {
			//Get the temp file path
			$tmpFilePath = $_FILES['file']['tmp_name'][$i];
			$filename = $_FILES['file']['name'][$i];
			
			//Make sure we have a filepath
			if ( $filename!= "" ) {
				
				$taskDao = new TaskDAO();
				$department = $taskDao->getDepartmentFromTarget( $target );
				
				$departmentPath = UPLOAD_ROOT . $department->name;
				$targetPath = $departmentPath . DIRECTORY_SEPARATOR . $target;
				$taskPath = $targetPath . DIRECTORY_SEPARATOR . $code;
				$targetfile = $taskPath . DIRECTORY_SEPARATOR . $filename;
				
				$fileExt = pathinfo( $targetfile, PATHINFO_EXTENSION );
				
				if( $fileExt != 'pdf' && $fileExt != 'p7m' ) {
					throw new Exception( 'Gli allegati devono avere estensione PDF e P7M.' );
					$upload = FALSE;
				}
				
				//Setup our new file path
				
				if( !file_exists( $departmentPath ) )	mkdir( $departmentPath );
				if( !file_exists( $targetPath ) ) 		mkdir( $targetPath );
				if( !file_exists( $taskPath ) )			mkdir( $taskPath );
				
				//Upload the file into the temp dir
				if ( !move_uploaded_file( $tmpFilePath, $targetfile) ) {
					throw new Exception( ' Caricamento di <b>' . $filename . '</b> fallito.' );
					$upload = FALSE;
					return $upload;
				}
			}
		}
		return $upload;
	}
	
	public function buildPdfFiles( array $indicator ) {
		$this->buildIndicatorSheet( $indicator );
		$this->buildIndicatorReport( $indicator['target'], $indicator['code'] );
	}
	
	public function buildIndicatorSheet( array $indicator ) {
		require_once SOURCES . 'pdfSheet.php';
	}
	
	public function buildIndicatorReport( String $target, String $code ) {
		require_once SOURCES . 'pdfReport.php';
	}
	
	public function fileDelete( String $filename ) {
		$taskDao = new TaskDAO();		
		$department = $taskDao->getDepartmentFromTarget( $this->target );
		
		$filedir = UPLOAD_ROOT . $department->name . DIRECTORY_SEPARATOR . $this->target . DIRECTORY_SEPARATOR . $this->code;

		if ( ( $filename !== NULL ) && file_exists( $filedir . DIRECTORY_SEPARATOR . $filename ) ) {
			if( unlink ( $filedir . DIRECTORY_SEPARATOR . $filename ) ) {
				return TRUE;
			} else {
				throw new Exception( $filename . ' non rimosso dal server' );
			}
		}
	}
}