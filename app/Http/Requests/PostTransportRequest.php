<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostTransportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'arrival_date' => 'required|after_or_equal:departure_date',
            'arrival_type' => 'required',
            'biddable' => 'required',
            'departure_address' => 'required',
            'departure_date' => 'required',
            'departure_lat' => 'required',
            'departure_lng' => 'required',
            'departure_type' => 'required',
            'destination_address' => 'required',
            'destination_lat' => 'required',
            'destination_lng' => 'required',
            'first_bid_buy' => 'required',
            'budget' => 'required',
            'pet_ids' => [
                function($attribute, $value, $fail) {
                    if (count($value) == 0) {
                        return $fail('You need to select at least one pet for transport.');
                    }
                }
            ]
        ];
    }
}
