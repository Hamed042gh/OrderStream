<?php
namespace App\Commands\Orderitem;

use App\Models\Orderitem;

class DeleteOrderitemCommandHandler
{
    public function handle(DeleteOrderitemCommand $command)
    {
        //Finding order by ID from database
        $orderitem = Orderitem::findOrFail($command->orderitemId);

        //Remove order from database
        $orderitem->delete();

        return $orderitem;
    }
}
