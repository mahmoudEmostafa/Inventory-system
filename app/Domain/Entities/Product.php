<?php

namespace App\Domain\Entities;

use App\Domain\Enums\Unit;
use App\Domain\Exceptions\InvalidProductException;

class Product
{
    private int $id;
    private string $code;
    private string $name;
    private Unit $unit;
    private bool $isActive;

    public function __construct(
        int $id,
        string $code,
        string $name,
        Unit $unit,
        bool $isActive = true
    ) {
        $this->validateCode($code);
        $this->validateName($name);

        $this->id = $id;
        $this->code = trim($code);
        $this->name = trim($name);
        $this->unit = $unit;
        $this->isActive = $isActive;
    }

    private function validateCode(string $code): void
    {
        if (trim($code) === '') {
            throw new InvalidProductException(
                'Product code cannot be empty.'
            );
        }
    }

    private function validateName(string $name): void
    {
        if (trim($name) === '') {
            throw new InvalidProductException(
                'Product name cannot be empty.'
            );
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUnit(): Unit
    {
        return $this->unit;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }
}