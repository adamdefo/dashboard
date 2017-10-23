<?php

require('../db/DB.php');
$DB = new DB();

$action = 'add'; // по умолчанию создаем новую запись
$data = json_decode($_REQUEST['data']); // входящие данные

// добавление или редактирование
// if(isset($_POST['action'])):
// 	$action = $_POST['action'];
// endif;

// удаление
// if(isset($_GET['action'])):
// 	$action = $_GET['action'];
// 	$id = $_GET['id'];
// endif;

// стандартный набор значений для сохранения
// if($action === 'add' || $action === 'edit'):
// 	$value = $_POST['value'];
// 	$speed = $_POST['speed'];
// 	$clientID = $_POST['client_ID'];
// 	$date_created = $_POST['date_created'];
// 	$status = is_null($_POST['status']) ? 0 : $_POST['status'];
// endif;

$sqlQuery = "";
switch($action) {
	case('add'):
		$countUniqueItems = $DB -> GetCountUniqueItems('VLAN');
		$UID = ++$countUniqueItems[0]['quantity'];
		$date_created = date('Y-m-d H:i:s');
		$date_last_update = $date_created;
		$sqlQuery = "INSERT INTO VLAN (UID,value,speed,status,date_created,date_last_update,client_ID) 
		VALUES ('$UID','$data->vlan','$data->speed',1,'$date_created','$date_last_update','$data->UID')";
	break;
	case('edit'):
		$UID = $_POST['UID'];
		$date_last_update = date('Y-m-d H:i:s');
		$sqlQuery = "INSERT INTO VLAN (UID,value,speed,status,date_created,date_last_update,client_ID) 
		VALUES ('$UID','$value','$speed','$status','$date_created','$date_last_update','$clientID')";
	break;
	case('delete'):
		$sqlQuery = "DELETE FROM VLAN WHERE id='$id'";
	break;
	default:
		echo 'Указано неверное действие';
	break;
}

$DB -> ExecuteQuery($sqlQuery);
echo json_encode($data->statusText = 'VLAN добавлен.');
//echo $data ? '<html><head><meta http-equiv="Refresh" content="0; URL=../index.php?view=vlan"></head></html>' : 'Есть ошибки!';