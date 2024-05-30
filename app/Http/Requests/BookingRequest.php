<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'zip' => 'nullable|string',
            'status' => 'string',
            'payment_method' => 'string',
            'payment_status' => 'string',
            'payment_url' => 'nullable|string',
            'total_price' => 'integer',
            'item_id' => 'required|exists:items,id',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
