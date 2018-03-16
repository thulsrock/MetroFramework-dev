<?php

class TargetDAO extends DAO {
	
	public function getList( $departments = NULL ) {
		$query = '';
		if( !is_array( $departments ) ) $departments = array( $departments );
		for( $i = 0; $i < count( $departments ); $i++ ) {
			$query .=	"SELECT t.code, t.department as IDDepartment, t.name, t.description, t.weight, t.startDate, t.endDate, d.name as department
						FROM target t
						JOIN department d ON t.department = d.ID";
			if( isset( $departments ) && $departments[$i] != NULL ) $query .= " WHERE t.department = $departments[$i] ";
			if( count( $departments ) > 1 && $i < count( $departments ) -1 ) {
				$query .= ' UNION ';
			}
		}
		$query .= " ORDER BY t.code, d.name ASC ";
		return parent::queryObjList($query);
	}
	
	public function save( array $target, String $status ) {
		if( $status == NEW_VO ) {
			$query = 'INSERT INTO target VALUES ( :code, :department, :name, :description, :weight, :startDate, :endDate )';
		} else {
			$query = 'UPDATE target SET department=:department, name=:name, description=:description, weight=:weight, startDate=:startDate, endDate=:endDate WHERE code=:code';
		}

		$stmt = $this->pdo->prepare ( $query );	
		$stmt->bindParam(':code', $target['code'], PDO::PARAM_STR);
		$stmt->bindParam(':department', $target['department'], PDO::PARAM_INT);
		$stmt->bindParam(':name', $target['name'], PDO::PARAM_STR);
		$stmt->bindParam(':description', $target['description'], PDO::PARAM_STR);
		$stmt->bindParam(':weight', $target['weight'], PDO::PARAM_INT);	
		$stmt->bindParam(':startDate', $target['startDate'], PDO::PARAM_STR);
		$stmt->bindParam(':endDate', $target['endDate'], PDO::PARAM_STR);
		
		try {
			$stmt->execute();
			$noticeRender = new NoticeRender( "Obiettivo registrato.");
		} catch ( Exception $e ) {
			$this->pdo->rollBack();
			echo $e->getMessage();
			throw new Exception('Registrazione Obiettivo fallita.');
		}
	}
	
	public function getTargetDetailsFromCode( $code ) {
		try {
			$query =	"SELECT *
						FROM target
						WHERE code = :code";
			$stmt = $this->pdo->prepare ( $query );
			$stmt->bindParam(':code', $code, PDO::PARAM_STR);
			$stmt->execute();
			
			$result = $stmt->fetch( PDO::FETCH_OBJ );
			
			$result->staff = $this->getStaff($target, $code);
		} catch (Exception $e) {
			echo 'Dettagli non trovati.';
		} finally {
			return $result;
		}
	}
	public function getDepartmentFromTarget( String $code ) {
		$query =	"SELECT DISTINCT d.ID as ID, d.name as name
					FROM target as t
					JOIN department as d ON t.department = d.ID
					WHERE t.code = ?";
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute ( [ $code] );
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		
		if ( $stmt->rowCount() == 1 ) return $result;
		else return FALSE;
	}
}