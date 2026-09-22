<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Contracts\PurchaseRepositoryInterface;
use App\Domain\Entities\Purchase;
use App\Infrastructure\Database\Database;
use PDO;

class PurchaseRepository implements PurchaseRepositoryInterface
{
    private PDO $connection;

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    public function savePurchase(Purchase $purchase): int
    {
        $statement = $this->connection->prepare(
            'INSERT INTO purchases (
                supplier_id,
                warehouse_id,
                date,
                total
            )
            VALUES (
                :supplier_id,
                :warehouse_id,
                :date,
                :total
            )'
        );

        $statement->execute([
            'supplier_id' => $purchase->getSupplierId(),
            'warehouse_id' => $purchase->getWarehouseId(),
            'date' => $purchase->getDate(),
            'total' => $purchase->getTotal(),
        ]);

        $purchaseId = (int) $this->connection->lastInsertId();

        $this->saveItems(
            $purchaseId,
            $purchase
        );

        return $purchaseId;
    }

    private function saveItems(
        int $purchaseId,
        Purchase $purchase
    ): void {
        $statement = $this->connection->prepare(
            'INSERT INTO purchase_items (
                purchase_id,
                product_id,
                quantity,
                unit_price
            )
            VALUES (
                :purchase_id,
                :product_id,
                :quantity,
                :unit_price
            )'
        );

        foreach ($purchase->getPurchaseItems() as $item) {
            $statement->execute([
                'purchase_id' => $purchaseId,
                'product_id' => $item->getProductId(),
                'quantity' => $item->getQuantity()->getValue(),
                'unit_price' => $item->getUnitPrice(),
            ]);
        }
    }
}