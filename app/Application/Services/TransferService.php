<?php

namespace App\Application\Services;

use App\Domain\Contracts\StockMovementRepositoryInterface;
use App\Domain\Contracts\TransactionManagerInterface;
use App\Domain\Contracts\TransferRepositoryInterface;
use App\Domain\Entities\StockMovement;
use App\Domain\Entities\Transfer;
use App\Domain\Enums\StockMovementReferenceType;
use App\Domain\Enums\StockMovementType;
use App\Domain\ValueObjects\Quantity;
use DateTimeImmutable;

class TransferService
{
    public function __construct(
        private TransferRepositoryInterface $transferRepository,
        private InventoryService $inventoryService,
        private StockMovementRepositoryInterface $stockMovementRepository,
        private TransactionManagerInterface $transactionManager
    ) {
    }

    public function create(
        Transfer $transfer,
        ?int $createdBy = null
    ): int {
        return $this->transactionManager->transaction(
            function () use ($transfer, $createdBy) {

                $transferId = $this->transferRepository
                    ->saveTransfer($transfer);

                foreach ($transfer->getTransferItems() as $item) {

                    $quantity = $item->getQuantity()->getValue();

                    /*
                     * 1. Remove stock from source warehouse
                     */
                    $this->inventoryService->decrease(
                        $item->getProductId(),
                        $transfer->getFromWarehouseId(),
                        $quantity
                    );

                    /*
                     * 2. Add stock to destination warehouse
                     */
                    $this->inventoryService->increase(
                        $item->getProductId(),
                        $transfer->getToWarehouseId(),
                        $quantity
                    );

                    /*
                     * 3. Record OUT movement
                     */
                    $outMovement = new StockMovement(
                        0,
                        $item->getProductId(),
                        $transfer->getFromWarehouseId(),
                        StockMovementType::TRANSFER_OUT,
                        new Quantity($quantity),
                        StockMovementReferenceType::TRANSFER,
                        $transferId,
                        $createdBy,
                        new DateTimeImmutable()
                    );

                    $this->stockMovementRepository
                        ->saveStockMovement($outMovement);

                    /*
                     * 4. Record IN movement
                     */
                    $inMovement = new StockMovement(
                        0,
                        $item->getProductId(),
                        $transfer->getToWarehouseId(),
                        StockMovementType::TRANSFER_IN,
                        new Quantity($quantity),
                        StockMovementReferenceType::TRANSFER,
                        $transferId,
                        $createdBy,
                        new DateTimeImmutable()
                    );

                    $this->stockMovementRepository
                        ->saveStockMovement($inMovement);
                }

                return $transferId;
            }
        );
    }
}