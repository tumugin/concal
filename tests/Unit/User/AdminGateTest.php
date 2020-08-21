<?php

namespace Tests\Unit\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AdminGateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param array $test_user_data
     * @param bool $is_admin
     * @dataProvider adminGateTestDataProvider
     */
    public function testAdminGate(array $test_user_data, bool $is_admin): void
    {
        $created_user = User::createUser(
            $test_user_data['user_name'],
            $test_user_data['name'],
            $test_user_data['password'],
            $test_user_data['email'],
            $test_user_data['user_privilege']
        );
        $this->assertEquals($is_admin, $created_user->isAdmin());
        Auth::login($created_user);
        $this->assertEquals(Gate::allows('has-admin-privilege'), $is_admin);
    }

    public function adminGateTestDataProvider(): array
    {
        return [
            // 管理者ユーザ
            [
                [
                    'user_name' => 'amina',
                    'name' => '東城アミナ',
                    'password' => 'Amina_12241224',
                    'email' => 'amina@example.com',
                    'user_privilege' => User::USER_PRIVILEGE_ADMIN,
                ],
                true,
            ],
            // 一般ユーザ
            [
                [
                    'user_name' => 'kana',
                    'name' => '葉山カナ',
                    'password' => 'kana_kana_Kana_1210',
                    'email' => 'kana@example.com',
                    'user_privilege' => User::USER_PRIVILEGE_USER,
                ],
                false,
            ],
        ];
    }
}
