<?php

namespace App\Http\Controllers;

use App\Application\Services\TransferService;
use App\Domain\Entities\Transfer;
use App\Domain\Entities\TransferItem;
use App\Domain\ValueObjects\Quantity;
use InvalidArgumentException;

class TransferController
{
    public function __construct(
        private TransferService $transferService
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
            !isset($data['from_warehouse_id']) ||
            !isset($data['to_warehouse_id']) ||
            !isset($data['date']) ||
            !isset($data['items'])
        ) {
            return [
                'success' => false,
                'message' => 'Missing required fields.'
            ];
        }

        if (
            !is_array($data['items']) ||
            empty($data['items'])
        ) {
            return [
                'success' => false,
                'message' => 'Transfer must contain at least one item.'
            ];
        }

        try {

            $transferItems = [];

            foreach ($data['items'] as $item) {

                if (
                    !isset($item['product_id']) ||
                    !isset($item['quantity'])
                ) {
                    throw new InvalidArgumentException(
                        'Each item must contain product_id and quantity.'
                    );
                }

                $transferItems[] = new TransferItem(
                    0,
                    (int) $item['product_id'],
                    new Quantity((float) $item['quantity'])
                );
            }

            $transfer = new Transfer(
                0,
                (int) $data['from_warehouse_id'],
                (int) $data['to_warehouse_id'],
                $transferItems,
                $data['date']
            );

            $transferId = $this->transferService->create(
                $transfer
            );

            return [
                'success' => true,
                'message' => 'Transfer created successfully.',
                'data' => [
                    'transfer_id' => $transferId,
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