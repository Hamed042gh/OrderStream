<?php

namespace App\Listeners\Orderitem;

use App\Events\Orderitem\UpdateOrderItemEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreUpdateOrderItemEvent
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
    public function handle(UpdateOrderItemEvent $event): void
    {
        //
    }
}
