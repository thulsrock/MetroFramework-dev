<?php

class JobDAO extends DAO {
			
	public function getJobsFromUserID( int $userID ) {
		$query =	'SELECT uj.ID, uj.department, uj.startDate, uj.endDate
					FROM userjob uj
					JOIN user u ON uj.user = u.ID
					WHERE u.ID = ?';
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute ( [ $userID ] );
		return $stmt->fetchALL ( PDO::FETCH_OBJ );
	}
	
	public function getUserIDFromJobID( $jobID ) {
		$query = 'SELECT u.ID FROM user u JOIN userjob uj ON u.ID = uj.user WHERE uj.ID = ?';
		$stmt = $pdo->prepare ( $query );
		$stmt->execute ( [ $jobID ] );
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		return $result->ID;
	}
	public function getJobFromID( $jobID ) {
		$query =	'SELECT department, startDate, endDate
					FROM userjob
					WHERE ID = ?';
		$stmt = $pdo->prepare ( $query );
		$stmt->execute ( [ $jobID ] );
		return $stmt->fetch ( PDO::FETCH_OBJ );
	}
	public function userjob_update( array $userDetails ) {
		if( !isset( $userDetails['department'] ) ) return TRUE;
		
		$query = 	"INSERT IGNORE INTO userjob ( user, department, startDate, endDate )
					VALUES ( :userID, :department, :startDate, :endDate )";
		$stmt = $pdo->prepare ( $query );
		
		$stmt->bindValue(':userID', $userDetails['ID'], PDO::PARAM_INT);
		$stmt->bindValue(':department', $userDetails['department'], PDO::PARAM_INT);
		$stmt->bindValue(':startDate', $userDetails['startDate'], PDO::PARAM_STR);
		$stmt->bindValue(':endDate', $userDetails['endDate'], PDO::PARAM_STR);
		$stmt->execute();
		
		if( !$stmt ) {
			throw new Exception( 'Dettagli incarico utente non aggiornati.' );
		} else {
			return TRUE;
		}
	}
	public function insertFeature( $userjob, $feature ) {
		$query = "INSERT IGNORE INTO userjob_feature VALUE (:userjob, :feature )";
		$stmt = $pdo->prepare ( $query );
		$stmt->bindValue(':userjob', $userjob, PDO::PARAM_INT);
		$stmt->bindValue(':feature', $feature, PDO::PARAM_STR);
		$stmt->execute();
		
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		if( $stmt ) return TRUE;
		else return FALSE;
	}
	public function deleteFeatures( $userjob ) {
		$query = "DELETE FROM userjob_feature where userjob = ?";
		$stmt = $pdo->prepare ( $query );
		$stmt->execute( [ $userjob ] );
		
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		if( $stmt ) return TRUE;
		else return FALSE;
	}
	public function deleteUserJob() {
		$user = new User();
		if( $this->session->hasPrivilege( USER_EDIT_FEATURE ) ) {
			$user->userjobDelete( $_GET['jobID'] );
			$this->action = EDIT_PAGE;
		}
	}
	public function userjobDelete( $userjobID ) {
		$query = 	"DELETE FROM userjob WHERE ID = ?";
		$stmt = $pdo->prepare ( $query );
		$stmt->execute( [ $userjobID ] );
		
		if( !$stmt ) {
			throw new Exception( 'Dettagli incarico utente non aggiornati.' );
		} else {
			return TRUE;
		}
	}
	public function updateUserjobDetails( array $userjobDetails ) {
		$return = FALSE;
		if( $this->deleteFeatures( $userjobDetails['userjob'] ) ) {
			foreach( $userjobDetails['userjob_feature'] as $feature ) {
				if( !$this->insertFeature( $userjobDetails['userjob'], $feature) ) return FALSE;
			}
			$return = TRUE;
		}
		return $return;
	}
}