<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Contracts\StockMovementRepositoryInterface;
use App\Domain\Entities\StockMovement;
use App\Infrastructure\Database\Database;
use PDO;

class StockMovementRepository implements StockMovementRepositoryInterface
{
    private PDO $connection;

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    public function saveStockMovement(
        StockMovement $stockMovement
    ): int {
        $statement = $this->connection->prepare(
            'INSERT INTO stock_movements (
                product_id,
                warehouse_id,
                type,
                quantity,
                reference_type,
                reference_id,
                created_by,
                created_at
            )
            VALUES (
                :product_id,
                :warehouse_id,
                :type,
                :quantity,
                :reference_type,
                :reference_id,
                :created_by,
                :created_at
            )'
        );

        $statement->execute([
            'product_id' => $stockMovement->getProductId(),
            'warehouse_id' => $stockMovement->getWarehouseId(),
            'type' => $stockMovement->getType()->value,
            'quantity' => $stockMovement->getQuantity()->getValue(),
            'reference_type' => $stockMovement->getReferenceType()->value,
            'reference_id' => $stockMovement->getReferenceId(),
            'created_by' => $stockMovement->getCreatedBy(),
            'created_at' => $stockMovement->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);

        return (int) $this->connection->lastInsertId();
    }
}