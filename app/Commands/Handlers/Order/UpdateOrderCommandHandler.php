<?php
namespace App\Commands\Handlers\Order;

use App\Models\Order;

class UpdateOrderCommandHandler
{
    public function handle(UpdateOrderCommand $command)
    {
        //Finding order by ID from database
        $order = Order::findOrFail($command->orderId);

        //Update database with new data
        $order->update($command->orderData);

        return $order;
    }
}
