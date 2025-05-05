<?php

namespace App\Commands\Handlers\Product;

use App\Commands\Product\DeleteProductCommand;
use App\Models\Product;

class DeleteProductCommandHandler
{
    public function handle(DeleteProductCommand $command)
    {
        $product = Product::findOrFail($command->id);
        if ($product) {
            $product->delete();
        }
    }
}
