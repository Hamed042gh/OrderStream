<?php

namespace App\Listeners\OrderItem;

use App\Events\Orderitem\DeleteOrderItemEvent;
use App\Jobs\Orderitem\StoreDeleteOrderItemJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreDeleteOrderItemEvent
{
    public function handle(DeleteOrderItemEvent $event)
    {
  
        StoreDeleteOrderItemJob::dispatch($event->orderitemId);
    }
}
