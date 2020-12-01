<?php

return [
    'production_host' => env('PRODUCTION_HOST', 'localhost'),
    'admin_host' => env('ADMIN_HOST', 'localhost'),
    'oauth2_proxy_user_info_endpoint' => env('OAUTH2_PROXY_USER_INFO_ENDPOINT', 'https://localhost/oauth2/userinfo'),
    'oauth2_proxy_enabled' => env('OAUTH2_PROXY_ENABLED', false),
];
