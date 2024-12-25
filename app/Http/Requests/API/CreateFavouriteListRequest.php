<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class CreateFavouriteListRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required'],
            'providerId' => ['exists:users,id,deleted_at,NULL'],
            'serviceId' => ['exists:services,id,deleted_at,NULL'],
        ];
    }

    public function messages(): array
    {
        return [
            'providerId.exists' => __('validation.providerId_exists'),
            'serviceId.exists' => __('validation.serviceId_exists'),
        ];
    }
}
