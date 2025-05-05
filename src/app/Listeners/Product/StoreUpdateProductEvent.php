<?php

namespace App\Listeners\Product;

use App\Events\Product\UpdateProductEvent;
use App\Jobs\Product\StoreUpdateProductJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreUpdateProductEvent
{    // Constructor is no longer needed for EventStoreRepository
    // because we will pass data to the job

    public function handle(UpdateProductEvent $event)
    {
        // Convert JSON to array
        $eventDataArray = json_decode($event->event_data, true);

        // Dispatch the job to store the event data
        StoreUpdateProductJob::dispatch($eventDataArray, $event->event_type, $event->entity_type, $event->entity_id);
    }
}
