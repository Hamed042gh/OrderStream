<?php

namespace App\Jobs\Orderitem;

use App\Jobs\Base\BaseCreateJob;
use App\Repositories\EventStoreRepository;
use Illuminate\Support\Facades\Validator;

class StoreCreateOrderItemJob extends BaseCreateJob
{
    public function handle(EventStoreRepository $eventStore)
    {
        // Validate data
        $validator = Validator::make($this->eventDataArray, [
            'order_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'unit_price' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        // Store event data using the repository
        $eventStore->storeEvent($this->eventDataArray, $this->eventType, $this->entityType, $this->entityId);
    }
}
