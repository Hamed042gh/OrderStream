<?php

namespace App\Queries\Handlers\OrderItem;

use App\Models\OrderItem;
use Illuminate\Support\Facades\Cache;

class GetOrderItemQueryHandler
{
    public function handle(GetOrderItemQuery $query)
    {
        // تلاش برای بازیابی محصول از کش
        $cachedProduct = Cache::tags(['products'])->get("product_{$query->id}");

        // اگر محصول در کش موجود نبود، از دیتابیس دریافت و در کش ذخیره می‌شود
        if (!$cachedProduct) {
            $cachedProduct = OrderItem::find($query->id);
            Cache::tags(['products'])->put("product_{$query->id}", $cachedProduct, now()->addMinutes(10)); // ذخیره محصول در کش
        }

        return $cachedProduct;
    }
}
