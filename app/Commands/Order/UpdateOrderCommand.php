<?php
namespace App\Commands\Order;

class UpdateOrderCommand
{
    public $orderId;
    public $orderData;

    public function __construct($orderId, $orderData)
    {
        $this->orderId = $orderId;
        $this->orderData = $orderData;
    }
}
