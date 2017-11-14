<?php

require('../db/DB.php');
$DB = new DB();
$tbl = 'IP_ADRESS';

$arrKeysToStr = implode(",", array_keys($_POST));

$action = 'add'; // по умолчанию будет создавать новую запись
$insert = "INSERT INTO ".$tbl." (UID,ip,speed,status,date_created,date_last_update,vlan_ID,client_ID)";


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
	$ip = $_POST['ip'];
	$speed = $_POST['speed'];
	$clientID = $_POST['client_ID'];
	$vlanID = $_POST['vlan_ID'];
	$date_created = $_POST['date_created'];
endif;

$sqlQuery = "";
switch($action) {
	case('add'):
		$countUniqueItems = $DB -> GetCountUniqueItems($tbl);
		$UID = ++$countUniqueItems[0]['quantity'];
		$date_last_update = $date_created;
		$sqlQuery = $insert." VALUES ('$UID','$ip','$speed','off','$date_created','$date_last_update','$vlanID','$clientID')";
	break;
	case('edit'):
		$UID = $_POST['UID'];
		$status = $_POST['status'];
		$date_last_update = date('Y-m-d H:i:s');
		$sqlQuery = $insert." VALUES ('$UID','$ip','$speed','$status','$date_created','$date_last_update','$vlanID','$clientID')";
	break;
	case('delete'):
		$sqlQuery = "DELETE FROM IP_ADRESS WHERE id='$id'";
	break;
	default:
		echo 'Указано неверное действие';
	break;
}

$data = $DB -> ExecuteQuery($sqlQuery);
echo $data ? '<html><head><meta http-equiv="Refresh" content="0; URL=../index.php?view=ip_adress"></head></html>' : 'Есть ошибки!';