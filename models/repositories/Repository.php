<?php


namespace app\models\repositories;


use app\models\Record;
use app\base\Application;

abstract class Repository
{
    protected $db;
    protected $tableName;

    public function __construct()
    {
        $this->db = Application::getInstance()->db;
        $this->tableName = $this->getTableName();
    }

    public function getLimit(int $count, int $notesOnPage, ?string $search, $orderBy, $rang)
    {   $tableName = $this->getTableName();
        $from = ($count - 1) * $notesOnPage;
         $sql = $search === null ?
            $sql = "SELECT id, name, email, text, LEFT(date, 16) AS date FROM {$tableName} ORDER BY {$orderBy} {$rang} LIMIT {$from} , {$notesOnPage}":
            $sql = "SELECT id, name, email, text, LEFT(date, 16) AS date FROM {$tableName} WHERE (name LIKE :search OR email LIKE :search OR text LIKE :search) ORDER BY {$orderBy} {$rang} LIMIT {$from}, {$notesOnPage}";
            $params = array(':search' => '%' . $search . '%');
        return  $this->getQuery($sql, $search ? $params: []);
        
            
    }
        
    public function getCountOfSearch( string $search)
    {   $tableName = $this->getTableName();
        $sql = "SELECT COUNT(*) as countItems FROM {$tableName} WHERE (name LIKE :search OR email LIKE :search OR text LIKE :search)";
            $params = array(':search' => '%' . $search . '%');
        return  $this->getQuery($sql, $params);
        
            
    } 

    public function getCountOfItems()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT COUNT(*) as countItems FROM {$tableName}";
        return  $this->getQuery($sql, []);
    }


    protected function getQuery($sql, $params = [])
    {
        return Application::getInstance()->db->queryAll($sql, $params, $this->getRecordClassname());
    }

   
    abstract public function getTableName(): string;

    abstract public function getRecordClassname(): string;



    

    public function getById(int $id)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE id = :id";
        return  $this->getQuery($sql, [':id' => $id])[0];
    }

    public function getByIds(array $productIds)
    {
        $table = $this->getTableName();
        $where = implode(', ', $productIds);
        $sql = "SELECT * FROM {$table} WHERE id IN ({$where})";
        return $this->getQuery($sql);
    }

    public function delete(Record $record)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE id = :id";
        return $this->db->execute($sql, [':id' => $record->id]);
    }

    public function insert(array $arr)
    {
        $tableName = $this->getTableName();

        $params = [];
        $columns = [];

        foreach ($arr as $key => $value) {
            $params[":{$key}"] = $value;
            $columns[] = "`{$key}`";
        }

        $columns = implode(", ", $columns);
        $placeholders = implode(", ", array_keys($params));
        $sql = "INSERT INTO {$tableName} ({$columns}) VALUES ({$placeholders})";
        $this->db->execute($sql, $params);
        return $this->db->getLastInsertId();        
    }


    public function update(Record $record)
    {
        $tableName = $this->getTableName();
        $basketData = [];
        $basketData = $_SESSION['basket'];
        $arrayData = array($this);
        $newArray = array_diff_assoc($basketData, $arrayData);
        if (isset($newArray)) {
            $params = [];
            $columns = [];
            $updateFields = [];

            foreach ($newArray as $key => $value) {

                $params[":{$key}"] = $value;
                $columns[] = "`{$key}`";
                $updateFields[] = "{$key} = {$value}";
            }

            $columns = implode(", ", $columns);
            $placeholders = implode(", ", array_keys($params));
            $updateFields = implode(",", $updateFields);


            $sql = "UPDATE {$tableName} ({$updateFields}) WHERE id = :id";
            $this->db->execute($sql, [':id' => $this->id]);
        }
    }

    // public function save(Record $record)
    // {
    //     if (is_null($record->id)) {
    //         $this->insert($record);
    //     } else {
    //         $this->update($record);
    //     }
    // }

 
}
