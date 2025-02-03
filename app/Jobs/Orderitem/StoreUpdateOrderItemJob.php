<?php

namespace App\Jobs\Orderitem;

use App\Jobs\Base\BaseCreateJob;
use App\Repositories\EventStoreRepository;
use Illuminate\Support\Facades\Log;

class StoreUpdateOrderItemJob extends BaseCreateJob
{
    
    public function handle(EventStoreRepository $eventStore)
    {

        if (empty($this->eventDataArray['changed_attributes'])) {
            Log::info("No changes detected for Orderitem ID: {$this->entityId}");
            return;
        }

        $eventStore->storeEvent($this->eventDataArray, $this->eventType, $this->entityType, $this->entityId);
    }
}
