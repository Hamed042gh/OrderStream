<?php

namespace App\Commands\Product;

class UpdateProductCommand
{
    public $productData;
    public $id;


    public function __construct($productData,$id)
    {
        $this->productData = $productData;
        $this->id = $id;
    }
}
