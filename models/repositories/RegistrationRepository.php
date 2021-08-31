<?php


namespace app\models\repositories;

use app\models\Registration;


class RegistrationRepository extends Repository
{    
    /**
     * getTableName
     *
     * @return string
     */
    public function getTableName(): string
    {
        return "registration";
    }
    
    /**
     * getRecordClassname
     *
     * @return string
     */
    public function getRecordClassname(): string
    {
        return Registration::class;
    }
    
    /**
     * getByLogin
     *
     * @param  mixed $login
     * @param  mixed $password
     * @return object
     */
    public function getByLogin($login, $password) 
    {
        $table = $this->getTableName();
        $sql = "SELECT * FROM {$table} WHERE (login = :login AND password = :password)";
        return $this->getQuery($sql, [':login' => $login, ':password' => $password ])[0]; 
    }
}