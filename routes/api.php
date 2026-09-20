<?php

use App\Core\Router;

$router->get('/api/test', function () {
    return [
        'success' => true,
        'message' => 'API is working.'
    ];
});