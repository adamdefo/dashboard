<?php

session_start();

require('db/DB.php');
$DB = new DB();

include('settings.php');

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
			//$LIST_OBJECT = $DB -> GetListItemsNoClone('COMMUTATORS');

			// $queryGetListObjects = "SELECT * FROM COMMUTATORS AS cmt, 
			// (SELECT UID, MAX(date_last_update) AS date FROM COMMUTATORS 
			// GROUP BY UID) AS cmt_group 
			// WHERE cmt.UID = cmt_group.UID AND cmt.date_last_update = cmt_group.date 
			// ORDER BY id DESC 
			// UNION 
			// SELECT * FROM CLIENTS AS cln, 
			// (SELECT UID, MAX(date_last_update) AS date2 FROM CLIENTS 
			// GROUP BY UID) AS cln_group 
			// WHERE cln.UID = cln_group.UID AND cln.date_last_update = cln_group.date2 
			// ORDER BY id DESC";

			$queryGetListObjects = "SELECT id, UID FROM COMMUTATORS AS commutators 
			 UNION 
			SELECT id, UID FROM CLIENTS AS clients";

			$listObjects = $DB -> FetchDataInArray($DB -> ExecuteQuery($queryGetListObjects));

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
						(SELECT UID, MAX(date_last_update) AS date FROM VLAN 
						GROUP BY UID) AS res2 
						WHERE res.UID = res2.UID AND res.date_last_update = res2.date AND res.status != 0 AND res.client_ID = $item[UID] 
						ORDER BY id DESC";
						$listVLAN = $DB -> FetchDataInArray($DB -> ExecuteQuery($query));
					else:
						$item = $DB -> GetItem('CLIENTS', 1);
					endif;
				break;
			}
		else:
			$items = $DB -> GetGroupedListItems('CLIENTS');
		endif;
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
				break;
				case('edit'):
					$action = 'edit';
					if(isset($_GET['id']) && $_GET['id'] !== ''):
						$item = $DB -> GetItem('COMMUTATORS', $_GET['id']);
						$title = 'Обновление коммутатора '.$item['model'].' | '.$item['UID'];
					else:
						$item = $DB -> GetItem('COMMUTATORS', 1);
					endif;
				break;
			}
		else:
			$items = $DB -> GetGroupedListItems('COMMUTATORS');
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