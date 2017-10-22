<?php

require('../db/DB.php');
$DB = new DB();

$arrKeysToStr = implode(",", array_keys($_POST));

$action = 'add';

$data = json_decode($_REQUEST['data']); // входящие данные

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
	$fullName = $_POST['full_name'];
	$shortName = $_POST['short_name'];
	$director = $_POST['director'];
	$inn = $_POST['inn'];
	$contactPerson = $_POST['contact_person'];
	$contactPersonPhone = $_POST['contact_person_phone'];
	$ulAdress = $_POST['ul_adress'];
	$factAdress = $_POST['fact_adress'];
	$connectionDate = $_POST['connection_date'];
	$connectionTypeID = $_POST['connection_type_ID'];
	$note = $_POST['note'];
	$noteOpen = $_POST['note_open'];
	$comment = $_POST['comment'];
	$date_created = $_POST['date_created'];
endif;

$data = ['fullName'];

$sqlQuery = "";
switch($action) {
	case('add'):
		$countUniqueItems = $DB -> GetCountUniqueItems('CLIENTS');
		$UID = ++$countUniqueItems[0]['quantity'];
		$date_last_update = $date_created;
		$sqlQuery = "INSERT INTO CLIENTS (UID,full_name,short_name,director,inn,contact_person,contact_person_phone,ul_adress,fact_adress,connection_date,connection_type_ID,note,note_open,comment,date_created,date_last_update) 
		VALUES ('$UID','$fullName','$shortName','$director','$inn','$contactPerson','$contactPersonPhone','$ulAdress','$factAdress','$connectionDate','$connectionTypeID','$note','$noteOpen','$comment','$date_created','$date_last_update')";
	break;
	case('edit'):
		$UID = $_POST['UID'];
		$date_last_update = date('Y-m-d H:i:s');
		$sqlQuery = "INSERT INTO CLIENTS (UID,full_name,short_name,director,inn,contact_person,contact_person_phone,ul_adress,fact_adress,connection_date,connection_type_ID,note,note_open,comment,date_created,date_last_update) 
		VALUES ('$UID','$fullName','$shortName','$director','$inn','$contactPerson','$contactPersonPhone','$ulAdress','$factAdress','$connectionDate','$connectionTypeID','$note','$noteOpen','$comment','$date_created','$date_last_update')";
	break;
	case('delete'):
		$sqlQuery = "DELETE FROM CLIENTS WHERE id='$id'";
	break;
	default:
		echo 'Указано неверное действие';
	break;
}

//$data = $DB -> ExecuteQuery($sqlQuery);
//echo $data ? '<html><head><meta http-equiv="Refresh" content="0; URL=../index.php?view=clients"></head></html>' : 'fail';
echo json_encode($data);