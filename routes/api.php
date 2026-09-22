<?php

use App\Core\Router;
use App\Http\Controllers\PurchaseController;

$router->get('/api/test', function () {
    return [
        'success' => true,
        'message' => 'API is working.'
    ];
});

$router->post('/api/purchases', function () use ($purchaseController) {
    return $purchaseController->store();
});




$router->post('/api/sales', function () use ($saleController) {
    return $saleController->store();
});


$router->post('/api/transfers', function () use ($transferController) {
    return $transferController->store();
});