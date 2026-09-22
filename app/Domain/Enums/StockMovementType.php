<?php

namespace App\Domain\Enums;

enum StockMovementType: string
{
    case PURCHASE_IN = 'purchase_in';
    case SALE_OUT = 'sale_out';
    case ADJUSTMENT_IN = 'adjustment_in';
    case ADJUSTMENT_OUT = 'adjustment_out';
    case TRANSFER_IN = 'transfer_in';
    case TRANSFER_OUT = 'transfer_out';
}