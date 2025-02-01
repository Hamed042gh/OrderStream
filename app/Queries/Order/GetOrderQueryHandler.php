<?php
namespace App\Queries\Order;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class GetOrderQueryHandler
{
    public function handle(GetOrderQuery $query)
    {
        // Cache key for specific order
        $cacheKey = "order_{$query->id}";

        // Check if the order is cached
        $order = Cache::tags(['orders'])->get($cacheKey);

        if (!$order) {
            // If not cached, fetch from database
            $order = Order::find($query->id);  // Example: Replace with your actual query
            Cache::tags(['orders'])->put($cacheKey, $order, now()->addMinutes(10));  // Cache for 10 minutes
        }

        return $order;
    }
}
