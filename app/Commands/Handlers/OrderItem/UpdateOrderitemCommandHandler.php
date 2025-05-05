<?php
namespace App\Commands\Handlers\OrderItem;

use App\Models\Orderitem;

class UpdateOrderitemCommandHandler
{
    public function handle(UpdateOrderitemCommand $command)
    {
        //Finding order by ID from database
        $orderitem = Orderitem::findOrFail($command->orderitemId);

        //Update database with new data
        $orderitem->update($command->orderitemData);

        return $orderitem;
    }
}
