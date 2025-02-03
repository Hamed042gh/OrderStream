<?php

namespace App\Listeners\Payment;

use App\Events\Payment\CreatePaymentEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreCreatePaymentEvent
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
    public function handle(CreatePaymentEvent $event): void
    {
        //
    }
}
