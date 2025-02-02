<?php

namespace App\Listeners\Order;

use App\Events\Order\DeleteOrderEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreDeleteOrderEvent
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
    public function handle(DeleteOrderEvent $event): void
    {
        //
    }
}
