<?php

namespace App\Listeners\Product;

use App\Events\Product\UpdateProductEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreUpdateProductEvent
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
    public function handle(UpdateProductEvent $event): void
    {
        //
    }
}
