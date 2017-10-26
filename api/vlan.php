<?php
require('../db/DB.php');
$DB = new DB();

$data = json_decode($_REQUEST['data']); // входящие данные

$error = 0;
$action = 'add'; // по умолчанию создаем новую запись
$insert = "INSERT INTO VLAN (UID,value,speed,status,date_created,date_last_update,client_ID)";

$action = $data->action;
$clientID = $data->clientID;
$date_created = date('Y-m-d H:i:s');
$data->isExist = false; // флаг, по которому узнаем вставлять в таблицу или нет
$update = true;

$sqlQuery = "";
switch($action) {
	case('add'):
		$listVlan = $DB -> GetListItems('VLAN','date_last_update','DESC','value',$data->vlan);
		$countUniqueItems = $DB -> GetCountUniqueItems('VLAN');
		// проверка, есть ли такой VLAN
		if(count($listVlan)): // ecли есть
			if(!$listVlan[0]['status']):
				$data->UID = $listVlan[0]['UID'];
				$date_created = $listVlan[0]['date_created'];
				$date_last_update = date('Y-m-d H:i:s');
				$sqlQuery = $insert." VALUES ('$data->UID','$data->vlan','$data->speed',1,'$date_created','$date_last_update','$clientID')";
				$data->statusText = 'VLAN привязан к клиенту '.$clientID;
			else: // иначе выведем сообщение у какого клиента этот VLAN
				$update = false;
				$data->isExist = true;
				$data->statusText = 'Такой VLAN есть у клиента '.$listVlan[0]['client_ID'];
			endif;
		else: // иначе создаем новый VLAN
			$UID = ++$countUniqueItems[0]['quantity'];
			$date_last_update = $date_created;
			$sqlQuery = $insert." VALUES ('$UID','$data->vlan','$data->speed',1,'$date_created','$date_last_update','$clientID')";
			$data->statusText = 'VLAN добавлен.';
		endif;
	break;
	case('edit'): // отключение VLAN
		$listVlan = $DB -> GetListItems('VLAN','date_last_update','DESC','UID',$data->UID);
		$value = $listVlan[0]['value'];
		$speed = $listVlan[0]['speed'];
		$date_created = $listVlan[0]['date_created'];
		$date_last_update = date('Y-m-d H:i:s');
		$sqlQuery = $insert." VALUES ('$data->UID','$value','$speed',0,'$date_created','$date_last_update','$clientID')";
		$data->statusText = 'VLAN отключен.';
	break;
	case('delete'):
		$update = false;
		$sqlQuery = "DELETE FROM VLAN WHERE id='$id'";
		$DB -> ExecuteQuery($sqlQuery);
		$data->statusText = 'VLAN удалён.';
	break;
	default:
		$error++;
		$data->statusText = 'Указано неверное действие!';
	break;
}

if($update && !$error):
	$DB -> ExecuteQuery($sqlQuery);
endif;
echo json_encode($data);