<?php

namespace App\Domain\Factories;

use App\Domain\Entities\Sale;
use App\Domain\Entities\SaleItem;
use App\Domain\ValueObjects\Quantity;

class SaleFactory
{
    public static function createSale(
        int $customerId,
        int $warehouseId,
        array $items,
        string $date
    ): Sale {
        $saleItems = [];

        foreach ($items as $item) {
            $saleItems[] = new SaleItem(
                0,
                $item['productId'],
                new Quantity($item['quantity']),
                $item['unitPrice']
            );
        }

        return new Sale(
            0,
            $customerId,
            $warehouseId,
            $saleItems,
            $date
        );
    }
}