<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Quantity;

class TransferItem
{
    private int $id;
    private int $productId;
    private Quantity $quantity;

    public function __construct(
        int $id,
        int $productId,
        Quantity $quantity
    ) {
        $this->id = $id;
        $this->productId = $productId;
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

    public function getQuantity(): Quantity
    {
        return $this->quantity;
    }
}