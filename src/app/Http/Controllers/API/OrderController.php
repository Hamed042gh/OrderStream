<?php

namespace App\Http\Controllers\API;

use App\Commands\Handlers\Order\CreateOrderCommandHandler;
use App\Commands\Handlers\Order\DeleteOrderCommandHandler;
use App\Commands\Handlers\Order\UpdateOrderCommandHandler;
use App\Commands\Order\CreateOrderCommand;
use App\Commands\Order\DeleteOrderCommand;
use App\Commands\Order\UpdateOrderCommand;
use App\Events\Order\CreateOrderEvent;
use App\Events\Order\DeleteOrderEvent;
use App\Events\Order\UpdateOrderEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\StoreUpdateOrderRequest;
use App\Queries\Handlers\Order\GetAllOrdersQueryHandler;
use App\Queries\Handlers\Order\GetOrderQueryHandler;
use App\Queries\Order\GetAllOrdersQuery;
use App\Queries\Order\GetOrderQuery;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // List all orders
    public function index(GetAllOrdersQueryHandler $handler)
    {
        $orders = Cache::tags(['orders'])->remember('orders', now()->addMinutes(10), function () use ($handler) {
            return $handler->handle(new GetAllOrdersQuery());
        });

        return response()->json($orders);
    }

    // Store a new order
    public function store(StoreOrderRequest $request, CreateOrderCommandHandler $handler)
    {
        $orderData = $request->validated();

        try {
            $command = new CreateOrderCommand($orderData);
            $order = $handler->handle($command);

            $this->storeOrderInCache($order);
            $this->dispatchOrderCreatedEvent($order);
            return $this->succedResponse($order);

        } catch (\Throwable $e) {
            $this->logOrderError($e);
            return $this->failedResponse($order);
        }
    }

    // Show a specific order
    public function show(string $id, GetOrderQueryHandler $handler)
    {
        $query = new GetOrderQuery($id);
        $order = $handler->handle($query);

        return response()->json($order);
    }

    // Update a specific order
    public function update(StoreUpdateOrderRequest $request, string $id, UpdateOrderCommandHandler $handler)
    {
        $orderData = $request->validated();

        try {
            $command = new UpdateOrderCommand($id, $orderData);
            $order = $handler->handle($command);
            $changedAttributes = $order->getChanges();

            $this->updateOrderInCache($order, $id);
            $this->dispatchOrderUpdatedEvent($order, $changedAttributes);

            return $this->succedResponse($order);

        } catch (\Throwable $e) {
            $this->logOrderError($e);
            return $this->failedResponse($order);
        }
    }

    // Delete a specific order
    public function destroy(string $id, DeleteOrderCommandHandler $handler)
    {
        $command = new DeleteOrderCommand($id);
        $handler->handle($command);

        Cache::tags(['orders'])->forget("order_{$id}");
        Cache::tags(['orders'])->forget('orders');

        event(new DeleteOrderEvent($id));

        return response()->json(['message' => 'Order deleted successfully'], 204);
    }

    private function storeOrderInCache($order)
    {
        Cache::tags(['orders'])->put("order_{$order->id}", $order, now()->addMinutes(10));
    }

    private function updateOrderInCache($order, $id)
    {
        Cache::tags(['orders'])->forget("order_{$id}");
        Cache::tags(['orders'])->forget('orders');

        Cache::tags(['orders'])->put("order_{$order->id}", $order, now()->addMinutes(10));
    }

    private function dispatchOrderCreatedEvent($order)
    {
        event(new CreateOrderEvent($order));
    }

    private function dispatchOrderUpdatedEvent($order, $changedAttributes)
    {
        event(new UpdateOrderEvent($order, $changedAttributes));
    }

    private function logOrderError($e)
    {
        Log::error("system has an error now", [
            'error_message' => $e->getMessage(),
            'stack_trace' => $e->getTraceAsString()
        ]);
    }

    private function succedResponse($order)
    {
        return response()->json([
            'status' => 'success',
            'data' => $order
        ], 201);
    }

    private function failedResponse($order)
    {
        return response()->json([
            'status' => 'error',
            'data' => $order
        ], 500);
    }
}
