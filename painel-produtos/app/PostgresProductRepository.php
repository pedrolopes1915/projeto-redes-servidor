<?php

declare(strict_types=1);

final class PostgresProductRepository implements ProductRepository
{
    private ?PDO $connection = null;

    private function connection(): PDO
    {
        if ($this->connection === null) {
            $password = getenv('DB_PASSWORD');
            if ($password === false || $password === '') {
                throw new RuntimeException('DB_PASSWORD ausente.');
            }
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $port = getenv('DB_PORT') ?: '5432';
            $database = getenv('DB_NAME') ?: 'cadastro_produtos';
            $user = getenv('DB_USER') ?: 'app_produtos';
            $this->connection = new PDO(
                "pgsql:host={$host};port={$port};dbname={$database};connect_timeout=5",
                $user,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        }
        return $this->connection;
    }

    public function all(): array
    {
        return $this->connection()->query(
            'SELECT id, nome, peso, valor, tipo, created_at FROM public.products ORDER BY id DESC'
        )->fetchAll();
    }

    public function create(array $product): void
    {
        $statement = $this->connection()->prepare(
            'INSERT INTO public.products (nome, peso, valor, tipo) VALUES (:nome, :peso, :valor, :tipo)'
        );
        $statement->execute([
            'nome' => $product['nome'],
            'peso' => $product['peso'],
            'valor' => $product['valor'],
            'tipo' => $product['tipo'],
        ]);
    }
}
