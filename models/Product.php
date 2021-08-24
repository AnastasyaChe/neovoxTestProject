<?php

namespace app\models;

class Product extends Record
{
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;

    public function getShortDescription()
    {
        
    }
}
