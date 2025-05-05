<?php

namespace App\Commands\Product;

class CreateProductCommand
{
    public $name;
    public $price;
    public $stock;

    public function __construct(array $productData)
    {
        $this->name = $productData['name'];
        $this->price = $productData['price'];
        $this->stock = $productData['stock'];
    }
}
