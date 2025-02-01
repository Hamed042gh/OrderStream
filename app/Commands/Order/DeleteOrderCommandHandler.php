<?php
namespace App\Commands\Order;

use App\Models\Order;

class DeleteOrderCommandHandler
{
    public function handle(DeleteOrderCommand $command)
    {
        //Finding order by ID from database
        $order = Order::findOrFail($command->orderId);

        //Remove order from database
        $order->delete();

        return $order;
    }
}
