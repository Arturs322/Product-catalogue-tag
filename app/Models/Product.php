<?php

namespace App\Models;

use Carbon\Carbon;

class Product
{
    private string $name;
    private string $category;
    private string $lastUpdated;
    private string $quantity;
    private string $createdAt;

    public function __construct(
        string  $name,
        string  $category,
        string  $lastUpdated,
        ?string $quantity = null,
        ?string $createdAt = null
    )
    {
        $this->name = $name;
        $this->category = $category;
        $this->lastUpdated = $lastUpdated;
        $this->quantity = $quantity;
        $this->createdAt = $createdAt ?? Carbon::now();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getLastUpdated(): string
    {
        return $this->lastUpdated;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'category' => $this->getCategory(),
            'lastUpdated' => $this->getLastUpdated(),
            'quantity' => $this->getQuantity(),
            'created_at' => $this->getCreatedAt()
        ];
    }
}