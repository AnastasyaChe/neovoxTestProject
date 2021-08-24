<?php


namespace app\models\repositories;


use app\models\Product;
use app\models\User;

class UserRepository extends Repository
{
    public function getTableName(): string
    {
        return "users";
    }

    public function getRecordClassname(): string
    {
        return User::class;
    }

}