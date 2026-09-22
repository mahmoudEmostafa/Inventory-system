<?php
namespace App\Domain\Contracts;

use App\Domain\Entities\StockMovement;

interface StockMovementRepositoryInterface
{
    public function saveStockMovement(StockMovement $stockMovement): int;
}