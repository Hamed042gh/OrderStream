<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventStore extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'event_type', 'event_data' ,'entity_id',
    'entity_type',];

    // مشخص کردن فیلد event_data به‌عنوان نوع JSON
    protected $casts = [
        'event_data' => 'array', // تبدیل event_data به آرایه
    ];
}
