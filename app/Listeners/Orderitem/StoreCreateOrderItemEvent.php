<?php

namespace App\Listeners\Orderitem;

use App\Events\Orderitem\CreateOrderItemEvent;
use App\Jobs\Orderitem\StoreCreateOrderItemJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreCreateOrderItemEvent
{
    public function handle(CreateOrderitemEvent $event)
    {
        
        // Convert JSON to array
        $eventDataArray = json_decode($event->event_data, true);

        // Dispatch the job to store the event data
        StoreCreateOrderItemJob::dispatch($eventDataArray,$event->event_type, $event->entity_type, $event->entity_id);
    }
}
