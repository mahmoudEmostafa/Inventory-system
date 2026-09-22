<?php

namespace App\Application\Services;

use App\Domain\Contracts\SaleRepositoryInterface;
use App\Domain\Contracts\StockMovementRepositoryInterface;
use App\Domain\Contracts\TransactionManagerInterface;
use App\Domain\Entities\Sale;
use App\Domain\Entities\StockMovement;
use App\Domain\Enums\StockMovementReferenceType;
use App\Domain\Enums\StockMovementType;
use App\Domain\ValueObjects\Quantity;
use DateTimeImmutable;

class SaleService
{
    public function __construct(
        private SaleRepositoryInterface $saleRepository,
        private InventoryService $inventoryService,
        private StockMovementRepositoryInterface $stockMovementRepository,
        private TransactionManagerInterface $transactionManager
    ) {
    }

    public function create(
        Sale $sale,
        ?int $createdBy = null
    ): int {
        return $this->transactionManager->transaction(
            function () use ($sale, $createdBy) {

                $saleId = $this->saleRepository
                    ->saveSale($sale);

                foreach ($sale->getSaleItems() as $item) {

                    $this->inventoryService->decrease(
                        $item->getProductId(),
                        $sale->getWarehouseId(),
                        $item->getQuantity()->getValue()
                    );

                    $movement = new StockMovement(
                        0,
                        $item->getProductId(),
                        $sale->getWarehouseId(),
                        StockMovementType::SALE_OUT,
                        new Quantity(
                            $item->getQuantity()->getValue()
                        ),
                        StockMovementReferenceType::SALE,
                        $saleId,
                        $createdBy,
                        new DateTimeImmutable()
                    );

                    $this->stockMovementRepository
                        ->saveStockMovement($movement);
                }

                return $saleId;
            }
        );
    }
}