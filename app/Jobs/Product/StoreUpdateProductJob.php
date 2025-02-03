<?php

namespace App\Jobs\Product;

use App\Jobs\Base\BaseCreateJob;
use App\Repositories\EventStoreRepository;
use Illuminate\Support\Facades\Log;

class StoreUpdateProductJob extends BaseCreateJob
{
   
    public function handle(EventStoreRepository $eventStore)
    {

        if (empty($this->eventDataArray['changed_attributes'])) {
            Log::info("No changes detected for Product ID: {$this->entityId}");
            return;
        }

        $eventStore->storeEvent($this->eventDataArray, $this->eventType, $this->entityType, $this->entityId);
    }
}
