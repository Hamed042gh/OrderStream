<?php

namespace App\Jobs\Order;

use App\Jobs\Base\BaseCreateJob;
use App\Repositories\EventStoreRepository;
use Illuminate\Support\Facades\Log;


class StoreUpdateOrderJob extends BaseCreateJob
{
   
    public function handle(EventStoreRepository $eventStore)
    {

        if (empty($this->eventDataArray['changed_attributes'])) {
            Log::info("No changes detected for Order ID: {$this->entityId}");
            return;
        }

        $eventStore->storeEvent($this->eventDataArray, $this->eventType, $this->entityType, $this->entityId);
    }
}
