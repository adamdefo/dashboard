<?php

require('../db/DB.php');
$DB = new DB();
$tbl = 'IP_ADRESS';
$data = json_decode($_REQUEST['data']); // входящие данные

$error = 0;
$action = 'add'; // по умолчанию создаем новую запись
$insert = "INSERT INTO ".$tbl." (UID,ip,speed,status,date_created,date_last_update,vlan_ID,client_ID)";

$action = $data->action;
$ip = $data->ip; // значение
$vlanID = $data->vlanID;
$clientID = $data->clientID;
$speed = $data->speed;
$dateCreated = date('Y-m-d H:i:s');
$data->isExist = false; // флаг, по которому узнаем вставлять в таблицу или нет
$update = true;

$sqlQuery = "";
switch($action) {
	case('add'):
		$listIP = $DB -> GetListItems($tbl,'date_last_update','DESC','ip',$ip);
		$countUniqueItems = $DB -> GetCountUniqueItems($tbl);
		// проверка, есть ли такой IP
		if(count($listIP)): // ecли есть
			if($listIP[0]['status'] === 'off'):
				$uid = $listIP[0]['UID'];
//				$speed = $listIP[0]['speed'];
				$dateCreated = $listIP[0]['date_created'];
				$date_last_update = date('Y-m-d H:i:s');
				$sqlQuery = $insert." VALUES ('$uid','$ip','$speed','on','$dateCreated','$date_last_update','$vlanID','$clientID')";
				$data->statusText = 'IP привязан к клиенту '.$clientID;
				$data->vlanID = $vlanID;
				$data->uid = $uid;
			else: // иначе выведем сообщение у какого клиента этот IP
				$update = false;
				$data->isExist = true;
				$data->statusText = 'Такой IP есть у клиента '.$listIP[0]['client_ID'];
			endif;
		else: // иначе создаем новый IP
			$uid = ++$countUniqueItems[0]['quantity'];
			$date_last_update = $dateCreated;
			$sqlQuery = $insert." VALUES ('$uid','$ip','$speed','on','$dateCreated','$date_last_update','$vlanID','$clientID')";
			$data->statusText = 'IP добавлен.';
			$data->vlanID = $vlanID;
			$data->uid = $uid;
		endif;
	break;
	case('edit'): // отключение IP
		$uid = $data->uid; // идентификатор
		$listIP = $DB -> GetListItems($tbl,'date_last_update','DESC','UID',$uid);
		$ip = $listIP[0]['ip'];
		$vlanID = $listIP[0]['vlan_ID'];
		$speed = $listIP[0]['speed'];
		$dateCreated = $listIP[0]['date_created'];
		$date_last_update = date('Y-m-d H:i:s');
		$sqlQuery = $insert." VALUES ('$uid','$ip','$speed','off','$dateCreated','$date_last_update','$vlanID','$clientID')";
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