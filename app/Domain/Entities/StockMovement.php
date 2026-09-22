<?php

namespace App\Domain\Entities;

use App\Domain\Enums\StockMovementType;
use App\Domain\ValueObjects\Quantity;
use App\Domain\Enums\StockMovementReferenceType;
use DateTimeImmutable;
class StockMovement
{
    private int $id;
    private int $productId;
    private int $warehouseId;
    private StockMovementType $type;
    private Quantity $quantity;
    private StockMovementReferenceType $referenceType;
private DateTimeImmutable $createdAt;
    private int $referenceId;
    private ?int $createdBy;
    

    public function __construct(
        int $id,
        int $productId,
        int $warehouseId,
        StockMovementType $type,
        Quantity $quantity,
        StockMovementReferenceType $referenceType,
        int $referenceId,
        ?int $createdBy,
        DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->productId = $productId;
        $this->warehouseId = $warehouseId;
        $this->type = $type;
        $this->quantity = $quantity;
        $this->referenceType = $referenceType;
        $this->referenceId = $referenceId;
        $this->createdBy = $createdBy;
        $this->createdAt = $createdAt;
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

    public function getType(): StockMovementType
    {
        return $this->type;
    }

    public function getQuantity(): Quantity
    {
        return $this->quantity;
    }

    public function getReferenceType(): StockMovementReferenceType
{
    return $this->referenceType;
}

public function getCreatedAt(): DateTimeImmutable
{
    return $this->createdAt;
}

    public function getReferenceId(): int
    {
        return $this->referenceId;
    }

    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    
}