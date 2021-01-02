<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Utils\PasswordAssertUtil;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
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

    public function toValueObject(): array
    {
        return [
            'user_name' => $this->input('userName'),
            'name' => $this->input('name'),
            'password' => Hash::make($this->input('password')),
            'email' => $this->input('email'),
            'user_privilege' => $this->input('userPrivilege'),
        ];
    }
}
