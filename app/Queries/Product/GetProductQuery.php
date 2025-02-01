<?php

namespace App\Queries\Product;

class GetProductQuery
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}
