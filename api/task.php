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
	$status_ID = $_POST['status_ID'];
	$description = $_POST['description'];
	$report = $_POST['report'];
	$date_created = $_POST['date_created'];
endif;

$sqlQuery = "";
switch($action) {
	case('add'):
		$countUniqueItems = $DB -> GetCountUniqueItems('TASKS','value');
		$value = ++$countUniqueItems[0]['quantity'];
		$date_last_update = NULL;
		$sqlQuery = "INSERT INTO TASKS (value,name,date_created,date_last_update,manager,object_ID,status_ID,description,report) 
		VALUES ('$value','$name','$date_created','$date_last_update','$manager','$object_ID','$status_ID','$description','$report')";
	break;
	case('edit'):
		$value = $_POST['value'];
		$date_last_update = date('Y-m-d H:i:s');
		$sqlQuery = "INSERT INTO TASKS (value,name,date_created,date_last_update,manager,object_ID,status_ID,description,report) 
		VALUES ('$value','$name','$date_created','$date_last_update','$manager','$object_ID','$status_ID','$description','$report')";
	break;
	case('delete'):
		$sqlQuery = "DELETE FROM TASKS WHERE id='$id'";
	break;
	default:
		echo 'Указано неверное действие';
	break;
}

$data = mysql_query($sqlQuery, $DB -> CreateConnect());
echo $data ? '<html><head><meta http-equiv="Refresh" content="0; URL=../index.php?view=tasks"></head></html>' : 'fail';