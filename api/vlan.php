<?php

require('../db/DB.php');
$DB = new DB();

include_once('../additional/additional.php');

$tbl = 'VLAN';
$data = json_decode($_REQUEST['data']); // входящие данные

$error = 0;
$action = 'add'; // по умолчанию создаем новую запись
$insert = "INSERT INTO ".$tbl." (UID,value,speed,status,date_created,date_last_update,client_ID)";
$insertIP = "INSERT INTO IP_ADRESS (UID,ip,status,date_created,date_last_update,vlan_ID,client_ID,speed)";

$action = $data->action;
$VLAN = $data->vlan;
$clientID = $data->clientID;
$speed = $data->speed;
$dateCreated = date('Y-m-d H:i:s');
$data->isExist = false; // флаг, по которому узнаем вставлять в таблицу или нет
$isNew = false;
$update = true;

// создает дефолтный IP
function CreateDefaultIP($DB, $vlan, $vlanID, $clientID, $dateCreated, $speed) {
	$ip = GenerateDefaultIP($vlan);
	$checkDefaultIP = $DB->FindClientIP($ip, $clientID, $vlanID, 'on');
	// если нет, то создаем
	if(!count($checkDefaultIP)):
		$countUniqueIP = $DB->GetCountUniqueItems('IP_ADRESS');
		$uidIP = $countUniqueIP[0]['quantity'] + 1;
		$statusIP = 'on';
		$insertIP = "INSERT INTO IP_ADRESS (UID,ip,status,date_created,date_last_update,vlan_ID,client_ID,speed)";
		$queryCreateDefaultIP = $insertIP." VALUES ('$uidIP','$ip','$statusIP','$dateCreated','$dateCreated','$vlanID','$clientID','$speed')";
		$DB->ExecuteQuery($queryCreateDefaultIP);
	else:
		foreach($checkDefaultIP as $defaultIP):
			if(CheckDefaultIp($defaultIP['ip'])):
				$uidIP = $defaultIP['UID'];
				$statusIP = 'on';
				$dateCreatedIP = $defaultIP['date_created'];
				$dateLastUpdateIP = date('Y-m-d H:i:s');;
				$insertIP = "INSERT INTO IP_ADRESS (UID,ip,status,date_created,date_last_update,vlan_ID,client_ID,speed)";
				$queryCreateDefaultIP = $insertIP." VALUES ('$uidIP','$ip','$statusIP','$dateCreatedIP','$dateLastUpdateIP','$vlanID','$clientID','$speed')";
				$DB->ExecuteQuery($queryCreateDefaultIP);
				break;
			endif;
		endforeach;
	endif;
}

$sqlQuery = "";
switch($action) {
	case('add'):
		$newVlan = false;
		$countUniqueItems = $DB -> GetCountUniqueItems('VLAN');
		$query = "SELECT * FROM VLAN AS res,
(SELECT UID, MAX(date_last_update) AS date FROM VLAN
GROUP BY UID) AS res2
WHERE res.UID = res2.UID AND res.date_last_update = res2.date AND res.client_ID = ".$clientID." ORDER BY id DESC";
		// получаю список VLANов у клиента
		$listVlanByClientId = $DB -> FetchDataInArray($DB -> ExecuteQuery($query));
		// проверка есть ли у клиента VLANы
		if(count($listVlanByClientId)): // если есть
			foreach($listVlanByClientId as $vlan):
				if($VLAN == $vlan['value']): // если у клиента есть VLAN, который мы отправляем с формы
					$isNew = false;
					// проверяю статус VLAN
					if($vlan['status'] === 'off'): // если он отключен, то просто включим его и запишем дату последнего изменения статуса
						$data->isExist = false;
						$vlanID = $vlan['UID'];
						$dateCreated = $vlan['date_created'];
						$dateLastUpdate = date('Y-m-d H:i:s');
						$sqlQuery = $insert." VALUES ('$vlanID','$VLAN','$speed','on','$dateCreated','$dateLastUpdate','$clientID')";
						$data->statusText = 'VLAN снова подключен к клиенту.';
						$data->vlanID = $vlanID;
						CreateDefaultIP($DB, $VLAN, $vlanID, $clientID, $dateCreated, $speed);
					else: // иначе выведем сообщение пользователю, что такой VLAN есть и он подключен
						$data->isExist = true;
						$update = false;
						$data->statusText = 'VLAN уже подключен у клиента.';
					endif;
					break;
				else:
					$isNew = true;
					$data->isExist = false;
				endif;
			endforeach;

			if($isNew):
				$vlanID = ++$countUniqueItems[0]['quantity'];
				$dateLastUpdate = $dateCreated;
				$sqlQuery = $insert." VALUES ('$vlanID','$VLAN','$speed','on','$dateCreated','$dateLastUpdate','$clientID')";
				$data->vlanID = $vlanID;
				$data->statusText = 'VLAN создан и добавлен.';
				CreateDefaultIP($DB, $VLAN, $vlanID, $clientID, $dateCreated, $speed);
			endif;

		else: // иначе создаем новый VLAN
			$vlanID = ++$countUniqueItems[0]['quantity'];
			$dateLastUpdate = $dateCreated;
			$sqlQuery = $insert." VALUES ('$vlanID','$VLAN','$speed','on','$dateCreated','$dateLastUpdate','$clientID')";
			$data->vlanID = $vlanID;
			$data->statusText = 'VLAN создан.';
			CreateDefaultIP($DB, $VLAN, $vlanID, $clientID, $dateCreated, $speed);
		endif;
	break;
	case('edit'): // отключение VLAN
		$vlanID = $data->vlanID;
		$listVlan = $DB -> GetListItems('VLAN','date_last_update','DESC','UID',$vlanID);
		$value = $listVlan[0]['value'];
		$speed = $listVlan[0]['speed'];
		$dateCreated = $listVlan[0]['date_created'];
		$dateLastUpdate = date('Y-m-d H:i:s');
		$sqlQuery = $insert." VALUES ('$vlanID','$value','$speed','off','$dateCreated','$dateLastUpdate','$clientID')";
		$data->statusText = 'VLAN отключен.';
		// отключение всех активных IP у VLAN
		$listIP = $DB->GetListActiveIpByVlan($vlanID, $clientID);
		if(count($listIP)):
			foreach($listIP as $ip):
				$uidOffIp = $ip['UID'];
				$valueOffIp = $ip['ip'];
				$statusOffIp = 'off';
				$speedOffIp = $ip['speed'];
				$dateCreatedOffIp = $ip['date_created'];
				$dateLastUpdateOffIp = date('Y-m-d H:i:s');
				$queryOffIp = $insertIP." VALUES ('$uidOffIp','$valueOffIp','$statusOffIp','$dateCreatedOffIp','$dateLastUpdateOffIp','$vlanID','$clientID','$speedOffIp')";
				$DB->ExecuteQuery($queryOffIp);
			endforeach;
		endif;
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