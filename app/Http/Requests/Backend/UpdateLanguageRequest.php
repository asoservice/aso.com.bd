<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLanguageRequest extends FormRequest
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
        $id = $this->route('systemLang');

        return [
            'name' => ['required', 'string', 'max:255', 'unique:system_langs,name,'.$id.',id,deleted_at,NULL'],
            'locale' => ['required'],
            'app_locale' => ['required'],
        ];
    }
}
