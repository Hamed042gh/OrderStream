<?php

namespace App\Commands\Product;

class DeleteProductCommand
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}
