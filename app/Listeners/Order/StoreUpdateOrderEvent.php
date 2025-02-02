<?php

namespace App\Listeners\Order;

use App\Events\Order\UpdateOrderEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreUpdateOrderEvent
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
    public function handle(UpdateOrderEvent $event): void
    {
        //
    }
}
