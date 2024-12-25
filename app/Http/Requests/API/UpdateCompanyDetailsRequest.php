<?php

namespace App\Http\Requests\API;

use App\Helpers\Helpers;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
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
        $providerCompany = Helpers::getCurrentUser()->company;
        if ($providerCompany) {
            return [
                'company_name' => 'required',
                'company_email' => ['required', 'string', 'email', 'max:255', Rule::unique('companies')->ignore($providerCompany->id)],
                'company_code' => 'required',
                'company_phone' => 'required',
                'description' => 'string',
            ];
        } else {
            return response()->json([
                'message' => __('static.provider.must_be_provider'),
                'success' => false,
            ]);
        }
    }

    /**
     * Get the validation messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'company_name.required' => 'Please enter the company name.',
            'company_email.required' => 'Please enter the company email.',
            'company_email.email' => 'Please enter a valid email address.',
            'company_email.unique' => 'This email is already in use.',
            'company_code.required' => 'Please enter the company code.',
            'company_phone.required' => 'Please enter the company phone number.',
        ];
    }
}

