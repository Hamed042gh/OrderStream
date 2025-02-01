<?php
namespace App\Commands\Order;

use App\Models\Order;

class CreateOrderCommandHandler
{
    public function handle(CreateOrderCommand $command)
    {
        // CreateOrderCommandHandler
        return Order::create($command->orderData);
    }
}
