<?php
namespace App\Commands\Orderitem;

class CreateOrderitemCommand
{
    public $orderitemData;

    public function __construct($orderitemData)
    {
        $this->orderitemData = $orderitemData;
    }
}