<?php

class User {
	
	public function __construct() {
	}
	
	public function getJobsAndFeaturesFromUserID( int $userID ) {
		try { 
			$jobManager = new JobDAO();
			$tmpJobs = $jobManager->getJobsFromUserID( $userID );
			
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
	
	public function passwordValidation( $username, $oldPassword, $newPassword, $confirmNewPassword ) {
		
		/*
		 $regex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[^a-zA-Z0-9]).{8,16}$/m';
		 
		 (?=.*\d) Atleast a digit
		 (?=.*[a-z]) Atleast a lower case letter
		 (?=.*[A-Z]) Atleast an upper case letter
		 (?!.* ) no space
		 (?=.*[^a-zA-Z0-9]) at least a character except a-zA-Z0-9
		 .{8,16} between 8 to 16 characters
		 
		 */
		if( $newPassword != $confirmNewPassword ) {
			throw new Exception('PASSWORDS_NOT_EQUALS');
		}
		if( strlen( $newPassword ) < PASSWORD_MIN_LENGTH || strlen( $newPassword ) > PASSWORD_MAX_LENGTH ) {
			throw new Exception(PASSWORD_WRONG_SIZE);
		}
		if( !preg_match("/[A-Z]/", $newPassword ) ) {
			throw new Exception(PASSWORD_NEEDS_UPPERCASE);
		}
		if( !preg_match("/[a-z]/", $newPassword ) ) {
			throw new Exception(PASSWORD_NEEDS_LOWERCASE);
		}
		if ( !preg_match("/\d/", $newPassword ) ) {
			throw new Exception(PASSWORD_NEEDS_NUMBER);
		}
		if ( !preg_match("/[^a-zA-Z0-9]/", $newPassword ) ) {
			throw new Exception(PASSWORD_NEEDS_SPECIAL_CHAR);
		}
		return TRUE;
	}
	
	public function registerUserInit( array $newUser ) {
		if( $this->verifyAttributes( $newUser ) == TRUE ) {
			if( $this->dao->registerUserDetails() ) {
				return TRUE;
			}
		} else return FALSE;
	}
	
	public function verifyAttributes( array $newUser ) {
		$userKeys = $this->dao->getUserAttributes();
		
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
	
	public function fieldIsEmpty( $userValue ) {
		if( !isset( $userValue ) || $userValue == '' ) return TRUE;
		else return FALSE;
	}
	
	public function fieldIsNotValid( $userValue ) {
		return FALSE;
	}
	
	public function update( array $userDetails ) {
		$return = FALSE;
		if( ( $userDetails['startDate'] != NULL || $userDetails['endDate'] != NULL ) && $userDetails['department'] != NULL ) {
			if( $this->dao->userjob_update( $userDetails ) ) {
				$return = TRUE;
			}
		}
		if( $this->user_update( $userDetails ) ) {
			$return = TRUE;
		}
		return $return;
	}
	
	public function updateUserjobDetails( array $userjobDetails ) {
		$return = FALSE;
		if( $this->dao->deleteFeatures( $userjobDetails['userjob'] ) ) {
			foreach( $userjobDetails['userjob_feature'] as $feature ) {
				if( !$this->dao->insertFeature( $userjobDetails['userjob'], $feature) ) return FALSE;
			}
			$return = TRUE;
		}
		return $return;
	}
	
	public function deleteUserJob() {
		$user = new User();
		if( $this->session->hasPrivilege( USER_EDIT_FEATURE ) ) {
			$user->userjobDelete( $_GET['jobID'] );
			$this->action = EDIT_PAGE;
		}
	}
	
	public function deleteUser() {
		$user = new User();
		if( $this->session->hasPrivilege( USER_EDIT ) ) {
			$user->userDelete( $_GET['user'] );
			$this->action = USER_INDEX;
		}
	}
	
}