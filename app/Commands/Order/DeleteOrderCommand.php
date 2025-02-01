<?php
namespace App\Commands\Order;

class DeleteOrderCommand
{
    public $orderId;

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }
}
