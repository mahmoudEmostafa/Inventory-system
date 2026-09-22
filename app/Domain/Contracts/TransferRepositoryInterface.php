<?php

namespace App\Domain\Contracts;

use App\Domain\Entities\Transfer;

interface TransferRepositoryInterface
{
    public function saveTransfer(Transfer $transfer): int;
}