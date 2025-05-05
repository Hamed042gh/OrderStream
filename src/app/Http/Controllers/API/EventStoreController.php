<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventStoreRequest;
use App\Models\EventStore;
use Illuminate\Support\Facades\Cache;

class EventStoreController extends Controller
{

    public function index()
    {
        $events = Cache::remember('event_stores', now()->addMinutes(10), function () {
            return EventStore::select('id', 'name', 'created_at')->get(); // دریافت فقط فیلدهای مورد نیاز
        });

        return response()->json($events);
    }


    public function store(StoreEventStoreRequest $request)
    {
        try {
            $eventStore = EventStore::create($request->validated());
            Cache::put("event_store_{$eventStore->id}", $eventStore, now()->addMinutes(10));
            Cache::forget('event_stores');

            return response()->json($eventStore, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create event', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        $eventStore = Cache::remember("event_store_{$id}", now()->addMinutes(10), function () use ($id) {
            return EventStore::findOrFail($id);
        });

        return response()->json($eventStore);
    }

 
    public function update(StoreEventStoreRequest $request, string $id)
    {
        $eventStore = EventStore::findOrFail($id);
        $eventStore->update($request->validated());

        Cache::put("event_store_{$id}", $eventStore, now()->addMinutes(10));
        Cache::forget('event_stores');

        return response()->json($eventStore);
    }


    public function destroy(string $id)
    {
        $eventStore = EventStore::findOrFail($id);
        $eventStore->delete();

        Cache::forget("event_store_{$id}");
        Cache::forget('event_stores');

        return response()->json(['message' => 'EventStore deleted successfully'], 204);
    }
}
