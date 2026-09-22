<?php

namespace App\Domain\Entities;

class Sale
{
    private int $id;
    private int $customerId;
    private int $warehouseId;
    private string $date;
    private array $saleItems;
    private float $total;

    public function __construct(
        int $id,
        int $customerId,
        int $warehouseId,
        array $saleItems,
        string $date
    ) {
        $this->id = $id;
        $this->customerId = $customerId;
        $this->warehouseId = $warehouseId;
        $this->saleItems = $saleItems;
        $this->date = $date;
        $this->total = $this->calculateTotal();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function getWarehouseId(): int
    {
        return $this->warehouseId;
    }

    public function getSaleItems(): array
    {
        return $this->saleItems;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    private function calculateTotal(): float
    {
        $total = 0;

        foreach ($this->saleItems as $item) {
            $total += $item->subTotal();
        }

        return $total;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}