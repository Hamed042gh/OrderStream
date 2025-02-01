<?php
namespace App\Queries\Order;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class GetAllOrdersQueryHandler
{
    public function handle(GetAllOrdersQuery $query)
    {
        // Check if orders are cached
        $orders = Cache::tags(['orders'])->get('all_orders');

        if (!$orders) {
            // If not cached, fetch from database
            $orders = Order::all();  // Example: Replace with your actual query
            Cache::tags(['orders'])->put('all_orders', $orders, now()->addMinutes(10));  // Cache for 10 minutes
        }

        return $orders;
    }
}
