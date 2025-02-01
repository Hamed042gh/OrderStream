<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderitemRequest;
use App\Http\Requests\StoreUpdateOrderitemRequest;
use App\Models\Orderitem;
use Illuminate\Support\Facades\Cache;

class OrderitemController extends Controller
{
    /**
     * نمایش لیست آیتم‌های سفارش
     */
    public function index()
    {
        $orderitems = Cache::remember('orderitems', 60, function () {
            return Orderitem::select('id', 'name', 'price', 'quantity')->get(); // دریافت فقط فیلدهای مورد نیاز
        });

        return response()->json($orderitems);
    }

    /**
     * ایجاد آیتم جدید
     */
    public function store(StoreOrderitemRequest $request)
    {
        try {
            $orderitem = Orderitem::create($request->validated());
            Cache::put("orderitem_{$orderitem->id}", $orderitem, 60);
            Cache::forget('orderitems');

            return response()->json($orderitem, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create order item', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * نمایش جزئیات یک آیتم سفارش
     */
    public function show(string $id)
    {
        $orderitem = Cache::remember("orderitem_{$id}", 60, function () use ($id) {
            return Orderitem::findOrFail($id);
        });

        return response()->json($orderitem);
    }

    /**
     * به‌روزرسانی آیتم سفارش
     */
    public function update(StoreUpdateOrderitemRequest $request, string $id)
    {
        $orderitem = Orderitem::findOrFail($id);
        $orderitem->update($request->validated());

        Cache::put("orderitem_{$orderitem->id}", $orderitem, 60);
        Cache::forget('orderitems');

        return response()->json($orderitem);
    }

    /**
     * حذف آیتم سفارش
     */
    public function destroy(string $id)
    {
        $orderitem = Orderitem::findOrFail($id);
        $orderitem->delete();

        Cache::forget("orderitem_{$id}");
        Cache::forget('orderitems');

        return response()->json(['message' => 'Orderitem deleted successfully'], 204);
    }
}
