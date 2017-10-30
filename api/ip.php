<?php
require('../db/DB.php');
$DB = new DB();
$tbl = 'IP_ADRESS';
$data = json_decode($_REQUEST['data']); // входящие данные

$error = 0;
$action = 'add'; // по умолчанию создаем новую запись
$insert = "INSERT INTO ".$tbl." (UID,ip,speed,status,date_created,date_last_update,vlan_ID,client_ID)";

$action = $data->action;
$ip = $data->ip;
$vlanID = $data->vlanID;
$clientID = $data->clientID;
$date_created = date('Y-m-d H:i:s');
$data->isExist = false; // флаг, по которому узнаем вставлять в таблицу или нет
$update = true;

$sqlQuery = "";
switch($action) {
	case('add'):
		$listIP = $DB -> GetListItems($tbl,'date_last_update','DESC','ip',$ip);
		$countUniqueItems = $DB -> GetCountUniqueItems('IP');
		// проверка, есть ли такой IP
		if(count($listIP)): // ecли есть
			if(!$listIP[0]['status']):
				$data->UID = $listIP[0]['UID'];
				$date_created = $listIP[0]['date_created'];
				$date_last_update = date('Y-m-d H:i:s');
				$sqlQuery = $insert." VALUES ('$data->UID','$ip','$data->speed',1,'$date_created','$date_last_update','$vlanID','$clientID')";
				$data->statusText = 'IP привязан к клиенту '.$clientID;
			else: // иначе выведем сообщение у какого клиента этот IP
				$update = false;
				$data->isExist = true;
				$data->statusText = 'Такой IP есть у клиента '.$listIP[0]['client_ID'];
			endif;
		else: // иначе создаем новый IP
			$UID = ++$countUniqueItems[0]['quantity'];
			$date_last_update = $date_created;
			$sqlQuery = $insert." VALUES ('$UID','$ip','$data->speed',1,'$date_created','$date_last_update','$vlanID','$clientID')";
			$data->statusText = 'IP добавлен.';
		endif;
	break;
	case('edit'): // отключение VLAN
		$listIP = $DB -> GetListItems($tbl,'date_last_update','DESC','UID',$data->UID);
		$ip = $listIP[0]['ip'];
		$vlanID = $listIP[0]['vlan_ID'];
		$speed = $listIP[0]['speed'];
		$date_created = $listIP[0]['date_created'];
		$date_last_update = date('Y-m-d H:i:s');
		$sqlQuery = $insert." VALUES ('$data->UID','$ip','$speed',0,'$date_created','$date_last_update','$vlanID','$clientID')";
		$data->statusText = 'IP отключен.';
	break;
	case('delete'):
		$update = false;
		$sqlQuery = "DELETE FROM ".$tbl." WHERE id='$id'";
		$DB -> ExecuteQuery($sqlQuery);
		$data->statusText = 'IP удалён.';
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