<?php

namespace App\Jobs;

use App\Repositories\EventStoreRepository;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Validator;

class StoreCreateOrderJob implements ShouldQueue
{
    use Queueable;
    protected $eventDataArray;
    protected $eventType;
    protected $entityType;

    public function __construct($eventDataArray, $eventType, $entityType)
    {
        $this->eventDataArray = $eventDataArray;
        $this->eventType = $eventType;
        $this->entityType = $entityType;
    }

    public function handle(EventStoreRepository $eventStore)
    {
        // Validate data
        $validator = Validator::make($this->eventDataArray, [
            'user_id' => 'required|integer',
            'order_id' => 'required|integer',
            'total_price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            // Handle validation errors
            // For now, you can log the error or notify
            // Here we return errors, but you could choose to handle them differently
            return $validator->errors();
        }

        // Store event data using the repository
        $eventStore->storeEvent($this->eventDataArray, $this->eventType, $this->entityType);
    }
}
