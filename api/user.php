<?php

require('../db/DB.php');
$DB = new DB();

$arrKeysToStr = implode(",", array_keys($_POST));

$action = 'add'; // по умолчанию будет создавать новую запись
$id = $_GET['id'];

// добавление или редактирование
if(isset($_POST['action'])):
	$action = $_POST['action'];
endif;

// удаление
if(isset($_GET['action'])):
	$action = $_GET['action'];
endif;

// стандартный набор значений для сохранения
if($action === 'add' || $action === 'edit'):
	$fio = $_POST['fio'];
	$phone = $_POST['phone'];
	$login = $_POST['login'];
	$password = $_POST['password'];
	$roleID = $_POST['role_ID'];
	$date_created = $_POST['date_created'];
	$isActive = is_null($_POST['is_active']) ? 0 : $_POST['is_active'];
endif;

$sqlQuery = "";
switch($action) {
	case('add'):
		$countUniqueItems = $DB -> GetCountUniqueItems('USERS');
		$UID = ++$countUniqueItems[0]['quantity'];
		$date_last_update = $date_created;
		$sqlQuery = "INSERT INTO USERS (UID,fio,phone,login,password,role_ID,date_created,is_active) 
		VALUES ('$UID','$fio','$phone','$login','$password','$role_ID','$date_created','$isActive')";
	break;
	case('edit'):
		$UID = $_POST['UID'];
		$date_last_update = date('Y-m-d H:i:s');
		$sqlQuery = "UPDATE USERS SET fio='$fio',phone='$phone',login='$login',password='$password',role_ID='$roleID',is_active='$isActive' WHERE id='$id'";
	break;
	case('delete'):
		$sqlQuery = "DELETE FROM USERS WHERE id='$id'";
	break;
	default:
		echo 'Указано неверное действие';
	break;
}

$data = $DB -> ExecuteQuery($sqlQuery);
echo $data ? '<html><head><meta http-equiv="Refresh" content="0; URL=../index.php?view=users"></head></html>' : 'fail';