<?php


namespace app\models\repositories;

use app\models\Orders;


class OrdersRepository extends Repository
{
    public function getTableName(): string
    {
        return "orders";
    }

    public function getRecordClassname(): string
    {
        return Orders::class;
    }

    public function getByCategoryId(int $categoryId)
    {
        $table = $this->getTableName();
        $sql = "SELECT * FROM {$table} WHERE category_id = :id";
        return $this->getQuery($sql, [':id' => $categoryId]);
    }
}