<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventStoreRequest;
use App\Models\EventStore;
use Illuminate\Support\Facades\Cache;

class EventStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // استفاده از remember برای ذخیره‌سازی و بازیابی از کش
        $events = Cache::remember('event_stores', now()->addMinutes(10), function () {
            return EventStore::all();  // در صورت عدم وجود در کش، داده‌ها از دیتابیس بارگذاری می‌شود
        });

        return response()->json($events);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventStoreRequest $request)
    {
        // ایجاد رویداد جدید با داده‌های معتبر از فرم رکویست
        $eventStore = EventStore::create($request->validated());

        // پاک کردن کش مربوط به لیست تمام رویدادها
        Cache::forget('event_stores');

        // ذخیره این رویداد خاص در کش
        Cache::put("event_store_{$eventStore->id}", $eventStore, now()->addMinutes(10));

        return response()->json($eventStore, 201);  // پاسخ موفقیت‌آمیز با کد 201
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // استفاده از remember برای ذخیره‌سازی و بازیابی رویداد خاص از کش
        $eventStore = Cache::remember("event_store_{$id}", now()->addMinutes(10), function () use ($id) {
            return EventStore::findOrFail($id);  // در صورت عدم وجود در کش، داده‌ها از دیتابیس بارگذاری می‌شود
        });

        return response()->json($eventStore);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreEventStoreRequest $request, string $id)
    {
        // جستجو برای رویداد با ID مشخص
        $eventStore = EventStore::findOrFail($id);

        // به‌روزرسانی رویداد
        $eventStore->update($request->validated());

        // ذخیره مجدد در کش
        Cache::forget("event_store_{$id}");  // کش این رویداد خاص را پاک می‌کند
        Cache::put("event_store_{$id}", $eventStore, now()->addMinutes(10));  // کش ۱۰ دقیقه

        // پاک‌سازی کش لیست تمام رویدادها
        Cache::forget('event_stores');

        return response()->json($eventStore);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // جستجو برای رویداد با ID مشخص
        $eventStore = EventStore::findOrFail($id);

        // حذف رویداد
        $eventStore->delete();

        // پاک‌سازی کش‌های مربوطه
        Cache::forget("event_store_{$id}");
        Cache::forget('event_stores');  // کش لیست تمام رویدادها را پاک می‌کند

        return response()->json(['message' => 'EventStore deleted successfully'], 204);  // پاسخ موفقیت‌آمیز با کد 204
    }
}
