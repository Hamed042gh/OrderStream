<?php

namespace App\Jobs\Base;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class BaseCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $eventDataArray;
    public string $eventType;
    public string $entityType;
    public int $entityId;

    public function __construct(array $eventDataArray, string $eventType, string $entityType, int $entityId)
    {
        $this->eventDataArray = $eventDataArray;
        $this->eventType = $eventType;
        $this->entityType = $entityType;
        $this->entityId = $entityId;
    }
}
