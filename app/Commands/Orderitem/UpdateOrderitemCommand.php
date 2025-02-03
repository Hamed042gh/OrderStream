<?php
namespace App\Commands\Orderitem;

class UpdateOrderitemCommand
{
    public $orderitemId;
    public $orderitemData;

    public function __construct($orderitemId, $orderitemData)
    {
        $this->orderitemId = $orderitemId;
        $this->orderitemData = $orderitemData;
    }
}
