<?php

namespace App\Http\Requests\Admin;

use App\Models\Cast;
use Illuminate\Foundation\Http\FormRequest;

class StoreCast extends FormRequest
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
            'castName' => 'required|string',
            'castShortName' => 'nullable|string',
            'castTwitterId' => 'nullable|string',
            'castDescription' => 'nullable|string',
            'castColor' => ['nullable', 'string', 'regex:' . Cast::CAST_COLOR_REGEX],
        ];
    }
}
