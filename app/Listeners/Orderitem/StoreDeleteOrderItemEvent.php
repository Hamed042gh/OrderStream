<?php

namespace App\Listeners\Orderitem;

use App\Events\Orderitem\DeleteOrderItemEvent;
use App\Jobs\Orderitem\StoreDeleteOrderItemJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreDeleteOrderItemEvent
{
    public function handle(DeleteOrderitemEvent $event)
    {
  
        StoreDeleteOrderItemJob::dispatch($event->orderitemId);
    }
}
