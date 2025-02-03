<?php

namespace App\Events\Orderitem;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeleteOrderItemEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $orderitemId;

    public function __construct(int $orderitemId)
    {
        $this->orderitemId = $orderitemId;
    }
}
