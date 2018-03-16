<?php

class Account {
	
	private $pdo;
	
	public function __construct() {
		$dao = new Dao();
		$this->pdo = $dao->getPdo();
	}
	
	public function verifyPassword( String $username, String $password ) {
		$stmt = $this->pdo->prepare ( "SELECT password FROM user WHERE username = ?" );
		$stmt->execute ( [$username] );
		$result = $stmt->fetch ( PDO::FETCH_OBJ );
		if ( !password_verify( $password, $result->password ) ) throw new Exception( PASSWORD_IS_WRONG );
		// To register the first user, print the password than copy/paste to the DB
		/*
		 $password = password_hash($post['password'], PASSWORD_BCRYPT, array('cost' => 13));
		 echo $password;
		 die();
		 */
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
			throw new Exception('PASSWORDS_ARE_NOT_EQUAL');
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
	
	public function passwordChange() {
		$username = isset( $_REQUEST['username'] ) ? $_REQUEST['username'] : $_SESSION['username'];
		$oldPassword = $_REQUEST['oldPassword'];
		$newPassword = $_REQUEST['newPassword'];
		$confirmNewPassword = $_REQUEST['confirmNewPassword'];
		
		try {
			$this->verifyPassword( $username, $oldPassword );
			!$this->passwordValidation($username, $oldPassword, $newPassword, $confirmNewPassword);
		} catch (Exception $e) {
			echo $e->getMessage();
			return FALSE;
		}
		
		$password = password_hash( $_REQUEST['newPassword'], PASSWORD_BCRYPT, array('cost' => 13) );
		$query = 'UPDATE user SET password = :password WHERE username = :username';
		
		$stmt = $pdo->prepare ( $query );
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->bindParam(':password', $password, PDO::PARAM_STR);
		$stmt->execute();
		
		if( !$stmt ) {
			throw new Exception(PASSWORD_UPDATE_FAIL);
			return FALSE;
		} else {
			throw new Notice(PASSWORD_UPDATE_SUCCESS);
			return TRUE;
		}
	}


}