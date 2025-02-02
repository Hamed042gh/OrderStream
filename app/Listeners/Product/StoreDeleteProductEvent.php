<?php

namespace App\Listeners\Product;

use App\Events\Product\DeleteProductEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreDeleteProductEvent
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DeleteProductEvent $event): void
    {
        //
    }
}
