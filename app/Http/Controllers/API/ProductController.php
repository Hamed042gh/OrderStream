<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\StoreUpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * نمایش لیست محصولات
     */
    public function index()
    {
        $products = Cache::remember('products', 60, function () {
            return Product::select('id', 'name', 'price', 'stock')->get(); // دریافت فقط فیلدهای مورد نیاز
        });

        return response()->json($products);
    }

    /**
     * ایجاد محصول جدید
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $product = Product::create($request->validated());
            Cache::put("product_{$product->id}", $product, 60);
            Cache::forget('products');

            return response()->json($product, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create product', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * نمایش یک محصول خاص
     */
    public function show(string $id)
    {
        $product = Cache::remember("product_{$id}", 60, function () use ($id) {
            return Product::findOrFail($id);
        });

        return response()->json($product);
    }

    /**
     * به‌روزرسانی محصول
     */
    public function update(StoreUpdateProductRequest $request, string $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());

        Cache::put("product_{$product->id}", $product, 60);
        Cache::forget('products');

        return response()->json($product);
    }

    /**
     * حذف محصول
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        Cache::forget("product_{$id}");
        Cache::forget('products');

        return response()->json(['message' => 'Product deleted successfully'], 204);
    }
}
