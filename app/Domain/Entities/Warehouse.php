<?php

namespace App\Domain\Entities;

use App\Domain\Exceptions\InvalidWarehouseException;

class Warehouse
{
    private int $id;
    private string $name;
    private ?string $location;
    private bool $isActive;

    public function __construct(
        int $id,
        string $name,
        ?string $location = null,
        bool $isActive = true
    ) {
        $this->validateName($name);

        $this->id = $id;
        $this->name = trim($name);
        $this->location = $location !== null
            ? trim($location)
            : null;
        $this->isActive = $isActive;
    }

    private function validateName(string $name): void
    {
        if (trim($name) === '') {
            throw new InvalidWarehouseException(
                'Warehouse name cannot be empty.'
            );
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLocation(): ?string
    {
        return $this->location;
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