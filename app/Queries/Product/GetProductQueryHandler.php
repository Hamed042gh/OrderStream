<?php

namespace App\Queries\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class GetProductQueryHandler
{
    public function handle(GetProductQuery $query)
    {
        // تلاش برای بازیابی محصول از کش
        $cachedProduct = Cache::tags(['products'])->get("product_{$query->id}");

        // اگر محصول در کش موجود نبود، از دیتابیس دریافت و در کش ذخیره می‌شود
        if (!$cachedProduct) {
            $cachedProduct = Product::find($query->id);
            Cache::tags(['products'])->put("product_{$query->id}", $cachedProduct, now()->addMinutes(10)); // ذخیره محصول در کش
        }

        return $cachedProduct;
    }
}
