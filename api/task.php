<?php

require('../db/DB.php');
$DB = new DB();

$arrKeysToStr = implode(",", array_keys($_POST));

$action = 'add'; // по умолчанию будет создавать новую запись

// добавление или редактирование
if(isset($_POST['action'])):
	$action = $_POST['action'];
endif;

// удаление
if(isset($_GET['action'])):
	$action = $_GET['action'];
	$id = $_GET['id'];
endif;

// стандартный набор значений для сохранения
if($action === 'add' || $action === 'edit'):
	$name = $_POST['name'];
	$manager = $_POST['manager'];
	$object_ID = $_POST['object_ID'];
	$userID = $_POST['user_ID'];
	$status_ID = $_POST['status_ID'];
	$description = $_POST['description'];
	$report = $_POST['report'];
	$date_created = $_POST['date_created'];
endif;

$sqlQuery = "";
switch($action) {
	case('add'):
		$countUniqueItems = $DB -> GetCountUniqueItems('TASKS');
		$UID = ++$countUniqueItems[0]['quantity'];
		$date_last_update = $date_created;
		$sqlQuery = "INSERT INTO TASKS (UID,name,date_created,date_last_update,manager,user_ID,object_ID,status_ID,description,report) 
		VALUES ('$UID','$name','$date_created','$date_last_update','$manager','$userID','$object_ID','$status_ID','$description','$report')";
	break;
	case('edit'):
		$UID = $_POST['UID'];
		$date_last_update = date('Y-m-d H:i:s');
		$sqlQuery = "INSERT INTO TASKS (UID,name,date_created,date_last_update,manager,user_ID,object_ID,status_ID,description,report) 
		VALUES ('$UID','$name','$date_created','$date_last_update','$manager','$userID','$object_ID','$status_ID','$description','$report')";
	break;
	case('delete'):
		$sqlQuery = "DELETE FROM TASKS WHERE id='$id'";
	break;
	default:
		echo 'Указано неверное действие';
	break;
}

$data = $DB -> ExecuteQuery($sqlQuery);
echo $data ? '<html><head><meta http-equiv="Refresh" content="0; URL=../index.php?view=tasks"></head></html>' : 'Есть ошибки!';