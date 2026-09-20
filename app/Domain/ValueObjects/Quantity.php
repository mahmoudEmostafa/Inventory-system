<?php

namespace App\Domain\ValueObjects;

use App\Domain\Exceptions\InvalidQuantityException;

class Quantity
{
    private float $value;

    public function __construct(float $value)
    {
        if ($value < 0) {
            throw new InvalidQuantityException(
                'Quantity cannot be negative.'
            );
        }

        $this->value = $value;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function isZero(): bool
    {
        return $this->value === 0.0;
    }
}