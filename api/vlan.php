<?php
require('../db/DB.php');
$DB = new DB();

$action = 'add'; // по умолчанию создаем новую запись
$data = json_decode($_REQUEST['data']); // входящие данные

$action = $data->action;
$clientID = $data->clientID;

$sqlQuery = "";
switch($action) {
	case('add'):
		$countUniqueItems = $DB -> GetCountUniqueItems('VLAN');
		$UID = ++$countUniqueItems[0]['quantity'];
		$date_created = date('Y-m-d H:i:s');
		$date_last_update = $date_created;
		$sqlQuery = "INSERT INTO VLAN (UID,value,speed,status,date_created,date_last_update,client_ID) 
		VALUES ('$UID','$data->vlan','$data->speed',1,'$date_created','$date_last_update','$clientID')";
		$data->UID = $UID;
		$data->statusText = 'VLAN добавлен.';
	break;
	case('edit'): // отключение VLAN
		$listVlan = $DB -> GetListItems('VLAN', 'date_last_update', 'DESC', 'UID', $data->UID);
		$value = $listVlan[0]['value'];
		$speed = $listVlan[0]['speed'];
		$date_created = $listVlan[0]['date_created'];
		$date_last_update = date('Y-m-d H:i:s');
		$sqlQuery = "INSERT INTO VLAN (UID,value,speed,status,date_created,date_last_update,client_ID) 
		VALUES ('$data->UID','$value','$speed',0,'$date_created','$date_last_update','$clientID')";
		$data->statusText = 'VLAN отключен.';
	break;
	case('delete'):
		$sqlQuery = "DELETE FROM VLAN WHERE id='$id'";
	break;
	default:
		echo 'Указано неверное действие';
	break;
}

$DB -> ExecuteQuery($sqlQuery);
echo json_encode($data);