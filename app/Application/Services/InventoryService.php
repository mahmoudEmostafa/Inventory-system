<?php

namespace App\Application\Services;

use App\Domain\Contracts\InventoryRepositoryInterface;
use App\Domain\Entities\Inventory;
use App\Domain\ValueObjects\Quantity;
use App\Domain\Exceptions\InvalidQuantityException;

class InventoryService
{
    public function __construct(
        private InventoryRepositoryInterface $inventoryRepository
    ) {
    }

    public function increase(
    int $productId,
    int $warehouseId,
    float $quantity
): void {
    $this->validateQuantity($quantity);

    $inventory = $this->inventoryRepository
        ->findByProductAndWarehouse(
            $productId,
            $warehouseId
        );

    if ($inventory === null) {
        $inventory = new Inventory(
            0,
            $productId,
            $warehouseId,
            new Quantity($quantity)
        );

        $this->inventoryRepository->create($inventory);

        return;
    }

    $inventory->increase($quantity);

    $this->inventoryRepository->update($inventory);
}

   public function decrease(
    int $productId,
    int $warehouseId,
    float $quantity
): void {
    $this->validateQuantity($quantity);

    $inventory = $this->inventoryRepository
        ->findByProductAndWarehouse(
            $productId,
            $warehouseId
        );

    if ($inventory === null) {
        throw new InvalidQuantityException(
            'Inventory record does not exist.'
        );
    }

    $inventory->decrease($quantity);

    $this->inventoryRepository->update($inventory);
}
    private function validateQuantity(float $quantity): void
    {
        if ($quantity <= 0) {
            throw new InvalidQuantityException(
                'Quantity must be greater than zero.'
            );
        }
    }
}