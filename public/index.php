<?php

require_once __DIR__ . '/../config/bootstrap.php';

use App\Core\Router;
use App\Http\Responses\JsonResponse;

$router = new Router();

require_once __DIR__ . '/../routes/api.php';

$path = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

$basePath = dirname($_SERVER['SCRIPT_NAME']);

if ($basePath !== '/' && str_starts_with($path, $basePath)) {
    $path = substr($path, strlen($basePath));
}

if ($path === '') {
    $path = '/';
}

$response = $router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $path
);

JsonResponse::send($response);