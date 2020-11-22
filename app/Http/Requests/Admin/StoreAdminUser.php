<?php

namespace App\Http\Requests\Admin;

use App\Models\AdminUser;
use App\Utils\PasswordAssertUtil;
use Illuminate\Foundation\Http\FormRequest;
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
}
