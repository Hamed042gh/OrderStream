<?php

namespace App\Listeners\Product;

use App\Events\Product\DeleteProductEvent;
use App\Jobs\Product\StoreDeleteProductJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreDeleteProductEvent
{
    public function handle(DeleteProductEvent $event)
    {
  
        StoreDeleteProductJob::dispatch($event->productId);
    }
}
