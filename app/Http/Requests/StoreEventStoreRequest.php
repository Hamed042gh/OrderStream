<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;  // اگر نیاز به مجوز خاص دارید، این را تغییر دهید
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'order_id' => 'required|exists:orders,id',  // وجود سفارش در جدول orders
            'event_type' => 'required|string|max:255',  // نوع رویداد (مانند "order_placed")
            'event_data' => 'required|array',  // داده‌های رویداد باید یک آرایه باشند
        ];
    }

    /**
     * Customize the error messages.
     */
    public function messages()
    {
        return [
            'order_id.required' => 'Order ID is required.',
            'order_id.exists' => 'The specified order ID does not exist.',
            'event_type.required' => 'Event type is required.',
            'event_data.required' => 'Event data is required.',
        ];
    }
}
