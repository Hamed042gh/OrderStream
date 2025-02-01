<?php
namespace App\Commands\Order;

class CreateOrderCommand
{
    public $orderData;

    public function __construct($orderData)
    {
        $this->orderData = $orderData;
    }
}
