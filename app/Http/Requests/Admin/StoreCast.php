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
            'castName' => 'required|string',
            'castShortName' => 'nullable|string',
            'castTwitterId' => 'nullable|string',
            'castDescription' => 'nullable|string',
            'castColor' => ['nullable', 'string', 'regex:' . Cast::CAST_COLOR_REGEX],
        ];
    }

    protected function passedValidation()
    {
        $this->replace([
            'cast_name' => $this->input('castName'),
            'cast_short_name' => $this->input('castShortName'),
            'cast_twitter_id' => $this->input('castTwitterId'),
            'cast_description' => $this->input('castDescription') ?? '',
            'cast_color' => $this->input('castColor'),
            'cast_disabled' => false,
        ]);
    }
}
