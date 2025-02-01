<?php

namespace App\Queries\Order;

class GetOrderQuery
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}
