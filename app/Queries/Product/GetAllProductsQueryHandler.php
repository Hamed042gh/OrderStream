<?php

namespace App\Queries\Product;

use App\Queries\Product\GetAllProductsQuery;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class GetAllProductsQueryHandler
{
    public function handle(GetAllProductsQuery $query)
    {
        // تلاش برای بازیابی داده‌ها از کش
        $cachedProducts = Cache::tags(['products'])->get('all_products');

        // اگر داده‌ها در کش موجود نبودند، پرس و جو انجام می‌شود و ذخیره در کش
        if (!$cachedProducts) {
            $cachedProducts = Product::all();
            Cache::tags(['products'])->put('all_products', $cachedProducts, now()->addMinutes(10)); // ذخیره داده‌ها در کش برای 10 دقیقه
        }

        return $cachedProducts;
    }
}
