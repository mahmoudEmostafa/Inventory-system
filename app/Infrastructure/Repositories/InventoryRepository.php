<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Contracts\InventoryRepositoryInterface;
use App\Domain\Entities\Inventory;
use App\Domain\ValueObjects\Quantity;
use App\Infrastructure\Database\Database;
use PDO;

class InventoryRepository implements InventoryRepositoryInterface
{
    private PDO $connection;

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    public function findByProductAndWarehouse(
        int $productId,
        int $warehouseId
    ): ?Inventory {
        $statement = $this->connection->prepare(
            'SELECT id, product_id, warehouse_id, quantity
             FROM inventory
             WHERE product_id = :product_id
             AND warehouse_id = :warehouse_id
             LIMIT 1'
        );

        $statement->execute([
            'product_id' => $productId,
            'warehouse_id' => $warehouseId,
        ]);

        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return new Inventory(
            (int) $row['id'],
            (int) $row['product_id'],
            (int) $row['warehouse_id'],
            new Quantity((float) $row['quantity'])
        );
    }

    public function create(Inventory $inventory): int
    {
        $statement = $this->connection->prepare(
            'INSERT INTO inventory
                (product_id, warehouse_id, quantity)
             VALUES
                (:product_id, :warehouse_id, :quantity)'
        );

        $statement->execute([
            'product_id' => $inventory->getProductId(),
            'warehouse_id' => $inventory->getWarehouseId(),
            'quantity' => $inventory->getQuantity()->getValue(),
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function update(Inventory $inventory): void
    {
        $statement = $this->connection->prepare(
            'UPDATE inventory
             SET quantity = :quantity
             WHERE id = :id'
        );

        $statement->execute([
            'quantity' => $inventory->getQuantity()->getValue(),
            'id' => $inventory->getId(),
        ]);
    }
}