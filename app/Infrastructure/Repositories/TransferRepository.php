<?php
namespace App\Infrastructure\Repositories;

use App\Domain\Contracts\TransferRepositoryInterface;
use App\Domain\Entities\Transfer;
use App\Infrastructure\Database\Database;
use PDO;

class TransferRepository implements TransferRepositoryInterface
{
    private PDO $connection;

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    public function saveTransfer(Transfer $transfer): int
    {
        $statement = $this->connection->prepare(
            'INSERT INTO stock_transfers (
                source_warehouse_id,
                destination_warehouse_id,
                date
            )
            VALUES (
                :source_warehouse_id,
                :destination_warehouse_id,
                :date
            )'
        );

        $statement->execute([
            'source_warehouse_id' => $transfer->getFromWarehouseId(),
            'destination_warehouse_id' => $transfer->getToWarehouseId(),
            'date' => $transfer->getDate(),
        ]);

        $transferId = (int) $this->connection->lastInsertId();

        $this->saveItems(
            $transferId,
            $transfer
        );

        return $transferId;
    }

    private function saveItems(
        int $transferId,
        Transfer $transfer
    ): void {
        $statement = $this->connection->prepare(
            'INSERT INTO stock_transfer_items (
                transfer_id,
                product_id,
                quantity
            )
            VALUES (
                :transfer_id,
                :product_id,
                :quantity
            )'
        );

        foreach ($transfer->getTransferItems() as $item) {
            $statement->execute([
                'transfer_id' => $transferId,
                'product_id' => $item->getProductId(),
                'quantity' => $item->getQuantity()->getValue(),
            ]);
        }
    }
}

