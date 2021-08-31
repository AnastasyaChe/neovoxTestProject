<?php

namespace app\services;

class Db
{
    public $config;

    public function __construct($driver, $host, $login, $password, $database, $charset)
    {
        $this->config = [
            'driver' => $driver,
            'host' => $host,
            'login' => $login,
            'password' => $password,
            'database' => $database,
            'charset' => $charset
        ];
    }


    private $connection = null;

    protected function getConnection()
    {
        if (is_null($this->connection)) {
            $this->connection = new \PDO(
                $this->buildDsnString(),
                $this->config['login'],
                $this->config['password']
            );

            $this->connection->setAttribute(
                \PDO::ATTR_DEFAULT_FETCH_MODE,
                \PDO::FETCH_ASSOC,

            );
        }


        return $this->connection;
    }




    /**
     * query
     *
     * @param  mixed $sql
     * @param  mixed $params
     * @return object
     */
    private function query(string $sql, array $params = [])
    {
        $pdoStatement = $this->getConnection()->prepare($sql);
        $pdoStatement->execute($params);
        return $pdoStatement;
    }
    /**
     * searchQuery
     *
     * @param  mixed $sql
     * @param  mixed $params
     * @return object
     */
    private function searchQuery(string $sql, array $params = [])
    {
        $pdoStatement = $this->getConnection()->prepare($sql);
        $res = $pdoStatement->execute($params);
        return $pdoStatement;
    }

    /**
     * queryOne
     *
     * @param  mixed $sql
     * @param  mixed $params
     * @param  mixed $className
     * @return array
     */
    public function queryOne(string $sql, array $params = [], string $className = null)
    {
        return $this->queryAll($sql,  $params, $className)[0];
    }

    /**
     * queryAll
     *
     * @param  mixed $sql
     * @param  mixed $params
     * @param  mixed $className
     * @return object
     */
    public function queryAll(string $sql, array $params = [], string $className = null)
    {
        $pdoStatement = $this->query($sql,  $params);
        if (isset($className)) {
            $pdoStatement->setFetchMode(
                \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE,
                $className
            );
        }
        return $pdoStatement->fetchAll();
    }
    /**
     * querySearch
     *
     * @param  mixed $sql
     * @param  mixed $params
     * @param  mixed $className
     * @return object
     */
    public function querySearch($sql, array $params, string $className = null)
    {
        $pdoStatement = $this->searchQuery($sql,  $params);
        if (isset($className)) {
            $pdoStatement->setFetchMode(
                \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE,
                $className
            );
        }
        return $pdoStatement->fetchAll();
    }

    /**
     * execute
     *
     * @param  mixed $sql
     * @param  mixed $params
     * @return int
     */
    public function execute(string $sql, array $params = []): int
    {
        return $this->query($sql, $params)->rowCount();
    }

    /**
     * getLastInsertId
     *
     * @return int
     */
    public function getLastInsertId()
    {
        return $this->getConnection()->lastInsertId();
    }


    private function buildDsnString()
    {
        return sprintf(
            '%s:host=%s;dbname=%s;charset=%s',
            $this->config['driver'],
            $this->config['host'],
            $this->config['database'],
            $this->config['charset'],
        );
    }
}
