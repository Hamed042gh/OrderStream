<?php

namespace App\Jobs\Order;

use App\Repositories\EventStoreRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StoreUpdateOrderJob implements ShouldQueue
{
    use Queueable;
    protected $eventDataArray;
    protected $eventType;
    protected $entityType;
    protected $entityId;

    public function __construct($eventDataArray, $eventType, $entityType, $entityId)
    {
        $this->eventDataArray = $eventDataArray;
        $this->eventType = $eventType;
        $this->entityType = $entityType;
        $this->entityId = $entityId;
    }

    public function handle(EventStoreRepository $eventStore)
    {

        if (empty($this->eventDataArray['changed_attributes'])) {
            Log::info("No changes detected for Order ID: {$this->entityId}");
            return;
        }

        $eventStore->storeEvent($this->eventDataArray, $this->eventType, $this->entityType, $this->entityId);
    }
}
