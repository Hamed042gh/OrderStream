<?php

namespace App\Jobs\Product;

use App\Repositories\EventStoreRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class StoreDeleteProductJob implements ShouldQueue
{
    use Queueable;

    protected $productId;

    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    public function handle(EventStoreRepository $eventStore)
    {
        $eventStore->storeEvent(
            ['product_id' => $this->productId],
            'DeleteProduct',
            'Product',
            $this->productId
        );
    }
}
