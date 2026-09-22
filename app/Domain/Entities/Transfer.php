<?php

namespace App\Domain\Entities;

class Transfer
{
    private int $id;
    private int $fromWarehouseId;
    private int $toWarehouseId;
    private string $date;
    private array $transferItems;

    public function __construct(
        int $id,
        int $fromWarehouseId,
        int $toWarehouseId,
        array $transferItems,
        string $date
    ) {
        if ($fromWarehouseId === $toWarehouseId) {
            throw new \InvalidArgumentException(
                'Source and destination warehouses must be different.'
            );
        }

        $this->id = $id;
        $this->fromWarehouseId = $fromWarehouseId;
        $this->toWarehouseId = $toWarehouseId;
        $this->transferItems = $transferItems;
        $this->date = $date;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFromWarehouseId(): int
    {
        return $this->fromWarehouseId;
    }

    public function getToWarehouseId(): int
    {
        return $this->toWarehouseId;
    }

    public function getTransferItems(): array
    {
        return $this->transferItems;
    }

    public function getDate(): string
    {
        return $this->date;
    }
}