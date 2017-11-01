<?php

require('../db/DB.php');
$DB = new DB();

$arrKeysToStr = implode(",", array_keys($_POST));

$action = 'add';
$insert = "INSERT INTO COMMUTATORS (UID,model,adress,ip,segment,firmware,parent_ID,parent_PORT,connection_type_ID,count_client_ports,status_ID,note,note_open,comment,date_created,date_last_update)";

// добавление или редактирование
if(isset($_POST['action'])):
	$action = $_POST['action'];
endif;

// удаление
if(isset($_GET['action'])):
	$action = $_GET['action'];
	$id = $_GET['id'];
endif;

if($action === 'add' || $action === 'edit'):
	$model = $_POST['model'];
	$adress = $_POST['adress'];
	$ip = $_POST['ip'];
	$segment = $_POST['segment'];
	$firmware = $_POST['firmware'];
	$parent_ID = $_POST['parent_ID'] === '0' ? NULL : $_POST['parent_ID'];
	$parent_PORT = $_POST['parent_PORT'];
	$connection_type_ID = $_POST['connection_type_ID'];
	$countPorts = $_POST['count_client_ports'];
	$status_ID = $_POST['status_ID'];
	$note = $_POST['note'];
	$note_open = $_POST['note_open'];
	$comment = $_POST['comment'];
	$date_created = date('Y-m-d H:i:s');
endif;

function GetGenerateVLAN($commutator, $port, $segment) {
	$vlan = ($commutator - 200) * 24 + $port + 999 + $segment * 4000;
	return $vlan;
}

$sqlQuery = "";
switch($action) {
	case('add'):
		$countUniqueItems = $DB -> GetCountUniqueItems('COMMUTATORS');
		$UID = ++$countUniqueItems[0]['quantity'];
		$date_last_update = $date_created;
		$sqlQuery = $insert." VALUES ('$UID','$model','$adress','$ip','$segment','$firmware','$parent_ID','$parent_PORT','$connection_type_ID','$countPorts','$status_ID','$note','$note_open','$comment','$date_created','$date_last_update')";
		if(!is_null($parent_ID)) {
			$port = 1;
			while($port < $countPorts) {
				$countUniqueVLAN = $DB -> GetCountUniqueItems('VLAN');
				$uidVLAN = ++$countUniqueVLAN[0]['quantity'];
				$vlan = GetGenerateVLAN($parent_ID, $port, $segment);
				$dateCreatedVLAN = date('Y-m-d H:i:s');
				$dateLastUpdateVLAN = $dateCreatedVLAN;
				$sqlQueryVLAN = "INSERT INTO VLAN (UID,value,speed,status,date_created,date_last_update,client_ID) 
				VALUES ('$uidVLAN','$vlan',0,0,'$dateCreatedVLAN','$dateLastUpdateVLAN',0)";
				$DB -> ExecuteQuery($sqlQueryVLAN);
				$port++;
			}
		}
	break;
	case('edit'):
		$UID = $_POST['UID'];
		$date_last_update = date('Y-m-d H:i:s');
		$sqlQuery = $insert." VALUES ('$UID','$model','$adress','$ip','$segment','$firmware','$parent_ID','$parent_PORT','$connection_type_ID','$countPorts','$status_ID','$note','$note_open','$comment','$date_created','$date_last_update')";
	

	break;
	case('delete'):
		$sqlQuery = "DELETE FROM COMMUTATORS WHERE id='$id'";
	break;
	default:
		echo 'Указано неверное действие';
	break;
}

//$data = $DB -> ExecuteQuery($sqlQuery);
//echo $data ? '<html><head><meta http-equiv="Refresh" content="0; URL=../index.php?view=commutators"></head></html>' : 'fail';