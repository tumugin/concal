<?php

namespace App\Http\Requests\Admin;

use App\Models\StoreGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStore extends FormRequest
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
            'storeName' => 'required|string',
            'storeGroupId' => ['required', 'integer', Rule::exists(StoreGroup::class, 'id')],
        ];
    }

    public function toValueObject(): array
    {
        return [
            'store_name' => $this->input('storeName'),
            'store_group_id' => $this->input('storeGroupId'),
            'store_disabled' => false,
        ];
    }
}
