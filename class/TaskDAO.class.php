<?php

class TaskDAO extends DAO {
		
	public function getList( $departments = NULL, $target = NULL ) {
		$query = '';
		if( !is_array( $departments ) ) $departments = array( $departments );
		for( $i = 0; $i < count( $departments ); $i++ ) {
			$query .=	"SELECT t.target, t.code, t.description, tg.department, t.startDate, t.endDate, t.attendedResult, t.actualResult, t.progression, t.difficulty
					FROM task t
					JOIN target tg ON t.target = tg.code
					JOIN department d ON tg.department = d.ID";
			if( isset( $departments ) && $departments[$i] != NULL ) $query .= " WHERE tg.department = $departments[$i] ";
			if( isset( $target ) ) $query .= " AND tg.code = ? ";
			if( count( $departments ) > 1 && $i < count( $departments ) -1 ) {
				$query .= ' UNION ';
			}
		}
		
		$query .= " ORDER BY t.target ASC";
		return parent::queryObjList( $query, $target );
	}
	
	public function save( array $task, String $status ) {
		if( $status == NEW_VO ) {
			$query = 'INSERT INTO task ( target, code, name, description, startDate, endDate, attendedResult )
								VALUES ( :target, :code, :name, :description, :startDate, :endDate, :attendedResult )';
		} else {
			$query = 'UPDATE task SET code=:code, name=:name, description=:description, startDate=:startDate, endDate=:endDate, attendedResult=:attendedResult
					WHERE target=:target AND code=:newCode';
		}
		
		$stmt = $this->pdo->prepare ( $query );
		
		if( $status != 'new' ) {
			$stmt->bindParam(':newCode', $task['newCode'], PDO::PARAM_STR);
		}
		
		$stmt->bindParam(':target', $task['target'], PDO::PARAM_STR);
		$stmt->bindParam(':code', $task['code'], PDO::PARAM_STR);
		$stmt->bindParam(':name', $task['name'], PDO::PARAM_STR);
		$stmt->bindParam(':description', $task['description'], PDO::PARAM_STR);
		$stmt->bindParam(':startDate', $task['startDate'], PDO::PARAM_STR);
		$stmt->bindParam(':endDate', $task['endDate'], PDO::PARAM_STR);
		$stmt->bindParam(':attendedResult', $task['attendedResult'], PDO::PARAM_INT);
		
		try {
			$stmt->execute();
			$noticeRender = new NoticeRender( 'Attività ' . $task['target'] . $task['code'] . ' registrata.' );
		} catch ( Exception $e ) {
			echo $e->getMessage();
			throw new Exception('Caricamento attività fallito.');
		}
	}
	
	public function getTaskDetail( $target, $code ) {
		$query =	"SELECT *
					FROM task
					WHERE target = :target AND code = :code";
		$stmt = $this->pdo->prepare ( $query );
		$stmt->bindParam(':target', $target, PDO::PARAM_STR);
		$stmt->bindParam(':code', $code, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch( PDO::FETCH_OBJ );
		return $result;
	}
	
	public function getAllDetails( $target, $code ) {
		$task = $this->getTaskDetail($target, $code);
		$task->staff = $this->getStaff($target, $code);
		$task->department = $this->getDepartmentFromTarget($target);
		return $task;
	}
	
	public function getDepartmentFromTarget($target) {
		$targetDao = new TargetDAO();
		return $targetDao->getDepartmentFromTarget($target);
	}
	
	public function getStaff( $target, $code ) {
		$query =	"SELECT DISTINCT user
					FROM userjob_task as ujt
					WHERE target = :target AND code = :code";
		$stmt = $this->pdo->prepare ( $query );
		$stmt->bindParam(':target', $target, PDO::PARAM_STR);
		$stmt->bindParam(':code', $code, PDO::PARAM_STR);
		$stmt->execute();
		
		$result = $stmt->fetchAll( PDO::FETCH_OBJ );
		if ( $stmt->rowCount() >= 1 ) {
			return $result;
		} else return FALSE;
	}

}