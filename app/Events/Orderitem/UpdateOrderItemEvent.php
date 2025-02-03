<?php

namespace App\Events\Orderitem;

use App\Models\Orderitem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateOrderItemEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $entity_type;
    public $entity_id;
    public $event_data;
    public $event_type;

    public function __construct(Orderitem $orderitem, array $changedAttributes)
    {
        $this->entity_type = 'Orderitem';
        $this->entity_id = $orderitem->id;
        $this->event_type = 'UpdateOrderitem';
        unset($changedAttributes['updated_at']);
        $this->event_data = json_encode([
            'changed_attributes' => $changedAttributes,
            'updated_at' => now(),
        ]);
    }
}
