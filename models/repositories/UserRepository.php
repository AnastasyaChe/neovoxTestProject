<?php


namespace app\models\repositories;



use app\models\User;

class UserRepository extends Repository
{
    /**
     * getTableName
     *
     * @return string
     */
    public function getTableName(): string
    {
        return "users";
    }

    /**
     * getRecordClassname
     *
     * @return string
     */
    public function getRecordClassname(): string
    {
        return User::class;
    }
    public function updateText($user)
    {
        $tableName = $this->getTableName();
        $sql = "UPDATE {$tableName} SET text = :text WHERE id = :id";
        $this->db->execute($sql, ['text' => $user['text'], 'id' => $user['id']]);
    }
}
