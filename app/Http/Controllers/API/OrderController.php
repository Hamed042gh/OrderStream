<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\StoreUpdateOrderRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        // Check Redis cache for orders
        $orders = Cache::remember('orders', 60, function () {
            return Order::all(); // If not in cache, fetch from the database
        });
        return response()->json($orders);
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        // Create a new order
        $order = Order::create($request->validated());

        // Clear the cache related to orders to refresh data
        Cache::forget('orders');

        return response()->json($order, 201); // Return the created order with 201 status code
    }

    /**
     * Display the specified order.
     */
    public function show(string $id)
    {
        // Check Redis cache for a specific order
        $order = Cache::remember("order_{$id}", 60, function () use ($id) {
            return Order::findOrFail($id); // If not in cache, fetch from the database
        });

        return response()->json($order); // Return the order data
    }

    /**
     * Update the specified order in storage.
     */
    public function update(StoreUpdateOrderRequest $request, string $id)
    {
        // Find the order by ID
        $order = Order::findOrFail($id);

        // Update the order with validated data
        $order->update($request->validated());

        // Clear the cache related to the specific order and the orders list
        Cache::forget("order_{$id}");
        Cache::forget('orders');

        return response()->json($order); // Return the updated order data
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(string $id)
    {
        // Find and delete the order by ID
        $order = Order::findOrFail($id);
        $order->delete();

        // Clear the cache related to the specific order and the orders list
        Cache::forget("order_{$id}");
        Cache::forget('orders');

        return response()->json(['message' => 'Order deleted successfully'], 204); // Return a success message
    }
}
