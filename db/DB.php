<?php

include 'DBconfig.php';
 
class DB extends DBconfig {
	
	public $connect;
	public $dataSet;
	private $sqlQuery;
	
	protected $dbHost;
	protected $dbName;
	protected $dbUserName;
	protected $dbUserPass;
	
	function DB() {
		$this -> connect = NULL;
		$this -> sqlQuery = NULL;
		$this -> dataSet = NULL;

		$dbConfig = new DBconfig();
		$this -> dbHost = $dbConfig -> dbHost;
		$this -> dbName = $dbConfig -> dbName;
		$this -> dbUserName = $dbConfig -> dbUserName;
		$this -> dbUserPass = $dbConfig -> dbUserPass;
		$dbConfig = NULL;
	}
	
	public function CreateConnect() {
		$this -> connect = mysql_connect($this -> dbHost, $this -> dbUserName, $this -> dbUserPass);
		mysql_query('SET CHARACTER SET utf8');
		mysql_query('SET NAMES utf8');
		mysql_query("SET lc_time_names = 'ru_RU'");
		
		if(!$this -> connect || !mysql_select_db($this -> dbName, $this -> connect)) { 
			die('Ошибка соединения: ' . mysql_error()); 
		}
		
		return $this -> connect;
	}
	
	public function DestroyConnect() {
		$this -> connect = NULL;
		$this -> sqlQuery = NULL;
		$this -> dataSet = NULL;
		$this -> dbName = NULL;
		$this -> dbHost = NULL;
		$this -> dbUserName = NULL;
		$this -> dbUserPass = NULL;
	}
	
	public function FetchDataInArray($result) {
		$arr = array();
		$count = 0;

		while($row = mysql_fetch_array($result))
		{
			$arr[$count] = $row;
			$count++;
		}

		return $arr;
	}
	
	public function GetListItems($tbl, $order_field='id', $order_type='ASC', $filter_field, $filter_value, $start=0, $limit) {
		if(is_null($filter_field)):
			 $this -> sqlQuery = "SELECT * FROM $tbl ORDER BY $order_field $order_type";
		else:
			if(is_null($limit)) {
				$this -> sqlQuery = "SELECT * FROM $tbl WHERE $filter_field='$filter_value' ORDER BY $order_field $order_type";
			} else {
				$this -> sqlQuery = "SELECT * FROM $tbl WHERE $filter_field=$filter_value ORDER BY $order_field $order_type LIMIT $start, $limit";
			}
		endif;

		return $this -> FetchDataInArray($this -> ExecuteQuery($this -> sqlQuery));
	}

	/** 
	 * Получает сгруппированные данные по date_stamp
	 * $uid - уникальное идентификатор, чтобы выбрать повторяющиеся записи и среди них выбрать актуальную
	 * $field_date - поле с датой, по которой будет выбираться самая актуальная запись
	 */
	public function GetGroupedListItems($tbl, $uid = 'UID', $field_date = 'date_last_update') {
		$sqlQuery = "SELECT * FROM $tbl AS res,
		(SELECT $uid, MAX($field_date) AS date FROM $tbl 
		GROUP BY $uid) AS res2 
		WHERE res.$uid = res2.$uid AND res.$field_date = res2.date 
		ORDER BY id DESC";

		return $this -> FetchDataInArray($this -> ExecuteQuery($sqlQuery));
	}

	/** 
	* Получает список без повторов
	*/
	public function GetListItemsNoClone($tbl, $field = 'UID') {
		$this -> sqlQuery = "SELECT DISTINCT $field FROM $tbl";

		$data = mysql_query($this -> sqlQuery, $this -> CreateConnect());
		return $this -> FetchDataInArray($data);
	}
	
	/** 
	* Количество уникальных записей
	*/
	public function GetCountUniqueItems($tbl, $field = 'UID') {
		$this -> sqlQuery = "SELECT COUNT(DISTINCT $field) AS quantity FROM $tbl";
 
		$data = mysql_query($this -> sqlQuery, $this -> CreateConnect());
		return $this -> FetchDataInArray($data);
	}

	/** Выбирает один элемент из таблицы
	* $tbl - название таблицы, принимает
	* $field - поле таблицы, по которому искать
	* $value - значение, которое приходит в $_GET 
	*/
	public function GetItem($tbl, $value, $field='id') {
		if(is_string($value)):
			$this -> sqlQuery = "SELECT * FROM $tbl WHERE $field='$value'";
		else:
			$this -> sqlQuery = "SELECT * FROM $tbl WHERE $field=$value";
		endif;

		$data = mysql_query($this -> sqlQuery, $this -> CreateConnect());
		return mysql_fetch_array($data);
	}

	/** Получает количество элементов в таблице
	* $tbl - имя таблицы
	*/
	public function GetCountItems($tbl) {	
		$sqlQuery = "SELECT COUNT(*) FROM $tbl";
		$data = $this -> ExecuteQuery($sqlQuery);
		return mysql_fetch_array($data);
	}

	/**
	 * @param $ip - значение ip
	 * @param $clientID - UID клиента
	 * @param $vlanID - UID vlan
	 * @param $status (on/off)
	 * @return array
	 */
	public function FindClientIP($ip, $clientID, $vlanID, $status) {
		if(is_null($status)):
			$sqlQuery = "SELECT * FROM IP_ADRESS WHERE ip = '$ip' AND client_ID = '$clientID' AND vlan_ID = '$vlanID'";
		else:
			$sqlQuery = "SELECT * FROM IP_ADRESS WHERE ip = '$ip' AND client_ID = '$clientID' AND vlan_ID = '$vlanID' AND status = '$status'";
		endif;

		return $this -> FetchDataInArray($this -> ExecuteQuery($sqlQuery));
	}

	/** Получает список активных ip привязанных к порту клиента, необходимо при отключение всех ip при отключение порта у клиента
	 * @param $clientID - UID клиента
	 * @param $vlanID - UID vlan
	 * @param $status (on/off)
	 * @return array
	 */
	public function GetListActiveIpByVlan($vlanID, $clientID, $status = 'on') {
		$sqlQuery = "SELECT * FROM IP_ADRESS AS all_ip,
(SELECT UID, MAX(date_last_update) AS date FROM IP_ADRESS
GROUP BY UID) AS ip_last_update
WHERE all_ip.UID = ip_last_update.UID AND all_ip.date_last_update = ip_last_update.date AND all_ip.vlan_ID = ".$vlanID." AND all_ip.client_ID = ".$clientID." AND all_ip.status = '$status' ORDER BY id DESC";

		return $this -> FetchDataInArray($this -> ExecuteQuery($sqlQuery));
	}

	/** Выполняет любой запрос
	 * $query - запрос
	 */
	public function ExecuteQuery($query) {
		return mysql_query($query, $this -> CreateConnect());
	}

}