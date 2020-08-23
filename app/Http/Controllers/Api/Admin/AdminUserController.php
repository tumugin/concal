<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    private const _PAGINATION_COUNT = 10;

    public function getAllUsers(Request $request)
    {
        $request->validate([
            'page' => 'required|integer',
        ]);
        $page = (int)$request->get('page');
        $users = User::all()
            ->skip(self::_PAGINATION_COUNT * $page)
            ->take(self::_PAGINATION_COUNT)
            ->getIterator();
        $users_result = collect($users)->map(function (User $user) {
            return [
                'userName' => $user->user_name,
                'name' => $user->name,
                'email' => $user->email,
                'userPrivilege' => $user->user_privilege,
            ];
        })->all();
        return [
            'success' => true,
            'users' => $users_result,
        ];
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'userName' => 'required|string',
            'name' => 'required|string',
            'password' => 'required|string',
            'email' => 'required|email:rfc',
            'userPrivilege' => 'required|string',
        ]);
        User::createUser(
            $request->post('userName'),
            $request->post('name'),
            $request->post('password'),
            $request->post('email'),
            $request->post('userPrivilege'),
        );
        return [
            'success' => true,
        ];
    }

    public function deleteUser(Request $request)
    {

    }

    public function editUser()
    {

    }
}
