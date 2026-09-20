<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(
        string $path,
        callable|array $handler
    ): void {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(
        string $path,
        callable|array $handler
    ): void {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(
        string $path,
        callable|array $handler
    ): void {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete(
        string $path,
        callable|array $handler
    ): void {
        $this->addRoute('DELETE', $path, $handler);
    }

    private function addRoute(
        string $method,
        string $path,
        callable|array $handler
    ): void {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(
        string $method,
        string $uri
    ): mixed {
        $handler = $this->routes[$method][$uri] ?? null;

        if ($handler === null) {
            http_response_code(404);

            return [
                'success' => false,
                'message' => 'Route not found.'
            ];
        }

        return call_user_func($handler);
    }
}