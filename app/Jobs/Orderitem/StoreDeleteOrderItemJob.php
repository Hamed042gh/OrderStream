<?php

namespace App\Jobs\Orderitem;

use App\Repositories\EventStoreRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class StoreDeleteOrderItemJob implements ShouldQueue
{
    use Queueable;

    protected $orderitemId;

    public function __construct($orderitemId)
    {
        $this->orderitemId = $orderitemId;
    }

    public function handle(EventStoreRepository $eventStore)
    {
        $eventStore->storeEvent(
            ['orderitem_id' => $this->orderitemId],
            'DeleteOrderitem',
            'Orderitem',
            $this->orderitemId
        );
    }
}
