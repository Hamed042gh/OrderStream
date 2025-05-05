<?php

namespace App\Jobs\Order;

use App\Jobs\Base\BaseCreateJob;
use App\Repositories\EventStoreRepository;
use Illuminate\Support\Facades\Validator;

class StoreCreateOrderJob extends BaseCreateJob
{
    public function handle(EventStoreRepository $eventStore)
    {

        // Validate data
        $validator = Validator::make($this->eventDataArray, [
            'user_id' => 'required|integer',
            'status' => 'required',
            'total_price' => 'required|numeric',
        ]);

        if ($validator->fails()) {

            return $validator->errors();
        }

        // Store event data using the repository
        $eventStore->storeEvent($this->eventDataArray, $this->eventType, $this->entityType, $this->entityId);
    }
}
