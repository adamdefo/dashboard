<?php

require('../db/DB.php');
$DB = new DB();

$arrKeysToStr = implode(",", array_keys($_POST));

$value = $_POST['value'];
$model = $_POST['model'];
$adress = $_POST['adress'];
$ip = $_POST['ip'];
$segment = $_POST['segment'];
$firmware = $_POST['firmware'];
echo gettype($_POST['parent_ID']);
$parent_ID = $_POST['parent_ID'] === '0' ? NULL : $_POST['parent_ID'];
$parent_PORT = $_POST['parent_PORT'];
$connection_type_ID = $_POST['connection_type_ID'];
$count_client_ports = $_POST['count_client_ports'];
$status_ID = $_POST['status_ID'];
$note = $_POST['note'];
$note_open = $_POST['note_open'];
$comment = $_POST['comment'];
$date_stamp = $_POST['date_stamp'];

$sqlQuery = "INSERT INTO COMMUTATORS (".$arrKeysToStr.") VALUES ('$value','$model','$adress','$ip','$segment','$firmware','$parent_ID','$parent_PORT','$connection_type_ID','$count_client_ports','$status_ID','$note','$note_open','$comment','$date_stamp')";

$data = mysql_query($sqlQuery, $DB -> CreateConnect());

if($data) {
	echo 'ok';
} else {
	echo 'fuck';
}

?>