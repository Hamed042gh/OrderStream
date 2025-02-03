<?php

namespace App\Events\Order;

use App\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateOrderEvent
{
    use Dispatchable, SerializesModels;

    public $entity_type;
    public $entity_id;
    public $event_data;
    public $event_type;

    public function __construct(Order $order, array $changedAttributes)
    {
        $this->entity_type = 'Order';
        $this->entity_id = $order->id;
        $this->event_type = 'UpdateOrder';
        unset($changedAttributes['updated_at']);
        $this->event_data = json_encode([
            'changed_attributes' => $changedAttributes,
            'updated_at' => now(),
        ]);
    }
}
