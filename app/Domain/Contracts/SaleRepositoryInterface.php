<?php

namespace App\Domain\Contracts;

use App\Domain\Entities\Sale;

interface SaleRepositoryInterface
{
    public function saveSale(Sale $sale): int;
}