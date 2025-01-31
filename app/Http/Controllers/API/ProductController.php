<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // بررسی کش Redis برای داده‌ها
        $products = Cache::remember('products', 60, function () {
            return Product::all(); // اگر در کش نیست، از دیتابیس می‌خواند
        });

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // ایجاد محصول جدید
        $product = Product::create($request->validated()); // استفاده از داده‌های معتبر از درخواست

        // پاک کردن کش مربوط به محصولات
        Cache::forget('products');

        return response()->json($product, 201); // پاسخ موفقیت‌آمیز با کد 201
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // بررسی کش Redis برای محصول خاص
        $product = Cache::remember("product_{$id}", 60, function () use ($id) {
            return Product::findOrFail($id); // اگر در کش نیست، از دیتابیس می‌خواند
        });

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProductRequest $request, string $id)
    {
        // جستجو برای محصول با ID مشخص
        $product = Product::findOrFail($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // به‌روزرسانی محصول
        $product->update($request->validated());

        // پاک کردن کش مربوط به محصول خاص و لیست محصولات
        Cache::forget("product_{$id}");
        Cache::forget('products');

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // جستجو برای محصول با ID مشخص
        $product = Product::findOrFail($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // حذف محصول
        $product->delete();

        // پاک کردن کش مربوط به محصول خاص و لیست محصولات
        Cache::forget("product_{$id}");
        Cache::forget('products');

        return response()->json(['message' => 'Product deleted successfully'], 204); // پاسخ موفقیت‌آمیز با کد 204
    }
}
