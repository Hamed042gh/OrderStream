<?php

namespace App\Listeners\Orderitem;

use App\Events\Orderitem\UpdateOrderItemEvent;
use App\Jobs\Orderitem\StoreUpdateOrderItemJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreUpdateOrderItemEvent
{
    public function handle(UpdateOrderitemEvent $event)
    {

        // Convert JSON to array
        $eventDataArray = json_decode($event->event_data, true);

        // Dispatch the job to store the event data
        StoreUpdateOrderItemJob::dispatch($eventDataArray, $event->event_type, $event->entity_type, $event->entity_id);
    }
}
