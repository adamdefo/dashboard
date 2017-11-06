<?php

session_start();
include '../db/DB.php';

class Auth extends DB {

	public $login;
	public $password;
	public $user = array();
	private $loggedIn = false;

	function __construct() {
		if(isset($_SESSION['user']) && count($_SESSION['user'])):
//			echo '<pre>';
//			print_r($_SESSION['user']);
//			echo '</pre>';
			$this->login = $_SESSION['user']['login'];
			$this->password = $_SESSION['user']['password'];
			$this->login();
		endif;
	}

	private function getUser() {
		$DB = new DB();
		$query = "SELECT * FROM USERS WHERE login='$this->login' AND password='$this->password'";
		return mysql_fetch_array($DB->ExecuteQuery($query));
	}

	public function login() {
		if(count($this->getUser())):
			$this->user = array_merge(array(), $this->getUser());
			$_SESSION['user'] = $this->user;
			$this->loggedIn = true;
		endif;
	}

	public function logout() {
		unset($_SESSION['user']);
		$this->loggedIn = false;
	}

	public function isLoggedIn() {
		return $this->loggedIn;
	}

}