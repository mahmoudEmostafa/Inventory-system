<?php

namespace App\Application\Services;

use App\Domain\Contracts\PurchaseRepositoryInterface;
use App\Domain\Contracts\StockMovementRepositoryInterface;
use App\Domain\Contracts\TransactionManagerInterface;
use App\Domain\Entities\Purchase;
use App\Domain\Entities\StockMovement;
use App\Domain\Enums\StockMovementType;
use App\Domain\ValueObjects\Quantity;
use App\Domain\Enums\StockMovementReferenceType;
use DateTimeImmutable;
class PurchaseService
{
    public function __construct(
        private PurchaseRepositoryInterface $purchaseRepository,
        private InventoryService $inventoryService,
        private StockMovementRepositoryInterface $stockMovementRepository,
        private TransactionManagerInterface $transactionManager
    ) {
    }

    public function create(Purchase $purchase, ?int $createdBy = null): int
    {
        return $this->transactionManager->transaction(
            function () use ($purchase, $createdBy) {

                $purchaseId = $this->purchaseRepository
                    ->savePurchase($purchase);

                foreach ($purchase->getPurchaseItems() as $item) {

                    $this->inventoryService->increase(
                        $item->getProductId(),
                        $purchase->getWarehouseId(),
                        $item->getQuantity()->getValue()
                    );

                    $movement = new StockMovement(
                        0,
                        $item->getProductId(),
                        $purchase->getWarehouseId(),
                        StockMovementType::PURCHASE_IN,
                        new Quantity(
                            $item->getQuantity()->getValue()
                        ),
                        StockMovementReferenceType::PURCHASE,
                        $purchaseId,
                        $createdBy,
                        new DateTimeImmutable()
                    );

                    $this->stockMovementRepository
                        ->saveStockMovement($movement);
                }
                

                return $purchaseId;
            }
        );
    }
}