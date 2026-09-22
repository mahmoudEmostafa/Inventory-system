<?php

namespace App\Http\Controllers;

use App\Domain\Factories\PurchaseFactory;
use App\Application\Services\PurchaseService;
use InvalidArgumentException;

class PurchaseController
{
    public function __construct(
        private PurchaseService $purchaseService
    ) {
    }

    public function store(): array
    {
        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (!is_array($data)) {
            return [
                'success' => false,
                'message' => 'Invalid JSON request.'
            ];
        }

        if (
            !isset($data['supplier_id']) ||
            !isset($data['warehouse_id']) ||
            !isset($data['date']) ||
            !isset($data['items'])
        ) {
            return [
                'success' => false,
                'message' => 'Missing required fields.'
            ];
        }

        if (!is_array($data['items']) || empty($data['items'])) {
            return [
                'success' => false,
                'message' => 'Purchase must contain at least one item.'
            ];
        }

        try {
            $items = [];

            foreach ($data['items'] as $item) {
                if (
                    !isset($item['product_id']) ||
                    !isset($item['quantity']) ||
                    !isset($item['unit_price'])
                ) {
                    throw new InvalidArgumentException(
                        'Each item must contain product_id, quantity and unit_price.'
                    );
                }

                $items[] = [
                    'productId' => (int) $item['product_id'],
                    'quantity' => (float) $item['quantity'],
                    'unitPrice' => (float) $item['unit_price'],
                ];
            }

            $purchase = PurchaseFactory::createPurchase(
                (int) $data['supplier_id'],
                (int) $data['warehouse_id'],
                $items,
                $data['date']
            );

            $purchaseId = $this->purchaseService->create(
                $purchase
            );

            return [
                'success' => true,
                'message' => 'Purchase created successfully.',
                'data' => [
                    'purchase_id' => $purchaseId,
                    'total' => $purchase->getTotal(),
                ]
            ];

        } catch (\Throwable $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }
}