<?php

class DAO {
	
	protected $pdo;
	
	public function __construct () {
		$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
		$dbopt = array (
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES => false,
				PDO::ATTR_PERSISTENT => true,
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET,
		);
		
		try {
			$this->pdo = new PDO( $dsn, DB_USER, DB_PASS, $dbopt );
		} catch (PDOException $e) {
			echo "Connection error " . $e->getMessage() . "\n";
			exit;
		}
	}
	
	public function getPdo() {
		return $this->pdo;
	}
	
	protected function queryObjList( String $query, String $filter = NULL ) {
		$stmt = $this->pdo->prepare ( $query );
		if( isset( $filter ) ) {		
			$stmt->execute( [ $filter ] );
		} else {
			$stmt->execute();
		}
		$result = $stmt->fetchAll ( PDO::FETCH_OBJ );
		if( $stmt->rowCount() > 0 ) return $result;
		else return FALSE;
	}
	
	public function save( array $vo, String $status ) {
		if( $status == NEW_VO ) {
			$this->insert($vo);
		} else {
			$this->edit($vo);
		}
	}
	/*
	public function save( &$vo ) {
		try {
			$module = get_class($vo) . 'DAO';
			$moduleDAO = new $moduleDAO();
			if( $this->vo->ID == 0 ) {
				$status = NEW_VO;
			} else {
				$status = EDIT_VO;
			}
			$moduleDAO->upload( $vo, $status );
		} catch (Exception $e) {
			$Render = new Render( $e );
		}
		
	}
	
	private function insert( array $vo ) {
		
	}
	
	private function edit( array $vo ) {
		
	}
	
	private function delete( array $vo ) {
		
	}
	*/
}
