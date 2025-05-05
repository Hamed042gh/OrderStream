<?php
namespace App\Commands\Orderitem;

class DeleteOrderitemCommand
{
    public $orderitemId;

    public function __construct($orderitemId)
    {
        $this->orderitemId = $orderitemId;
    }
}
