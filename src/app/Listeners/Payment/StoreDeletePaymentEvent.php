<?php

namespace App\Listeners\Payment;

use App\Events\Payment\DeletePaymentEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreDeletePaymentEvent
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
    public function handle(DeletePaymentEvent $event): void
    {
        //
    }
}
