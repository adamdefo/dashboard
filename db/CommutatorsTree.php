<?php
/**
 * Посторение дерева коммутаторов
 * @author Алексей Кондратьев
 */
class CommutatorsTree {

    private $db = null;
    private $commutators = array();

    function __construct() {
        $this->db = new PDO("mysql:dbname=mello7_it;host=localhost", "mello7_it", "DK2929vb1");
        $this->commutators = $this->getAllCommutators();
    }

    private function getAllCommutators() {
        $query = $this->db->prepare("SELECT * FROM `COMMUTATORS` AS res,
							(SELECT UID, MAX(date_last_update) AS date FROM `COMMUTATORS`
							GROUP BY UID) AS res2
							WHERE res.UID = res2.UID AND res.date_last_update = res2.date");
        $query->execute();
        $queryResult = $query->fetchAll(PDO::FETCH_OBJ);

        $return = array();
        foreach ($queryResult as $value) {
            $return[$value->parent_ID][] = $value;
        }
        return $return;
    }

    /**
    * Вывод дерева
    * @param Integer $parent_id - id-родителя
    * @param Integer $level - уровень вложености
    */
    public function outTree($parent_ID, $level) {
        if (isset($this->commutators[$parent_ID])) { //Если категория с таким parent_id существует
            foreach ($this->commutators[$parent_ID] as $value) { //Обходим ее
                echo '<li class="commutators-tree__item"><a href="index.php?view=commutators&action=edit&id='.$value->id.'">'.$value->UID.'</a>';
                echo '<button class="btn btn-small btn-default commutators-tree__btn js-toggle-commutator" data-id="'.$value->id.'">'.$value->UID.'</button>';
                echo '<table class="commutators-tree__table">';
                echo '<tr class="commutators-tree__tr">';
                echo '<td>Модель</td>';
                echo '<td>IP</td>';
                echo '<td>Прошивка</td>';
                echo '<td>Адрес</td>';
                echo '<td>Сегмент</td>';
                echo '<td>Тип подключения</td>';
                echo '<td>Статус</td>';
                echo '<td>Дата изменения</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td>'.$value->model.'</td>';
                echo '<td>'.$value->ip.'</td>';
                echo '<td>'.$value->firmware.'</td>';
                echo '<td>'.$value->adress.'</td>';
                echo '<td>'.$value->segment.'</td>';
                echo '<td>'.$value->connection_type_ID.'</td>';
                echo '<td>'.$value->status_ID.'</td>';
                echo '<td>'.$value->date_last_update.'</td>';
                echo '</tr>';
                echo '</table>';
                echo '<div class="commutators-tree__info commutators-tree__info-'.$value->id.'"></div>';
                $level++;
                echo "<ul>";
                $this->outTree($value->UID, $level);
                echo '</li></ul>';
                $level--;
            }
        }
    }

    public function printLog() {
        echo '<pre>';
        print_r($this->commutators);
        echo '</pre>';
    }

}