<?php

namespace App\Events\Product;

use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateProductEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $entity_type;
    public $entity_id;
    public $event_data;
    public $event_type;

    public function __construct(Product $product)
    {

        $this->entity_type = 'Product';
        $this->entity_id = $product->id;
        $this->event_type = 'CreateProduct';
        $this->event_data = json_encode([
            'name' => $product->name,
            'price' => $product->price,
            'stock' => $product->stock,
        ]);
    }
}
