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


    /**
     * getLimit
     *
     * @param  mixed $count
     * @param  mixed $notesOnPage
     * @param  mixed $search
     * @param  mixed $orderBy
     * @param  mixed $rang
     * @return array
     */
    public function getLimit(int $count, int $notesOnPage, ?string $search, $orderBy, $rang)
    {
        $tableName = $this->getTableName();
        $from = ($count - 1) * $notesOnPage;
        $sql = $search === null ?
            $sql = "SELECT id, name, email, text, user_id, LEFT(date, 16) AS date FROM {$tableName} ORDER BY {$orderBy} {$rang} LIMIT {$from} , {$notesOnPage}" :
            $sql = "SELECT id, name, email, text, LEFT(date, 16) AS date FROM {$tableName} WHERE (name LIKE :search OR email LIKE :search OR text LIKE :search) ORDER BY {$orderBy} {$rang} LIMIT {$from}, {$notesOnPage}";
        $params = array(':search' => '%' . $search . '%');
        return  $this->getQuery($sql, $search ? $params : []);
    }

    /**
     * getCountOfSearch
     *
     * @param  mixed $search
     * @return array
     */
    public function getCountOfSearch(string $search)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT COUNT(*) as countItems FROM {$tableName} WHERE (name LIKE :search OR email LIKE :search OR text LIKE :search)";
        $params = array(':search' => '%' . $search . '%');
        return  $this->getQuery($sql, $params);
    }

    /**
     * getCountOfItems
     *
     * @return array
     */
    public function getCountOfItems()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT COUNT(*) as countItems FROM {$tableName}";
        return  $this->getQuery($sql, []);
    }

    /**
     * getById
     *
     * @param  mixed $id
     * @return array
     */
    public function getById(int $id)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE id = :id";
        return  $this->getQuery($sql, [':id' => $id])[0];
    }

    /**
     * getQuery
     *
     * @param  mixed $sql
     * @param  mixed $params
     * @return array
     */
    protected function getQuery($sql, $params = [])
    {
        return Application::getInstance()->db->queryAll($sql, $params, $this->getRecordClassname());
    }


    abstract public function getTableName(): string;

    abstract public function getRecordClassname(): string;


    /**
     * delete
     *
     * @param  mixed $record
     * @return int
     */
    public function delete(Record $record)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE id = :id";
        return $this->db->execute($sql, [':id' => $record->id]);
    }

    /**
     * insert
     *
     * @param  mixed $array
     * @return int
     */
    public function insert($array)
    {
        $tableName = $this->getTableName();

        $params = [];
        $columns = [];

        foreach ($array as $key => $value) {
            $params[":{$key}"] = $value;
            $columns[] = "`{$key}`";
        }

        $columns = implode(", ", $columns);
        $placeholders = implode(", ", array_keys($params));
        $sql = "INSERT INTO {$tableName} ({$columns}) VALUES ({$placeholders})";
        $this->db->execute($sql, $params);
        return $this->db->getLastInsertId();
    }

    /**
     * insertImages
     *
     * @param  mixed $lastInsertId
     * @param  mixed $filename
     * @return int
     */
    public function insertImages($lastInsertId, $filename)
    {
        $tableName = $this->getTableName();
        $sql = "INSERT INTO {$tableName} SET `user_id` = ?, `filename` = ?";
        $this->db->execute($sql, array($lastInsertId, $filename));
        return $this->db->getLastInsertId();
    }

    /**
     * update
     *
     * @param  mixed $user
     * @return int
     */
    public function update(array $user)
    {
        $tableName = $this->getTableName();
        $userData = $_SESSION['user'];
        $newArray = array_diff_assoc($user, $userData);
        if (isset($newArray)) {
            $params = [];
            foreach ($newArray as $key => $value) {
                $param[] = "{$key} = :{$key}";
                $params["{$key}"] = $value;
            }
            $params['id'] = $userData['id'];
            $param = implode(",", $param);
            $sql = "UPDATE {$tableName} SET {$param} WHERE id = :id";
            $this->db->execute($sql, $params);
        }
    }
}
