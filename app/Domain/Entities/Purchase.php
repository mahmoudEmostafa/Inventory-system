<?php

namespace App\Domain\Entities;

use App\Domain\Exceptions\InvalidPurchaseException;

class Purchase
{
    private int $id;
    private int $supplierId;
    private int $warehouseId;
    private string $date;
    private array $purchaseItems;
    private float $total;

    public function __construct(
        int $id,
        int $supplierId,
        int $warehouseId,
        array $purchaseItems,
        string $date
    ) {
        $this->validateSupplier($supplierId);
        $this->validateWarehouse($warehouseId);
        $this->validateItems($purchaseItems);
        $this->validateDate($date);

        $this->id = $id;
        $this->supplierId = $supplierId;
        $this->warehouseId = $warehouseId;
        $this->purchaseItems = $purchaseItems;
        $this->date = $date;
        $this->total = $this->calculateTotal();
    }

    private function validateSupplier(int $supplierId): void
    {
        if ($supplierId <= 0) {
            throw new InvalidPurchaseException(
                'Supplier ID must be greater than zero.'
            );
        }
    }

    private function validateWarehouse(int $warehouseId): void
    {
        if ($warehouseId <= 0) {
            throw new InvalidPurchaseException(
                'Warehouse ID must be greater than zero.'
            );
        }
    }

    private function validateItems(array $purchaseItems): void
    {
        if (empty($purchaseItems)) {
            throw new InvalidPurchaseException(
                'Purchase must contain at least one item.'
            );
        }

        foreach ($purchaseItems as $item) {
            if (!$item instanceof PurchaseItem) {
                throw new InvalidPurchaseException(
                    'Invalid purchase item.'
                );
            }
        }
    }

    private function validateDate(string $date): void
    {
        if (trim($date) === '') {
            throw new InvalidPurchaseException(
                'Purchase date cannot be empty.'
            );
        }
    }

    private function calculateTotal(): float
    {
        $total = 0;

        foreach ($this->purchaseItems as $item) {
            $total += $item->subTotal();
        }

        return $total;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSupplierId(): int
    {
        return $this->supplierId;
    }

    public function getWarehouseId(): int
    {
        return $this->warehouseId;
    }

    public function getPurchaseItems(): array
    {
        return $this->purchaseItems;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}