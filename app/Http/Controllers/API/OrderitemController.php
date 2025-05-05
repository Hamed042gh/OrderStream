<?php

namespace App\Http\Controllers\API;

use App\Commands\Orderitem\CreateOrderitemCommand;
use App\Commands\Orderitem\CreateOrderitemCommandHandler;
use App\Commands\Orderitem\DeleteOrderitemCommand;
use App\Commands\Orderitem\DeleteOrderitemCommandHandler;
use App\Commands\Orderitem\UpdateOrderitemCommand;
use App\Commands\Orderitem\UpdateOrderitemCommandHandler;
use App\Events\Orderitem\CreateOrderItemEvent;
use App\Events\Orderitem\DeleteOrderItemEvent;
use App\Events\Orderitem\UpdateOrderItemEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderitemRequest;
use App\Http\Requests\StoreUpdateOrderitemRequest;
use App\Models\Orderitem;
use Illuminate\Support\Facades\Cache;

class OrderitemController extends Controller
{
    /**
     * نمایش لیست آیتم‌های سفارش
     */
    public function index()
    {
        $orderitems = Cache::remember('orderitems', 60, function () {
            return Orderitem::all(); 
        });

        return response()->json($orderitems);
    }

    /**
     * ایجاد آیتم جدید
     */
    public function store(StoreOrderitemRequest $request)
    {
        try {
            $orderitemData = $request->validated();
            $command = new CreateOrderitemCommand($orderitemData);
            $orderitem = app(CreateOrderitemCommandHandler::class)->handle($command);
            Cache::put("orderitem_{$orderitem->id}", $orderitem, 60);
            Cache::forget('orderitems');
            event(new CreateOrderItemEvent($orderitem));
            return response()->json($orderitem, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create order item', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * نمایش جزئیات یک آیتم سفارش
     */
    public function show(string $id)
    {
        $orderitem = Cache::remember("orderitem_{$id}", 60, function () use ($id) {
            return Orderitem::findOrFail($id);
        });

        return response()->json($orderitem);
    }

    /**
     * به‌روزرسانی آیتم سفارش
     */
    public function update(StoreUpdateOrderitemRequest $request, string $id)
    {
        $orderitemData = $request->validated();
        $command = new UpdateOrderitemCommand($id, $orderitemData);
        $orderitem = app(UpdateOrderitemCommandHandler::class)->handle($command);
        $changedAttributes = $orderitem->getChanges();

        Cache::put("orderitem_{$orderitem->id}", $orderitem, 60);
        Cache::forget('orderitems');
        event(new UpdateOrderItemEvent($orderitem, $changedAttributes));
        return response()->json($orderitem);
    }

    /**
     * حذف آیتم سفارش
     */
    public function destroy(string $id)
    {
        $command = new DeleteOrderitemCommand($id);
        app(DeleteOrderitemCommandHandler::class)->handle($command);
        Cache::tags(['orderitems'])->forget("orderitem_{$id}");
        Cache::tags(['orderitems'])->forget('orderitems');
        event(new DeleteOrderItemEvent($id));
        return response()->json(['message' => 'Orderitem deleted successfully'], 204);
    }
}
