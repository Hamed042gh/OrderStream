<?php

namespace App\Commands\Product;

use App\Commands\Product\CreateProductCommand;
use App\Models\Product;

class CreateProductCommandHandler
{
    public function handle(CreateProductCommand $command)
    {
        $product = new Product();
        $product->name = $command->name;
        $product->price = $command->price;
        $product->stock = $command->stock;
        $product->save();

        return $product;
    }
}
