<?php

namespace source\JsonModels;
class Product
{
    public $id, $name, $price;


    public function __construct($id, $name, $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }


    public function toProductArray()
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price
        ];
    }
}