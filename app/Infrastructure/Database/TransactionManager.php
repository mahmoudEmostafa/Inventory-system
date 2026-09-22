<?php

namespace App\Infrastructure\Database;

use App\Domain\Contracts\TransactionManagerInterface;
use Closure;
use PDO;
use Throwable;

class TransactionManager implements TransactionManagerInterface
{
    public function __construct(
        private PDO $connection
    ) {}

    public function transaction(Closure $callback): mixed
    {
        $this->connection->beginTransaction();

        try {
            $result = $callback();

            $this->connection->commit();

            return $result;

        } catch (Throwable $exception) {
            $this->connection->rollBack();

            throw $exception;
        }
    }
}