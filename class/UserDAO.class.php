<?php

class UserDAO extends DAO {
	
	private function createUser( $args ){
		return new UserVO( $args );
	}
		
	public function userExists( String $username ) {
		$stmt = $this->pdo->prepare ( "SELECT count(*) as count FROM user WHERE username = ?" );
		$stmt->execute ( [$username] );
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		if( $result->count != 1 ) {
			throw new Exception( USER_NOT_FOUND );
		}
	}

	public function getUserFromUsername( String $username ) {
		$query = "SELECT * FROM user WHERE username = ?";
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute ( [ $username ] );
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		return $this->createUser( $result );
	}
	
	public function getUsernameFromUserID( int $userID ) {
		$query = "SELECT username FROM user WHERE ID = ?";
		$stmt = $this->pdo->prepare ( $query );
		$stmt->execute ( [ $userID ] );
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		return $result->username;
	}

	public function getUserFromUserID( int $userID ) {
		$username = $this->getUsernameFromUserID($userID);
		return $this->getUserFromUsername($username);
	}

	public function getList() {
		$query = "SELECT * FROM user ORDER BY surname ASC";
		return parent::queryObjList($query);
	}

	
	
	
	
	
	
	public function hasAccessPrivilege( $module ) {
		if( !$this->isSystemCoreFunction( $module ) && !$this->session->hasPrivilege( $module ) ) {
			throw new NoPrivilegeException( 'Non si dispone dei privilegi necessari per accedere al modulo ' . $module );
		}
	}

	public function insert( $user ) {
		$encryptedPassword = password_hash( $this->user['password'], PASSWORD_BCRYPT, array( 'cost' => 13 ) );
		
		$query =	'INSERT INTO user ( SSN, username, password, serialNumber, name, surname, email )
					VALUES ( :ssn, :username, :password, :serialNumber, :name, :surname, :email )';
		
		$stmt = $pdo->prepare ( $query );
		
		$stmt->bindParam(':ssn', $this->user['ssn'], PDO::PARAM_STR);
		$stmt->bindParam(':username', $this->user['username'], PDO::PARAM_STR);
		$stmt->bindParam(':password', $encryptedPassword, PDO::PARAM_STR);
		$stmt->bindParam(':serialNumber', $this->user['serialNumber'], PDO::PARAM_INT);
		$stmt->bindParam(':name', $this->user['name'], PDO::PARAM_STR);
		$stmt->bindParam(':surname', $this->user['surname'], PDO::PARAM_STR);
		$stmt->bindParam(':email', $this->user['email'], PDO::PARAM_STR);
		$stmt->execute();

		if( !$stmt ) {
			throw new Exception( 'Registrazione utente fallita.' );
		} else {
			$noticeRender = new NoticeRender( "Attività " . $element['target'] . $element['code'] . " registrata." );
			return TRUE;
		}
	}
	
	public function update( $user ) {
		$query =	"UPDATE user
					SET username = :username,
						name = :name,
						surname = :surname,
						email = :email,
						SSN = :SSN,
						serialNumber = :serialNumber,
						disabled = :disabled
	
					WHERE ID = :ID";
		
		$stmt = $pdo->prepare ( $query );
		
		$stmt->bindValue(':username', $userDetails['username'], PDO::PARAM_STR);
		$stmt->bindValue(':name', $userDetails['name'], PDO::PARAM_STR);
		$stmt->bindValue(':surname', $userDetails['surname'], PDO::PARAM_STR);
		$stmt->bindValue(':email', $userDetails['email'], PDO::PARAM_STR);
		$stmt->bindValue(':SSN', $userDetails['SSN'], PDO::PARAM_INT);
		$stmt->bindValue(':serialNumber', $userDetails['serialNumber'], PDO::PARAM_INT);
		$stmt->bindValue(':disabled', $userDetails['disabled'], PDO::PARAM_INT);
		$stmt->bindValue(':ID', $userDetails['ID'], PDO::PARAM_INT);
		$stmt->execute();
		
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		if( !$stmt ) {
			throw new Exception( 'Dettagli utente non aggiornati.' );
		} else {
			return TRUE;
		}
	}
	/*
	public function update( array $userDetails ) {
		$return = FALSE;
		if( ( $userDetails['startDate'] != NULL || $userDetails['endDate'] != NULL ) && $userDetails['department'] != NULL ) {
			if( $this->userjob_update( $userDetails ) ) {
				$return = TRUE;
			}
		}
		if( $this->user_update( $userDetails ) ) {
			$return = TRUE;
		}
		return $return;
	}
	*/

	public function getUserIDFromUsername( String $username ) {
		$stmt = $this->pdo->prepare ( "SELECT ID FROM user WHERE username = ?" );
		$stmt->execute ( [$username] );
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		return $result->ID;
	}

	public function userDelete( int $userID ) {
		$stmt = $pdo->prepare ( "DELETE FROM user WHERE ID = ?" );
		$stmt->execute ( [$userID] );
		if( ! $stmt ) {
			throw new Exception('Utente non cancellato.');
		}
	}
	
	public function getJobsAndFeaturesFromUserID( int $userID ) {
		try {
			$jobDAO = new JobDAO();
			$tmpJobs = $jobDAO->getJobsFromUserID( $userID );
			
			$jobs = array();
			
			foreach( $tmpJobs as $tmpJob ) {
				$jobs[$tmpJob->ID] = new stdClass();
				
				$jobs[$tmpJob->ID]->department = $tmpJob->department;
				$jobs[$tmpJob->ID]->startDate = $tmpJob->startDate;
				$jobs[$tmpJob->ID]->endDate = $tmpJob->endDate;
				
				$featureManager = new FeatureDAO();
				$jobsFeature = $featureManager->getFeatureFromJobID( $tmpJob->ID );
				
				try {
					foreach( $jobsFeature as $feature ) {
						$jobs[$tmpJob->ID]->features[] = $feature->code;
					}
				} catch (Exception $e) {
					return NULL;
				}
			}
		} catch (Exception $e) {
			return NULL;
		}
		return $jobs;
	}
	
	public function verifyAttributes( array $newUser ) {
		$userKeys = $this->getUserAttributes();
		
		if ( $this->verifyInputFormAttributes( $userKeys, $newUser ) ) {
			$this->user = $newUser;
			return TRUE;
		} else return FALSE;
	}
	
	public function verifyInputFormAttributes( array $userKeys, array $newUser ) {
		/**
		 * Matches the user input form's fields with the attributes retrieved from the DB
		 */
		/*
		 foreach ( $userKeys as $value ) {
		 //		if( $value == 'username' || $value == 'email' || $value == 'SSN' || $value == 'serialNumber' ) continue;
		 if( !array_key_exists( $value, $newUser ) ) {
		 $_SESSION['error'][] = "Campo " . $value. " nullo";
		 $return = FALSE;
		 }
		 if( $this->fieldIsEmpty( $newUser[$value] ) ) {
		 $_SESSION['error'][] = "Campo " . $value. " non compilato";
		 $return = FALSE;
		 }
		 }*/
		//return $return;
		return TRUE;
	}
	
	public function deleteUser() {
		$user = new User();
		if( $this->session->hasPrivilege( USER_EDIT ) ) {
			$user->userDelete( $_GET['user'] );
			$this->action = USER_INDEX;
		}
	}
	public function registerUserInit( array $newUser ) {
		if( $this->verifyAttributes( $newUser ) == TRUE ) {
			if( $this->registerUserDetails() ) {
				return TRUE;
			}
		} else return FALSE;
	}
}