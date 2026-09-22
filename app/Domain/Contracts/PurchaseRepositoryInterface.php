<?php
namespace App\Domain\Contracts;

use App\Domain\Entities\Purchase;

interface PurchaseRepositoryInterface
{
    
    public function savePurchase(Purchase $purchase): int;
}