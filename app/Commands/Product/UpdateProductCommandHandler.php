<?php

namespace App\Commands\Product;

use App\Commands\Product\UpdateProductCommand;
use App\Models\Product;

class UpdateProductCommandHandler
{
    public function handle(UpdateProductCommand $command)
    {
        $product = Product::findOrFail($command->id);
    
        $product->name = $command->productData['name'] ?? $product->name;
        $product->price = $command->productData['price'] ?? $product->price;
        $product->stock = $command->productData['stock'] ?? $product->stock;
    
        $product->save();
    
        return $product;
    }
    
}
