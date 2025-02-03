<?php
namespace App\Commands\Orderitem;

use App\Models\Orderitem;

class CreateOrderitemCommandHandler
{
    public function handle(CreateOrderitemCommand $command)
    {
        // CreateOrderitemCommandHandler
        $orderitem = new Orderitem();
        $orderitem->order_id = $command->orderitemData['order_id'];
        $orderitem->product_id = $command->orderitemData['product_id'];
        $orderitem->quantity = $command->orderitemData['quantity'];
        $orderitem->unit_price = $command->orderitemData['unit_price'];
    
        if (!$orderitem->save()) {
         
        }
    
        return $orderitem;
    }
}
