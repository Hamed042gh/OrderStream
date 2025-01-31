<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderitemRequest;
use App\Models\Orderitem;
use Illuminate\Support\Facades\Cache;

class OrderitemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // بررسی کش Redis برای داده‌ها
        $orderitems = Cache::remember('orderitems', 60, function () {
            return Orderitem::all(); // اگر در کش نیست، از دیتابیس می‌خواند
        });

        return response()->json($orderitems);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderitemRequest $request)
    {
        // ایجاد آیتم سفارش جدید
        $orderitem = Orderitem::create($request->validated()); // استفاده از داده‌های معتبر از درخواست

        // پاک کردن کش مربوط به آیتم‌های سفارش
        Cache::forget('orderitems');

        return response()->json($orderitem, 201); // پاسخ موفقیت‌آمیز با کد 201
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // بررسی کش Redis برای آیتم سفارش خاص
        $orderitem = Cache::remember("orderitem_{$id}", 60, function () use ($id) {
            return Orderitem::findOrFail($id); // اگر در کش نیست، از دیتابیس می‌خواند
        });

        return response()->json($orderitem);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreOrderitemRequest $request, string $id)
    {
        // جستجو برای آیتم سفارش با ID مشخص
        $orderitem = Orderitem::findOrFail($id);

        if (!$orderitem) {
            return response()->json(['message' => 'Orderitem not found'], 404);
        }

        // به‌روزرسانی آیتم سفارش
        $orderitem->update($request->validated());

        // پاک کردن کش مربوط به آیتم سفارش خاص و لیست آیتم‌ها
        Cache::forget("orderitem_{$id}");
        Cache::forget('orderitems');

        return response()->json($orderitem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // جستجو برای آیتم سفارش با ID مشخص
        $orderitem = Orderitem::findOrFail($id);

        if (!$orderitem) {
            return response()->json(['message' => 'Orderitem not found'], 404);
        }

        // حذف آیتم سفارش
        $orderitem->delete();

        // پاک کردن کش مربوط به آیتم سفارش خاص و لیست آیتم‌ها
        Cache::forget("orderitem_{$id}");
        Cache::forget('orderitems');

        return response()->json(['message' => 'Orderitem deleted successfully'], 204); // پاسخ موفقیت‌آمیز با کد 204
    }
}
