<?php

namespace App\Jobs\Product;

use App\Jobs\Base\BaseCreateJob;
use App\Repositories\EventStoreRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Validator;

class StoreCreateProductJob extends BaseCreateJob
{
    public function handle(EventStoreRepository $eventStore)
    {
       
        // Validate data
        $validator = Validator::make($this->eventDataArray, [
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required',
        ]);
       
        if ($validator->fails()) {
            return $validator->errors();
        }

        // Store event data using the repository
        $eventStore->storeEvent($this->eventDataArray, $this->eventType, $this->entityType,$this->entityId);
    }
}
