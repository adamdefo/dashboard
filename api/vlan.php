<?php
require('../db/DB.php');
$DB = new DB();

$data = json_decode($_REQUEST['data']); // входящие данные

$error = 0;
$action = 'add'; // по умолчанию создаем новую запись
$insert = "INSERT INTO VLAN (UID,value,speed,status,date_created,date_last_update,client_ID)";

$action = $data->action;
$VLAN = $data->vlan;
$clientID = $data->clientID;
$speed = $data->speed;
$date_created = date('Y-m-d H:i:s');
$data->isExist = false; // флаг, по которому узнаем вставлять в таблицу или нет
$update = true;

$sqlQuery = "";
switch($action) {
	case('add'):
		$countUniqueItems = $DB -> GetCountUniqueItems('VLAN');
		$query = "SELECT * FROM VLAN AS res,
(SELECT UID, MAX(date_last_update) AS date FROM VLAN
GROUP BY UID) AS res2
WHERE res.UID = res2.UID AND res.date_last_update = res2.date AND res.client_ID = $clientID ORDER BY id DESC";
		// получаю список VLANов у клиента
		$listVlanByClientId = $DB -> FetchDataInArray($DB -> ExecuteQuery($query));
		// проверка есть ли у клиента VLANы
		if(count($listVlanByClientId)): // если есть
			foreach($listVlanByClientId as $vlan):
				if($vlan['value'] == $VLAN): // если у клиента есть VLAN, который мы отправляем с формы
					$data->isExist = true;
					// проверяю статус VLAN
					if(!$vlan['status']): // если он отключен, то просто включим его и запишем дату последнего изменения состояния
						$UID = $vlan['UID'];
						$date_created = $vlan['date_created'];
						$date_last_update = date('Y-m-d H:i:s');
						$sqlQuery = $insert." VALUES ('$UID','$VLAN','$speed',1,'$date_created','$date_last_update','$clientID')";
						$data->statusText = 'VLAN снова подключен к клиенту '.$clientID;
					else: // иначе выведем сообщение пользователю, что такой VLAN есть и он подключен
						$update = false;
						$data->statusText = 'VLAN уже подключен к клиенту '.$clientID;
					endif;
					break;
				else:
					$data->isExist = false;
				endif;
			endforeach;

			if(!$data->isExist):
				$UID = ++$countUniqueItems[0]['quantity'];
				$date_last_update = $date_created;
				$sqlQuery = $insert." VALUES ('$UID','$VLAN','$speed',1,'$date_created','$date_last_update','$clientID')";
				$data->statusText = 'Такого VLAN нет у клиента, но теперь есть.';
			endif;
		else: // иначе создаем новый VLAN
			$UID = ++$countUniqueItems[0]['quantity'];
			$date_last_update = $date_created;
			$sqlQuery = $insert." VALUES ('$UID','$VLAN','$speed',1,'$date_created','$date_last_update','$clientID')";
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