<?php

namespace App\Domain\Enums;

enum StockMovementReferenceType: string
{
    case PURCHASE = 'purchase';
    case SALE = 'sale';
    case TRANSFER = 'transfer';
    case ADJUSTMENT = 'adjustment';
}