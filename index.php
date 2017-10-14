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
					$action = 'add';
				break;
                case('edit'):
                	$action = 'edit';
                	if(isset($_GET['id']) && $_GET['id'] !== ''):
                		$item = $DB -> GetItem('COMMUTATORS', $_GET['id']);
                	else:
                		$item = $DB -> GetItem('COMMUTATORS', 1);
                	endif;
				break;
            }
    	else:
    		$items = $DB -> GetListItems('COMMUTATORS');
    	endif;
	break;

	case('users'):
		$title = 'Пользователи';

	break;
}

include('view/index.php');