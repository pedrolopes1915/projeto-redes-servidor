CREATE TABLE IF NOT EXISTS products (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nome VARCHAR(120) NOT NULL CHECK (length(trim(nome)) > 0),
    peso NUMERIC(10, 2) NOT NULL CHECK (peso > 0),
    valor NUMERIC(10, 2) NOT NULL CHECK (valor > 0),
    tipo VARCHAR(20) NOT NULL CHECK (tipo IN ('alimento', 'bebida', 'limpeza', 'eletronico', 'outro')),
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);
