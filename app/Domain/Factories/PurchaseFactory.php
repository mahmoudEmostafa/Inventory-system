<?php

namespace App\Domain\Factories;

use App\Domain\Entities\Purchase;
use App\Domain\Entities\PurchaseItem;
use App\Domain\ValueObjects\Quantity;

class PurchaseFactory
{
    /**
     * @param int $supplierId
     * @param int $warehouseId
     * @param array $items
     * @param string $date
     * @return Purchase
     */
    public static function createPurchase(
        int $supplierId,
        int $warehouseId,
        array $items,
        string $date
    ): Purchase {
        $purchaseItems = [];

        foreach ($items as $item) {
            $purchaseItems[] = new PurchaseItem(
                0,
                $item['productId'],
                new Quantity($item['quantity']),
                $item['unitPrice']
            );
        }

        return new Purchase(
            0,
            $supplierId,
            $warehouseId,
            $purchaseItems,
            $date
        );
    }
}