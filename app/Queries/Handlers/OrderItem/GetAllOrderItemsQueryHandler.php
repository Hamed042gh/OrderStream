<?php

namespace App\Queries\Handlers\OrderItem;


use App\Models\OrderItem;
use App\Queries\OrderItem\GetAllOrderItemsQuery;
use Illuminate\Support\Facades\Cache;

class GetAllOrderItemsQueryHandler
{
    public function handle(GetAllOrderItemsQuery $query)
    {
        // تلاش برای بازیابی داده‌ها از کش
        $cachedProducts = Cache::tags(['products'])->get('all_products');

        // اگر داده‌ها در کش موجود نبودند، پرس و جو انجام می‌شود و ذخیره در کش
        if (!$cachedProducts) {
            $cachedProducts = OrderItem::all();
            Cache::tags(['products'])->put('all_products', $cachedProducts, now()->addMinutes(10)); // ذخیره داده‌ها در کش برای 10 دقیقه
        }

        return $cachedProducts;
    }
}
