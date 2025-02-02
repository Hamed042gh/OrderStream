<?php

namespace App\Jobs;

use App\Repositories\EventStoreRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Validator;

class StoreCreateProductJob implements ShouldQueue
{
    use Queueable;
    protected $eventDataArray;
    protected $eventType;
    protected $entityType;
    protected $entityId;

    public function __construct($eventDataArray, $eventType, $entityType,$entityId)
    {
        $this->eventDataArray = $eventDataArray;
        $this->eventType = $eventType;
        $this->entityType = $entityType;
        $this->entityId = $entityId;
       
        
    }

    public function handle(EventStoreRepository $eventStore)
    {
       
        // Validate data
        $validator = Validator::make($this->eventDataArray, [
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required',
        ]);
       
        if ($validator->fails()) {
            // Handle validation errors
            // For now, you can log the error or notify
            // Here we return errors, but you could choose to handle them differently
            return $validator->errors();
        }

        // Store event data using the repository
        $eventStore->storeEvent($this->eventDataArray, $this->eventType, $this->entityType,$this->entityId);
    }
}
