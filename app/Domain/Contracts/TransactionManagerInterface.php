<?php

namespace App\Domain\Contracts;

use Closure;

interface TransactionManagerInterface
{
    public function transaction(Closure $callback): mixed;
}