<?php


namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\Controller;

class AdminSystemInfoController extends Controller
{
    public function __invoke()
    {
        return [
            'success' => true,
            'systemInfo' => [
                'hostName' => gethostname(),
                'environment' => config('app.env'),
            ],
        ];
    }
}
