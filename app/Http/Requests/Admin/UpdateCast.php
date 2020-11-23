<?php

namespace App\Http\Requests\Admin;

use App\Models\Cast;
use App\Models\Store;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCast extends FormRequest
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

    protected function prepareForValidation()
    {
        if ($this->storeIds) {
            $this->merge([
                'storeIds' => explode(',', $this->storeIds)
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'castName' => 'required|string',
            'castShortName' => 'nullable|string',
            'castTwitterId' => 'nullable|string',
            'castDescription' => 'nullable|string',
            'castColor' => ['nullable', 'string', 'regex:' . Cast::CAST_COLOR_REGEX],
            'storeIds' => 'nullable|array',
            'storeIds.*' => ['integer', Rule::exists(Store::class, 'id')],
            'castDisabled' => ['nullable', 'string', Rule::in(['true', 'false'])],
        ];
    }
}
