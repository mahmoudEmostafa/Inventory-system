<?php
namespace App\Domain\Entities;

use App\Domain\Exceptions\InvalidPurchaseItemException;
use App\Domain\ValueObjects\Quantity;
class PurchaseItem{
private int $id;
private int $productId;
private Quantity $quantity;
private float $unitPrice;


public function __construct(
    int $id,
    int $productId,
    Quantity $quantity,
    float $unitPrice
    
) {


    if ($productId <= 0) {
            throw new InvalidPurchaseItemException(
                'Product ID must be greater than zero.'
            );
        }

        if ($quantity->getValue() <= 0) {
            throw new InvalidPurchaseItemException(
                'Purchase quantity must be greater than zero.'
            );
        }

        if ($unitPrice <= 0) {
            throw new InvalidPurchaseItemException(
                'Unit price must be greater than zero.'
            );
        }
    $this->id = $id;
    $this->productId = $productId;
    $this->quantity = $quantity;
    $this->unitPrice = $unitPrice;
   
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

public function getUnitPrice(): float
{
    return $this->unitPrice;
}

public function subTotal(): float
{
    return $this->quantity->getValue() * $this->unitPrice;
}

}