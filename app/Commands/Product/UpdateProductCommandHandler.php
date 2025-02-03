<?php

namespace App\Commands\Product;

use App\Commands\Product\UpdateProductCommand;
use App\Models\Product;

class UpdateProductCommandHandler
{
    public function handle(UpdateProductCommand $command)
    {
        $product = Product::findOrFail($command->id);
    
        //Update database with new data
        $product->update($command->productData);

        return $product;
    }
    
}
