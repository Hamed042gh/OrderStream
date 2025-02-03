<?php

namespace App\Listeners\Order;

use App\Events\Order\DeleteOrderEvent;
use App\Jobs\Order\StoreDeleteOrderJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreDeleteOrderEvent
{
 
    public function handle(DeleteOrderEvent $event)
    {
  
        StoreDeleteOrderJob::dispatch($event->orderId);
    }
}
