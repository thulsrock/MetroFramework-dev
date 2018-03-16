<?php

class UserVO {
	
	private $ID;
	private $surname;
	private $name;
	private $username;
	private $password;
	private $email;
	private $SSN;
	private $serialNumber;
	private $disabled;
	
	public function __construct( $args ) {
		$this->ID			= $args->ID;
		$this->surname		= $args->surname;
		$this->name			= $args->name;
		$this->username		= $args->username;
		$this->password		= $args->password;
		$this->email		= $args->email;
		$this->ssn			= $args->SSN;
		$this->serialNumber	= $args->serialNumber;
		$this->disabled		= $args->disabled;
	}
	
	public function getID() {
		return $this->ID;
	}
	public function getSurname() {
		return $this->surname;
	}
	public function getName() {
		return $this->name;
	}
	public function getUsername() {
		return $this->username;
	}
	public function getEmail() {
		return $this->email;
	}
	public function getSsn() {
		return $this->SSN;
	}
	public function getSerialNumber() {
		return $this->serialNumber;
	}
	public function isDisabled() {
		return $this->disabled == 1;
	}
	public function isActive() {
		return !$this->isDisabled();
	}
}