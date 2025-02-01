<?php

namespace App\Repositories;

use App\Models\EventStore;

class EventStoreRepository
{
    // Store event data in EventStore
    public function storeEvent($eventDataArray, $eventType, $entityType)
    {
        return EventStore::create([
            'entity_id' => $eventDataArray['order_id'],  // Using order_id from the validated data
            'entity_type' => $entityType,  // Using entityType
            'event_type' => $eventType,    // Using eventType
            'event_data' => json_encode($eventDataArray), // Storing data as JSON
        ]);
    }

    // Retrieve all events with pagination
    public function getAllEvents($perPage = 15)
    {
        return EventStore::paginate($perPage);
    }

    // Retrieve events by event type
    public function getEventsByType($eventType)
    {
        return EventStore::where('event_type', $eventType)->get();
    }

    // Retrieve events for a specific entity
    public function getEventsForEntity($entity)
    {
        return EventStore::where('entity_id', $entity->id)
            ->where('entity_type', get_class($entity))
            ->get();
    }
}
