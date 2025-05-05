<?php

namespace App\Listeners\Order;

use App\Events\Order\UpdateOrderEvent;
use App\Jobs\Order\StoreUpdateOrderJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreUpdateOrderEvent
{
    // Constructor is no longer needed for EventStoreRepository
    // because we will pass data to the job

    public function handle(UpdateOrderEvent $event)
    {

        // Convert JSON to array
        $eventDataArray = json_decode($event->event_data, true);

        // Dispatch the job to store the event data
        StoreUpdateOrderJob::dispatch($eventDataArray, $event->event_type, $event->entity_type, $event->entity_id);
    }
}
