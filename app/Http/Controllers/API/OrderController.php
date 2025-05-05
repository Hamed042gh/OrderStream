<?php

namespace App\Http\Controllers\API;

use App\Commands\Order\CreateOrderCommand;
use App\Commands\Order\CreateOrderCommandHandler;
use App\Commands\Order\DeleteOrderCommand;
use App\Commands\Order\DeleteOrderCommandHandler;
use App\Commands\Order\UpdateOrderCommand;
use App\Commands\Order\UpdateOrderCommandHandler;
use App\Events\Order\CreateOrderEvent;
use App\Events\Order\DeleteOrderEvent;
use App\Events\Order\UpdateOrderEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\StoreUpdateOrderRequest;
use App\Queries\Order\GetAllOrdersQuery;
use App\Queries\Order\GetAllOrdersQueryHandler;
use App\Queries\Order\GetOrderQuery;
use App\Queries\Order\GetOrderQueryHandler;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    // List all orders
    public function index()
    {
        $query = new GetAllOrdersQuery();
        $orders = app(GetAllOrdersQueryHandler::class)->handle($query);

        return response()->json($orders);
    }

    // Store a new order
    public function store(StoreOrderRequest $request)
    { 
        $orderData = $request->validated();
        $command = new CreateOrderCommand($orderData);
        $order = app(CreateOrderCommandHandler::class)->handle($command);

        Cache::tags(['orders'])->put("order_{$order->id}", $order, now()->addMinutes(10));
        event(new CreateOrderEvent($order));
        return response()->json($order, 201);
    }

    // Show a specific order
    public function show(string $id)
    {
        $query = new GetOrderQuery($id);
        $order = app(GetOrderQueryHandler::class)->handle($query);

        return response()->json($order);
    }

    // Update a specific order
    public function update(StoreUpdateOrderRequest $request, string $id)
    {
        $orderData = $request->validated();
        $command = new UpdateOrderCommand($id, $orderData);
        $order = app(UpdateOrderCommandHandler::class)->handle($command);
        $changedAttributes = $order->getChanges();

        // پاک کردن کش قدیمی
        Cache::tags(['orders'])->forget("order_{$id}");
        Cache::tags(['orders'])->forget('orders');
    
        // ذخیره کش جدید
        Cache::tags(['orders'])->put("order_{$order->id}", $order, now()->addMinutes(10));
    
        // ارسال رویداد به همراه تغییرات
        event(new UpdateOrderEvent($order, $changedAttributes));
        return response()->json($order);
    }

    // Delete a specific order
    public function destroy(string $id)
    {
        $command = new DeleteOrderCommand($id);
        app(DeleteOrderCommandHandler::class)->handle($command);
        Cache::tags(['orders'])->forget("order_{$id}");
        Cache::tags(['orders'])->forget('orders');
        event(new DeleteOrderEvent($id));
        return response()->json(['message' => 'Order deleted successfully'], 204);
    }
}
