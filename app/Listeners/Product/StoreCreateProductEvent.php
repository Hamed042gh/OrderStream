<?php

namespace App\Listeners\Product;

use App\Events\Product\CreateProductEvent;
use App\Jobs\StoreCreateProductJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreCreateProductEvent
{
    // Constructor is no longer needed for EventStoreRepository
    // because we will pass data to the job

    public function handle(CreateProductEvent $event)
    {
        
        // Convert JSON to array
        $eventDataArray = json_decode($event->event_data, true);

        // Dispatch the job to store the event data
        StoreCreateProductJob::dispatch($eventDataArray,$event->event_type, $event->entity_type, $event->entity_id);
    }
}
