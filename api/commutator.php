<?php

require('../db/DB.php');
$DB = new DB();

$arrKeysToStr = implode(",", array_keys($_POST));

$action = 'add';

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
	$count_client_ports = $_POST['count_client_ports'];
	$status_ID = $_POST['status_ID'];
	$note = $_POST['note'];
	$note_open = $_POST['note_open'];
	$comment = $_POST['comment'];
	$date_created = $_POST['date_created'];
endif;

$sqlQuery = "";
switch($action) {
	case('add'):
		$countUniqueItems = $DB -> GetCountUniqueItems('TASKS','value');
		$value = ++$countUniqueItems[0]['quantity'];
		$date_last_update = NULL;
		$sqlQuery = "INSERT INTO COMMUTATORS (value,model,adress,ip,segment,firmware,parent_ID,parent_PORT,connection_type_ID,count_client_ports,status_ID,note,note_open,comment,date_created,date_last_update) 
		VALUES ('$value','$model','$adress','$ip','$segment','$firmware','$parent_ID','$parent_PORT','$connection_type_ID','$count_client_ports','$status_ID','$note','$note_open','$comment','$date_created','$date_last_update')";
	break;
	case('edit'):
		$value = $_POST['value'];
		$date_last_update = date('Y-m-d H:i:s');
		$sqlQuery = "INSERT INTO COMMUTATORS (value,model,adress,ip,segment,firmware,parent_ID,parent_PORT,connection_type_ID,count_client_ports,status_ID,note,note_open,comment,date_created,date_last_update) 
		VALUES ('$value','$model','$adress','$ip','$segment','$firmware','$parent_ID','$parent_PORT','$connection_type_ID','$count_client_ports','$status_ID','$note','$note_open','$comment','$date_created','$date_last_update')";
	break;
	case('delete'):
		$sqlQuery = "DELETE FROM COMMUTATORS WHERE id='$id'";
	break;
	default:
		echo 'Указано неверное действие';
	break;
}

$data = mysql_query($sqlQuery, $DB -> CreateConnect());
echo $data ? '<html><head><meta http-equiv="Refresh" content="0; URL=../index.php?view=commutators"></head></html>' : 'fail';