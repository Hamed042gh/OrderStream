<?php

namespace App\Listeners\Orderitem;

use App\Events\Orderitem\DeleteOrderItemEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreDeleteOrderItemEvent
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
    public function handle(DeleteOrderItemEvent $event): void
    {
        //
    }
}
