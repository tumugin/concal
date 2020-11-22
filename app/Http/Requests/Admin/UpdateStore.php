<?php

namespace App\Http\Requests\Admin;

use App\Models\StoreGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'storeName' => 'required|string',
            'storeGroupId' => ['required', 'integer', Rule::exists(StoreGroup::class, 'id')],
            'storeDisabled' => ['required', 'string', Rule::in(['true', 'false'])],
        ];
    }
}
