<?php

namespace App\Domain\Contracts;

use App\Domain\Entities\Inventory;

interface InventoryRepositoryInterface
{
    public function findByProductAndWarehouse(
        int $productId,
        int $warehouseId
    ): ?Inventory;

    public function create(Inventory $inventory): int;

    public function update(Inventory $inventory): void;
}