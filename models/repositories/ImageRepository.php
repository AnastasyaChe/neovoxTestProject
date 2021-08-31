<?php


namespace app\models\repositories;

use app\models\Image;


class ImageRepository extends Repository
{
    /**
     * getTableName
     *
     * @return string
     */
    public function getTableName(): string
    {
        return "images";
    }

    /**
     * getRecordClassname
     *
     * @return string
     */
    public function getRecordClassname(): string
    {
        return Image::class;
    }

    /**
     * getByUserId
     *
     * @param  mixed $userId
     * @return object
     */
    public function getByUserId(int $userId)
    {
        $table = $this->getTableName();
        $sql = "SELECT * FROM {$table} WHERE user_id = :id";
        return $this->getQuery($sql, [':id' => $userId]);
    }
    /**
     * deleteImage
     *
     * @param  mixed $idImg
     * @return int
     */
    public function deleteImage($idImg)
    {
        $table = $this->getTableName();
        $sql = "DELETE * FROM {$table} WHERE id = :idImg";
        return $this->db->execute($sql, [':id' => $idImg]);
    }
}
