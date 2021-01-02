<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Utils\PasswordAssertUtil;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UpdateUser extends FormRequest
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
        /** @var $user User */
        $user = $this->route()->parameter('user');
        return [
            'userName' => [
                'sometimes',
                'string',
                "regex:" . User::USER_NAME_TEST_REGEX,
                Rule::unique(User::class, 'user_name')->ignore($user->id)
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
                Rule::unique(User::class, 'email')->ignore($user->id)
            ],
            'userPrivilege' => ['sometimes', 'string', Rule::in(User::USER_PRIVILEGES)],
        ];
    }

    public function toValueObject(): array
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

        return $validated_values;
    }
}
