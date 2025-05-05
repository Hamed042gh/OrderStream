<?php

namespace App\Queries\OrderItem;

class GetOrderItemQuery
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}
