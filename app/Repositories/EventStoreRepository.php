<?php

namespace App\Repositories;

use App\Models\EventStore;

class EventStoreRepository
{
    // Store event data in EventStore
    public function storeEvent($eventDataArray,$event_type, $entity_type, $entity_id)
    {
        
        return EventStore::create([
            'entity_id' => $entity_id,  
            'entity_type' => $entity_type,  // Using entityType
            'event_type' => $event_type,    // Using eventType
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

       // Get order state by order ID
       public function getOrderState($orderId)
       {
           $events = EventStore::where('entity_id', $orderId)
               ->where('entity_type', 'Order')
               ->orderBy('created_at', 'asc')
               ->get();
   
           $orderState = [];
   
           foreach ($events as $event) {
               $data = json_decode($event->event_data, true);
               $orderState = array_merge($orderState, $data['changed_attributes']);
           }
   
           return $orderState;
       }
}
