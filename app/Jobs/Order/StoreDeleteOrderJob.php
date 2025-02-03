<?php

namespace App\Jobs\Order;

use App\Repositories\EventStoreRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class StoreDeleteOrderJob implements ShouldQueue
{
    use Queueable;

    protected $orderId;

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    public function handle(EventStoreRepository $eventStore)
    {
        $eventStore->storeEvent(
            ['order_id' => $this->orderId],
            'DeleteOrder',
            'Order',
            $this->orderId
        );
    }
}
