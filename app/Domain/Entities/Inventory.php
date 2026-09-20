<?php

namespace App\Domain\Entities;

use App\Domain\Exceptions\InvalidQuantityException;
use App\Domain\ValueObjects\Quantity;

class Inventory
{
    private int $id;
    private int $productId;
    private int $warehouseId;
    private Quantity $quantity;

    public function __construct(
        int $id,
        int $productId,
        int $warehouseId,
        Quantity $quantity
    ) {
        $this->id = $id;
        $this->productId = $productId;
        $this->warehouseId = $warehouseId;
        $this->quantity = $quantity;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getWarehouseId(): int
    {
        return $this->warehouseId;
    }

    public function getQuantity(): Quantity
    {
        return $this->quantity;
    }

    public function increase(float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidQuantityException(
                'Increase amount must be greater than zero.'
            );
        }

        $newQuantity = $this->quantity->getValue() + $amount;

        $this->quantity = new Quantity($newQuantity);
    }

    public function decrease(float $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidQuantityException(
                'Decrease amount must be greater than zero.'
            );
        }

        if ($amount > $this->quantity->getValue()) {
            throw new InvalidQuantityException(
                'Insufficient inventory quantity.'
            );
        }

        $newQuantity = $this->quantity->getValue() - $amount;

        $this->quantity = new Quantity($newQuantity);
    }
}