<?php

declare(strict_types=1);

interface ProductRepository
{
    public function all(): array;
    public function create(array $product): void;
}
