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
	
	private function FetchDataInArray($result) {
		$arr = array();
		$count = 0;

		while($row = mysql_fetch_array($result))
		{
			$arr[$count] = $row;
			$count++;
		}

		return $arr;
	}
	
	public function GetListItems($tbl, $filter_field, $filter_value, $order_field='id', $order_type='ASC', $start=0, $limit=null) {
		if($filter_field === null):
			 $this -> sqlQuery = "SELECT * FROM $tbl ORDER BY $order_field $order_type";
		else:
			if($limit == null) {
				$this -> sqlQuery = "SELECT * FROM $tbl WHERE $filter_field=$filter_value ORDER BY $order_field $order_type";
			} else {
				$this -> sqlQuery = "SELECT * FROM $tbl WHERE $filter_field=$filter_value ORDER BY $order_field $order_type LIMIT $start, $limit";
			}
		endif;

		$data = mysql_query($this -> sqlQuery, $this -> CreateConnect());
		return $this -> FetchDataInArray($data);
	}

	/** 
	 * Получает сгруппированные данные по date_stamp
	 * $field_date - поле, по которому выбирается самая новая запись
	 */
	public function GetGroupedListItems($tbl,$field_date) {
		$this -> sqlQuery = "SELECT * FROM $tbl AS res, 
		(SELECT value, MAX($field_date) AS date FROM $tbl 
		GROUP BY value) AS res2 
		WHERE res.value = res2.value AND res.$field_date = res2.date 
		ORDER BY id DESC";

		$data = mysql_query($this -> sqlQuery, $this -> CreateConnect());
		return $this -> FetchDataInArray($data);
	}

	/** 
	* Получает список без повторов
	*/
	public function GetListItemsNoClone($tbl, $field) {
		$this -> sqlQuery = "SELECT DISTINCT $field FROM $tbl";

		$data = mysql_query($this -> sqlQuery, $this -> CreateConnect());
		return $this -> FetchDataInArray($data);
	}
	
	/** 
	* Количество уникальных записей
	*/
	public function GetCountUniqueItems($tbl, $field) {
		$this -> sqlQuery = "SELECT COUNT(DISTINCT $field) AS quantity FROM $tbl";
 
		$data = mysql_query($this -> sqlQuery, $this -> CreateConnect());
		return $this -> FetchDataInArray($data);
	}

	/** Выборка одного элемента
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
		$this -> sqlQuery = "SELECT COUNT(*) FROM $tbl";

		$data = mysql_query($this -> sqlQuery, $this -> CreateConnect());
		$row = mysql_fetch_array($data);

		return $row;
	}

	public function foo() {
		echo 'foo';
	}

}