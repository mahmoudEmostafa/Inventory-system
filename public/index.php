<?php

require_once __DIR__ . '/../config/bootstrap.php';

use App\Core\Router;
use App\Http\Responses\JsonResponse;
use App\Infrastructure\Database\Database;
use App\Infrastructure\Database\TransactionManager;
use App\Infrastructure\Repositories\InventoryRepository;
use App\Infrastructure\Repositories\PurchaseRepository;
use App\Infrastructure\Repositories\StockMovementRepository;
use App\Application\Services\InventoryService;
use App\Application\Services\PurchaseService;
use App\Http\Controllers\PurchaseController;
use App\Application\Services\SaleService;
use App\Infrastructure\Repositories\SaleRepository;
use App\Http\Controllers\SaleController;
$database = new Database();

$inventoryRepository = new InventoryRepository(
    $database
);

$purchaseRepository = new PurchaseRepository(
    $database
);

$stockMovementRepository = new StockMovementRepository(
    $database
);

$inventoryService = new InventoryService(
    $inventoryRepository
);

$transactionManager = new TransactionManager(
    $database->getConnection()
);

$purchaseService = new PurchaseService(
    $purchaseRepository,
    $inventoryService,
    $stockMovementRepository,
    $transactionManager
);

$purchaseController = new PurchaseController(
    $purchaseService
);
$saleRepository = new SaleRepository($database);

$saleService = new SaleService(
    $saleRepository,
    $inventoryService,
    $stockMovementRepository,
    $transactionManager
);

$saleController = new SaleController(
    $saleService
);
$router = new Router();

require_once __DIR__ . '/../routes/api.php';

$path = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

$basePath = dirname($_SERVER['SCRIPT_NAME']);

if (
    $basePath !== '/' &&
    str_starts_with($path, $basePath)
) {
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