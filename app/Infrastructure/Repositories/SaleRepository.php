<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Contracts\SaleRepositoryInterface;
use App\Domain\Entities\Sale;
use App\Infrastructure\Database\Database;
use PDO;

class SaleRepository implements SaleRepositoryInterface
{
    private PDO $connection;

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    public function saveSale(Sale $sale): int
    {
        $statement = $this->connection->prepare(
            'INSERT INTO sales (
                customer_id,
                warehouse_id,
                date,
                total
            )
            VALUES (
                :customer_id,
                :warehouse_id,
                :date,
                :total
            )'
        );

        $statement->execute([
            'customer_id' => $sale->getCustomerId(),
            'warehouse_id' => $sale->getWarehouseId(),
            'date' => $sale->getDate(),
            'total' => $sale->getTotal(),
        ]);

        $saleId = (int) $this->connection->lastInsertId();

        $this->saveItems(
            $saleId,
            $sale
        );

        return $saleId;
    }

    private function saveItems(
        int $saleId,
        Sale $sale
    ): void {
        $statement = $this->connection->prepare(
            'INSERT INTO sale_items (
                sale_id,
                product_id,
                quantity,
                unit_price
            )
            VALUES (
                :sale_id,
                :product_id,
                :quantity,
                :unit_price
            )'
        );

        foreach ($sale->getSaleItems() as $item) {
            $statement->execute([
                'sale_id' => $saleId,
                'product_id' => $item->getProductId(),
                'quantity' => $item->getQuantity()->getValue(),
                'unit_price' => $item->getUnitPrice(),
            ]);
        }
    }
}