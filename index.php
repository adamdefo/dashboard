<?php

session_start();

include_once('settings.php');
include_once('additional/additional.php');

require('db/DB.php');
$DB = new DB();

require('auth/Auth.php');
$Auth = new Auth();

if($Auth->isLoggedIn()):

	$view = empty($_GET['view']) ? 'index' : $_GET['view'];
	$view = stripslashes($view);
	$view = strip_tags($view);

	$mainMenu = $DB -> GetListItems('MENU'); // главное меню

	switch($view) {
		case('index'):
			$title = 'Главная';
		break;

		case('tasks'):
			$title = 'Задачи';

			if(isset($_GET['action']) && $_GET['action'] !== ''):
				$COMMUTATOR_STATUS = $DB -> GetListItems('DIR_TASK_STATUS');
				$LIST_OBJECT = $DB -> GetListItemsNoClone('COMMUTATORS');

				$queryGetListObjects = "SELECT id, UID FROM COMMUTATORS AS commutators
				 UNION
				SELECT id, UID FROM CLIENTS AS clients";

				$listObjects = $DB -> FetchDataInArray($DB -> ExecuteQuery($queryGetListObjects));

				$queryGetListUsers = "SELECT * FROM USERS AS users WHERE role_ID = 3 AND is_active = 1";
				$listUsers = $DB -> FetchDataInArray($DB -> ExecuteQuery($queryGetListUsers));

				switch($_GET['action']) {
					case('add'):
						$title = 'Создание задачи';
						$action = 'add';
					break;
					case('edit'):
						$action = 'edit';
						if(isset($_GET['id']) && $_GET['id'] !== ''):
							$id = $_GET['id'];
							$item = $DB -> GetItem('TASKS', $id);
							$title = 'Обновление задачи '.$item['name'];
						else:
							$item = $DB -> GetItem('TASKS', 1);
						endif;
					break;
				}
			else:
				$query = "SELECT * FROM TASKS AS res,
				(SELECT UID, MAX(date_last_update) AS date FROM TASKS
				GROUP BY UID) AS res2
				WHERE res.UID = res2.UID AND res.date_last_update = res2.date
				ORDER BY id DESC";

				$items = $DB -> FetchDataInArray($DB -> ExecuteQuery($query));
			endif;
		break;

		case('clients'):
			$title = 'Клиенты';
			if(isset($_GET['action']) && $_GET['action'] !== ''):
				$CONNECTION_TYPE = $DB -> GetListItems('DIR_CONNECTION_TYPE');
				switch($_GET['action']) {
					case('add'):
						$title = 'Создание клиента';
						$action = 'add';
					break;
					case('edit'):
						$action = 'edit';
						if(isset($_GET['id']) && $_GET['id'] !== ''):
							$item = $DB -> GetItem('CLIENTS', $_GET['id']);
							$title = 'Обновление клиента '.$item['full_name'];
							// список подключенных VLAN
							$query = "SELECT * FROM VLAN AS res,
(SELECT UID, MAX(date_last_update) AS date FROM VLAN GROUP BY UID) AS res2
WHERE res.UID = res2.UID AND res.date_last_update = res2.date AND res.status != 'off' AND res.client_ID = ".$item['UID']." ORDER BY id DESC";
							$listVLAN = $DB -> FetchDataInArray($DB -> ExecuteQuery($query));
							// список доступных IP для модалки
							$queryFreeIP = "SELECT * FROM IP_ADRESS AS res,
(SELECT UID, MAX(date_last_update) AS date FROM IP_ADRESS GROUP BY UID) AS res2
WHERE res.UID = res2.UID AND res.date_last_update = res2.date AND res.status = 'off' ORDER BY id DESC";
							$listFreeIP = $DB -> FetchDataInArray($DB -> ExecuteQuery($queryFreeIP));
							// список подключенных IP
							$queryIP = "SELECT ip.UID, ip.ip, ip.status, ip.vlan_ID, vlan.value, clients.full_name, ip.speed FROM
(SELECT UID, ip, status, vlan_ID, client_ID, speed FROM IP_ADRESS AS res,
 (SELECT UID as uid2, MAX(date_last_update) AS date_update FROM IP_ADRESS
  GROUP BY UID) AS res2
 WHERE res.UID = res2.uid2 AND res.date_last_update = res2.date_update AND res.status = 'on' AND res.client_ID = ".$item['UID']." ORDER BY id DESC) as ip
 INNER JOIN (SELECT value, UID as vlan_ID, MAX(date_last_update) FROM VLAN
  GROUP BY vlan_ID) as vlan ON ip.vlan_ID = vlan.vlan_ID
 INNER JOIN (SELECT full_name, UID, MAX(date_last_update) FROM CLIENTS
  GROUP BY UID) as clients ON ip.client_ID = clients.UID";
							$listIP = $DB -> FetchDataInArray($DB -> ExecuteQuery($queryIP));
						else:
							$item = $DB -> GetItem('CLIENTS', 1);
						endif;
					break;
				}
			else:
				$items = $DB -> GetGroupedListItems('CLIENTS');
			endif;
		break;

		case('test'):
			$query = "SELECT ip.UID, ip.ip, ip.status, vlan.value, clients.full_name, ip.speed FROM
(SELECT UID, ip, status, vlan_ID, client_ID, speed FROM IP_ADRESS AS res,
 (SELECT UID as uid2, MAX(date_last_update) AS date_update FROM IP_ADRESS
  GROUP BY UID) AS res2
 WHERE res.UID = res2.uid2 AND res.date_last_update = res2.date_update AND res.status = 'on' AND res.client_ID = 1 ORDER BY id DESC) as ip
 INNER JOIN (SELECT value, UID, MAX(date_last_update) FROM VLAN
  GROUP BY UID) as vlan ON ip.vlan_ID = vlan.UID
 INNER JOIN (SELECT full_name, UID, MAX(date_last_update) FROM CLIENTS
  GROUP BY UID) as clients ON ip.client_ID = clients.UID";
			$list = $DB -> FetchDataInArray($DB -> ExecuteQuery($query));
			echo '<pre>';
			print_r($list);
			echo '</pre>';
		break;

		case('commutators'):
			$title = 'Коммутаторы';

			if(isset($_GET['action']) && $_GET['action'] !== ''):
				$CONNECTION_TYPE = $DB -> GetListItems('DIR_CONNECTION_TYPE');
				$COMMUTATOR_STATUS = $DB -> GetListItems('DIR_COMMUTATOR_STATUS');

				switch($_GET['action']) {
					case('add'):
						$title = 'Создание коммутатора';
						$action = 'add';
						$query = "SELECT * FROM COMMUTATORS AS res,
						(SELECT UID, MAX(date_last_update) AS date FROM COMMUTATORS
						GROUP BY UID) AS res2
						WHERE res.UID = res2.UID AND res.date_last_update = res2.date
						ORDER BY id DESC";
						$listCommutators = $DB -> FetchDataInArray($DB -> ExecuteQuery($query));
					break;
					case('edit'):
						$action = 'edit';
						if(isset($_GET['id']) && $_GET['id'] !== ''):
							$item = $DB -> GetItem('COMMUTATORS', $_GET['id']);
							$title = 'Обновление коммутатора '.$item['model'].' | '.$item['UID'];
							$query = "SELECT * FROM COMMUTATORS AS res,
							(SELECT UID, MAX(date_last_update) AS date FROM COMMUTATORS
							GROUP BY UID) AS res2
							WHERE res.UID = res2.UID AND res.date_last_update = res2.date AND res.UID != $item[UID]
							ORDER BY id DESC";
							$listCommutators = $DB -> FetchDataInArray($DB -> ExecuteQuery($query));
						else:
							$item = $DB -> GetItem('COMMUTATORS', 1);
						endif;
					break;
				}
			else:
				require('db/CommutatorsTree.php');
				$commutatorsTree = new CommutatorsTree();
			endif;
		break;

		case('users'):
			$title = 'Пользователи';

			if(isset($_GET['action']) && $_GET['action'] !== ''):
				$ROLES= $DB -> GetListItems('USERS_ROLES', 'id', 'DESC');
				switch($_GET['action']) {
					case('add'):
						$title = 'Создание пользователя';
						$action = 'add';
					break;
					case('edit'):
						$action = 'edit';
						if(isset($_GET['id']) && $_GET['id'] !== ''):
							$id = $_GET['id'];
							$item = $DB -> GetItem('USERS', $id);
							$title = 'Редактирование пользователя '.$item['fio'];
						else:
							$item = $DB -> GetItem('USERS', 1);
						endif;
					break;
				}
			else:
				$items = $DB -> GetListItems('USERS');
			endif;

		break;

		case('ip_adress'):
			$title = 'IP адреса';

			if(isset($_GET['action']) && $_GET['action'] !== ''):
				$clients = $DB -> GetGroupedListItems('CLIENTS');
				$VLAN = $DB -> GetGroupedListItems('VLAN');

				switch($_GET['action']) {
					case('add'):
						$title = 'Создать IP адрес';
						$action = 'add';
					break;
					case('edit'):
						$action = 'edit';
						if(isset($_GET['id']) && $_GET['id'] !== ''):
							$id = $_GET['id'];
							$item = $DB -> GetItem('IP_ADRESS', $id);
							$title = 'Обновление IP: '.$item['ip'];
						else:
							$item = $DB -> GetItem('IP_ADRESS', 1);
						endif;
					break;
				}
			else:
				$query = "SELECT * FROM IP_ADRESS AS res,
				(SELECT UID, MAX(date_last_update) AS date FROM IP_ADRESS
				GROUP BY UID) AS res2
				WHERE res.UID = res2.UID AND res.date_last_update = res2.date
				ORDER BY id DESC";

				$items = $DB -> FetchDataInArray($DB -> ExecuteQuery($query));
			endif;
		break;

		case('vlan'):
			$title = 'VLAN';

			if(isset($_GET['action']) && $_GET['action'] !== ''):
				$clients = $DB -> GetGroupedListItems('CLIENTS');

				switch($_GET['action']) {
					case('add'):
						$title = 'Создать VLAN';
						$action = 'add';
					break;
					case('edit'):
						$action = 'edit';
						if(isset($_GET['id']) && $_GET['id'] !== ''):
							$id = $_GET['id'];
							$item = $DB -> GetItem('VLAN', $id);
							$title = 'Обновление VLAN: '.$item['vlan'];
						else:
							$item = $DB -> GetItem('VLAN', 1);
						endif;
					break;
				}
			else:
				$query = "SELECT * FROM VLAN AS res,
				(SELECT UID, MAX(date_last_update) AS date FROM VLAN
				GROUP BY UID) AS res2
				WHERE res.UID = res2.UID AND res.date_last_update = res2.date
				ORDER BY id DESC";

				$items = $DB -> FetchDataInArray($DB -> ExecuteQuery($query));
			endif;
		break;
	}

	include('view/index.php');

else:

	include('view/login.php');

endif;
