<?php
namespace App\Commands\Handlers\Order;

use App\Commands\Order\CreateOrderCommand;
use App\Models\Order;

class CreateOrderCommandHandler
{
    public function handle(CreateOrderCommand $command)
    {
        // CreateOrderCommandHandler
        return Order::create($command->orderData);
    }
}
