<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Utils\PasswordAssertUtil;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUser extends FormRequest
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
            'userName' => [
                'required',
                'string',
                "regex:" . User::USER_NAME_TEST_REGEX,
                Rule::unique(User::class, 'user_name')
            ],
            'name' => 'required|string',
            'password' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!PasswordAssertUtil::isStrongPassword($value)) {
                    $fail('十分に強力なパスワードではありません');
                }
            }],
            'email' => ['required', 'email:rfc', Rule::unique(User::class, 'email')],
            'userPrivilege' => ['required', 'string', Rule::in(User::USER_PRIVILEGES)],
        ];
    }
}
