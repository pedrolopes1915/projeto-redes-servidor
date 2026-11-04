<?php

declare(strict_types=1);

final class SessionProductRepository implements ProductRepository
{
    public function all(): array
    {
        return array_reverse($_SESSION['products'] ?? []);
    }

    public function create(array $product): void
    {
        $product['id'] = ($_SESSION['next_product_id'] ?? 0) + 1;
        $product['created_at'] = date('c');
        $_SESSION['products'][] = $product;
        $_SESSION['next_product_id'] = $product['id'];
    }
}
