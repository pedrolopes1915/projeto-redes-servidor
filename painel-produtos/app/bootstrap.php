<?php

declare(strict_types=1);

require_once __DIR__ . '/ProductRepository.php';
require_once __DIR__ . '/PostgresProductRepository.php';

session_start([
    'use_strict_mode' => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'cookie_secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
]);

$repository = new PostgresProductRepository();
$categories = [
    'alimento' => 'Alimento',
    'bebida' => 'Bebida',
    'limpeza' => 'Limpeza',
    'eletronico' => 'Eletrônico',
    'outro' => 'Outro',
];

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function input(string $key): string
{
    return isset($_POST[$key]) && is_string($_POST[$key]) ? trim($_POST[$key]) : '';
}

function validDecimal(string $value): bool
{
    return preg_match('/^\d{1,8}(?:\.\d{1,2})?$/D', $value) === 1 && (float) $value >= 0.01;
}
