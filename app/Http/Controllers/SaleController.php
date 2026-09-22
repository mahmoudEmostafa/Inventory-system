<?php

namespace App\Http\Controllers;

use App\Application\Services\SaleService;
use App\Domain\Factories\SaleFactory;
use InvalidArgumentException;

class SaleController
{
    public function __construct(
        private SaleService $saleService
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
            !isset($data['customer_id']) ||
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
                'message' => 'Sale must contain at least one item.'
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

            $sale = SaleFactory::createSale(
                (int) $data['customer_id'],
                (int) $data['warehouse_id'],
                $items,
                $data['date']
            );

            $saleId = $this->saleService->create(
                $sale
            );

            return [
                'success' => true,
                'message' => 'Sale created successfully.',
                'data' => [
                    'sale_id' => $saleId,
                    'total' => $sale->getTotal(),
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