<?php

namespace App\Http\Requests\API;

use App\Exceptions\ExceptionHandler;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingStatusRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = $this->route('orderStatus') ? $this->route('orderStatus')->id : $this->id;

        return [
            'name' => ['unique:booking_status,name,'.$id.',id,deleted_at,NULL', 'string', 'max:255'],
            'color' => ['string'],
            'sequence' => ['required', 'unique:booking_status,sequence,'.$id.',id,deleted_at,NULL', 'numeric'],
            'status' => ['min:0', 'max:1'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new ExceptionHandler($validator->errors()->first(), 422);
    }
}
