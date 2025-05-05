<?php

namespace App\Listeners\Payment;

use App\Events\Payment\UpdatePaymentEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreUpdatePaymentEvent
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
    public function handle(UpdatePaymentEvent $event): void
    {
        //
    }
}
