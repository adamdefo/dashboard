<?php

session_start();

require('db/DB.php');
$DB = new DB();

include('settings.php');

$view = empty($_GET['view']) ? 'index' : $_GET['view'];
$view = stripslashes($view);
$view = strip_tags($view);

switch($view) {
	case('index'):
		$title = 'Главная';
	break;

	case('tasks'):
		$title = 'Задачи';

		if(isset($_GET['action']) && $_GET['action'] !== ''):
			$COMMUTATOR_STATUS = $DB -> GetListItems('DIR_COMMUTATOR_STATUS');
			$LIST_OBJECT = $DB -> GetListItemsNoClone('COMMUTATORS', 'value');

			switch($_GET['action']) {
				case('add'):
					$title = 'Создание задачи';
					$action = 'add';
				break;
				case('edit'):
					$action = 'edit';
					if(isset($_GET['id']) && $_GET['id'] !== ''):
						$item = $DB -> GetItem('TASKS', $_GET['id']);
						$title = 'Обновление задачи '.$item['name'];
					else:
						$item = $DB -> GetItem('TASKS', 1);
					endif;
				break;
			}
		else:
			$items = $DB -> GetGroupedListItems('TASKS','date_last_update');
		endif;
	break;

	case('clients'):
		$title = 'Клиенты';
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
						$title = 'Обновление коммутатора '.$item['value'];
					else:
						$item = $DB -> GetItem('COMMUTATORS', 1);
					endif;
				break;
			}
		else:
			$items = $DB -> GetGroupedListItems('COMMUTATORS','date_last_update');
		endif;
	break;

	case('users'):
		$title = 'Пользователи';

	break;
}

include('view/index.php');