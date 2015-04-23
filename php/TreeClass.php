<?PHP
//Определяем класс Node отвечающий за хранение данных узла дерева
//$id - идентификатор узла, $name - имя узла, $is_node - файл или папка, 
//$is_open - открыт или закрыт узел, $index_node - положение узла относительно родительского эелемента, $level - уровень вложенности;

class Node {

    public $id, $name, $is_node, $is_open, $index_node, $level;

    public function __construct($id, $name, $is_node, $is_open, $index_node, $level) {
        $this->id = $id;
        $this->name = $name;
        $this->is_node = $is_node;
        $this->is_open = $is_open;
        $this->index_node = $index_node;
        $this->level = $level;
    }
}

//Определяем класс Tree отвечающий за обработку данных дерева
class Tree {
    
    private $refs = array(), $tree = array();
    
    //Определяем конструктор
    function __construct() {
        //Задаем Root узел дерева
        $node = new Node(0, "Ext JS", 1, 1, 0, 0);
        $this->tree[] = $node;
        $this->refs[0] = &$node;
    }
    
    //Функция для помещения узла дерева в массив
    function AppendChild($id, $name, $is_node, $is_open, $parent_id, $index_node, $level) {
        if (isset($this->refs[$parent_id])) {

            $ref = $this->refs[$parent_id];
            $node = new Node($id, $name, $is_node, $is_open, $index_node, $level);
            $ref->children[$node->index_node] = $node;            
            ksort($ref->children);
            $this->refs[$id] = &$node;
        }
    }
    //Получаем готовую структуру дерева
    function Get() {
        return $this->tree;
    }
    //Собираем запрос в рекурсивной функции обновления полей в базе данных при сохранении структуры дерева 
    function SetQueryToDB($arr, &$query) {
        foreach ($arr as $a) {
            if (is_array($a)) {
                if ($a['name'] != '') {
                    $query.="UPDATE jqtree_data SET `is_open` = " . $a['is_open'] . ", `parent_id` = " . $a['parent_id'] . ", `index_node` = " . $a['index_node'] . ", `level` = " . $a['level'] . " WHERE `id` = " . $a['id'] . "; ";
                }
                $this->SetQueryToDB($a, $query);
            } else {
                
            }
        }
    }
    //Передаем запрос для выполнения 
    function SaveTreeData($query) {
        try {
            $DBH = new PDO("mysql:host=" . $GLOBALS["host"] . ";dbname=" . $GLOBALS["dbName"], $GLOBALS["user"], $GLOBALS["pass"]);
            $STH = $DBH->prepare($query);
            $STH->execute();
            echo 'saved';
        } catch (PDOException $e) {
            echo 'error';
        }
    }   
    //Получаем данные из базы данных со структурой дерева    
    function GetTreeData() {
        try {
            $array = array();
            $DBH = new PDO("mysql:host=" . $GLOBALS["host"] . ";dbname=" . $GLOBALS["dbName"], $GLOBALS["user"], $GLOBALS["pass"]);
            $STH = $DBH->query('SELECT * FROM jqtree_data ORDER BY level');
            $STH->execute();
            $this->CreateJson($STH->fetchAll());
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    //Создаем Json объект для передачи клиенту
    function CreateJson($array) {
        for ($i = 0; $i < count($array); $i++) {
            $this->AppendChild($array[$i]['id'], $array[$i]['name'], $array[$i]['is_node'], ($array[$i]['is_open']==1)?true:false, $array[$i]['parent_id'], $array[$i]['index_node'], $array[$i]['level']);
        }
        echo (json_encode($this->Get()));
    }    

}

?>