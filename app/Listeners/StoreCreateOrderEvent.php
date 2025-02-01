<?php

namespace App\Listeners;

use App\Events\Order\CreateOrderEvent;
use App\Jobs\StoreCreateOrderJob;


class StoreCreateOrderEvent
{
    // Constructor is no longer needed for EventStoreRepository
    // because we will pass data to the job

    public function handle(CreateOrderEvent $event)
    {
        // Convert JSON to array
        $eventDataArray = json_decode($event->event_data, true);

        // Dispatch the job to store the event data
        StoreCreateOrderJob::dispatch($eventDataArray, 'CreateOrder', 'Order');
    }
}
