<?php

namespace App\Http\Requests\Admin;

use App\Models\AdminUser;
use App\Utils\PasswordAssertUtil;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminUser extends FormRequest
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
        /** @var $admin_user AdminUser */
        $admin_user = $this->route()->parameter('admin_user');
        return [
            'userName' => [
                'sometimes',
                'string',
                "regex:" . AdminUser::USER_NAME_TEST_REGEX,
                Rule::unique(AdminUser::class, 'user_name')->ignore($admin_user->id)
            ],
            'name' => 'sometimes|string',
            'password' => ['sometimes', 'string', function ($attribute, $value, $fail) {
                if (!PasswordAssertUtil::isStrongPassword($value)) {
                    $fail('十分に強力なパスワードではありません');
                }
            }],
            'email' => [
                'sometimes',
                'email:rfc',
                Rule::unique(AdminUser::class, 'email')->ignore($admin_user->id)
            ],
            'userPrivilege' => ['sometimes', 'string', Rule::in(AdminUser::USER_PRIVILEGES)],
        ];
    }
}
