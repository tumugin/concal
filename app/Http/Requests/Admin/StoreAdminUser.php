<?php

namespace App\Http\Requests\Admin;

use App\Models\AdminUser;
use App\Utils\PasswordAssertUtil;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StoreAdminUser extends FormRequest
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
                "regex:" . AdminUser::USER_NAME_TEST_REGEX,
                Rule::unique(AdminUser::class, 'user_name')
            ],
            'name' => 'required|string',
            'password' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!PasswordAssertUtil::isStrongPassword($value)) {
                    $fail('十分に強力なパスワードではありません');
                }
            }],
            'email' => ['required', 'email:rfc', Rule::unique(AdminUser::class, 'email')],
            'userPrivilege' => ['required', 'string', Rule::in(AdminUser::USER_PRIVILEGES)],
        ];
    }

    protected function passedValidation()
    {
        $this->replace([
            'user_name' => $this->input('userName'),
            'name' => $this->input('name'),
            'password' => Hash::make($this->input('password')),
            'email' => $this->input('email'),
            'user_privilege' => $this->input('userPrivilege'),
        ]);
    }
}
