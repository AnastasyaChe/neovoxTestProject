<?php

namespace app\interfaces;

interface ModelInterface
{
    static function getAll();

    static function getById(int $id);

    static function getTableName(): string;

    // function save();
}
