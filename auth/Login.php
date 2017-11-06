<?php

session_start();
require('Auth.php');
$Auth = new Auth();

if(!isset($_SESSION['user']) && !count($_SESSION['user'])):
	$Auth->login = $_POST['login'];
	$Auth->password = $_POST['password'];
	$Auth->login();

	if($Auth->isLoggedIn()):
		echo '<html><head><meta http-equiv="Refresh" content="0; URL=../index.php"></head></html>';
	endif;
endif;