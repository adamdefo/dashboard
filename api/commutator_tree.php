<?php

require('../db/DB.php');
$DB = new DB();

include_once('../additional/additional.php');

// входящие данные
$data = json_decode($_REQUEST['data']);
$commutatorID = $data->id;

// получаю всю инфу по коммутатору
$commutator = $DB->GetItem('COMMUTATORS', $commutatorID);

// генерирую vlanы для него
$arr = array();
$port = 1;
while($port <= $commutator['count_client_ports']) {
  $item = array();
  $item['port'] = $port;

  $vlan = GenerateVLAN($commutator['UID'], $port, $commutator['segment']);
  $item['vlan'] = $vlan;

  $queryGetListVlan = "SELECT * FROM VLAN as vlan,
 (SELECT UID, MAX(date_last_update) AS vlan_last_update FROM VLAN
  GROUP BY UID) AS group_vlan
 WHERE vlan.value = '$vlan' AND vlan.UID = group_vlan.UID AND vlan.date_last_update = group_vlan.vlan_last_update";
  $listVlan = $DB -> FetchDataInArray($DB -> ExecuteQuery($queryGetListVlan));

  if(count($listVlan)):
    $item['clients'] = array();
    foreach($listVlan as $vlan):
      $clientID = $vlan['client_ID'];
      $queryGetListClients = "SELECT UID, full_name, contact_person, contact_person_phone, date_last_update FROM CLIENTS AS clients,
(SELECT UID AS group_uid, MAX(date_last_update) AS client_last_update FROM CLIENTS
 GROUP BY group_uid) AS group_clients
 WHERE clients.UID = '$clientID' AND clients.date_last_update = group_clients.client_last_update";
      $listClients = mysql_fetch_array($DB -> ExecuteQuery($queryGetListClients));
      $listClients['status'] = $vlan['status'];
      array_push($item['clients'], $listClients);
    endforeach;
  endif;

  array_push($arr, $item);
  $port++;
}

echo json_encode($arr);