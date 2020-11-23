<?php

namespace App\Http\Requests\Admin;

use App\Models\Store;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCastAttend extends FormRequest
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
            'storeId' => ['required', 'integer', Rule::exists(Store::class, 'id')],
            'startTime' => 'required|date|before:endTime',
            'endTime' => 'required|date|after:startTime',
            'attendInfo' => 'nullable|string',
        ];
    }
}
