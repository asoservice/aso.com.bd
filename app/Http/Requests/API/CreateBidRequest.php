<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
class CreateBidRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'service_request_id' => ['required', 'exists:service_requests,id'],
            'provider_id' => ['exists:users,id,deleted_at,NULL','nullable'],
            'amount' => ['required'],
        ];
    }
}