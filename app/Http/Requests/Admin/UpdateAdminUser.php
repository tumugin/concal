<?php

namespace App\Http\Requests\Admin;

use App\Models\AdminUser;
use App\Utils\PasswordAssertUtil;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
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
        return true;
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

    protected function passedValidation()
    {
        $validated_values = [];
        if ($this->input('userName') !== null) {
            $validated_values['user_name'] = $this->input('userName');
        }
        if ($this->input('name') !== null) {
            $validated_values['name'] = $this->input('name');
        }
        if ($this->input('password') !== null) {
            $validated_values['password'] = Hash::make($this->input('password'));
        }
        if ($this->input('email') !== null) {
            $validated_values['email'] = $this->input('email');
        }
        if ($this->input('userPrivilege') !== null) {
            $validated_values['user_privilege'] = $this->input('userPrivilege');
        }

        $this->replace($validated_values);
    }
}
