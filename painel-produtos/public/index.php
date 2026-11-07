<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

header('Cache-Control: no-store');
set_exception_handler(function (Throwable $exception): void {
    error_log('Falha na aplicação: ' . get_class($exception) . ' (código ' . $exception->getCode() . ').');
    http_response_code(500);
    echo '<!DOCTYPE html><html lang="pt-BR"><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Serviço indisponível</title><link rel="stylesheet" href="style.css"><main class="container conteudo-principal"><section class="painel"><h1>Não foi possível concluir a operação</h1><p>Tente novamente mais tarde.</p><a href="index.php">Voltar ao cadastro</a></section></main></html>';
});
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));
$errors = [];
$values = ['nome' => '', 'peso' => '', 'valor' => '', 'tipo' => ''];
$success = $_SESSION['success'] ?? null;
unset($_SESSION['success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $key => $value) {
        $values[$key] = input($key);
    }

    if (!hash_equals($_SESSION['csrf'], input('csrf'))) {
        http_response_code(403);
        $errors[] = 'A sessão do formulário expirou. Tente novamente.';
    } else {
        $values['peso'] = str_replace(',', '.', $values['peso']);
        $values['valor'] = str_replace(',', '.', $values['valor']);

        if ($values['nome'] === '' || preg_match('/^.{1,120}$/usD', $values['nome']) !== 1) {
            $errors[] = 'Informe um nome com até 120 caracteres.';
        }
        if (!validDecimal($values['peso'])) {
            $errors[] = 'Informe um peso entre 0,01 e 99.999.999,99 kg, com até duas casas decimais.';
        }
        if (!validDecimal($values['valor'])) {
            $errors[] = 'Informe um valor entre R$ 0,01 e R$ 99.999.999,99, com até duas casas decimais.';
        }
        if (!array_key_exists($values['tipo'], $categories)) {
            $errors[] = 'Selecione uma categoria válida.';
        }

        if (!$errors) {
            $values['peso'] = number_format((float) $values['peso'], 2, '.', '');
            $values['valor'] = number_format((float) $values['valor'], 2, '.', '');
            $repository->create($values);
            $_SESSION['success'] = 'Produto cadastrado com sucesso.';
            header('Location: index.php', true, 303);
            exit;
        }
        http_response_code(422);
    }
}

$products = $repository->all();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="cabecalho">
        <div class="container cabecalho-conteudo">
            <span class="marca">Sistema para Cadastrar Produtos</span>
            <?php if (is_file(__DIR__ . '/imagens/logo.png')): ?>
                <img src="imagens/logo.png" alt="Logo UNIPÊ">
            <?php endif; ?>
            <span class="identificacao">Unipê Cadeira: DevOps</span>
        </div>
    </header>
    <main class="container conteudo-principal">
        <div class="introducao">
            <h1>Cadastro de Produtos</h1>
        </div>
        <section class="painel" aria-labelledby="titulo-formulario">
            <h2 id="titulo-formulario">Novo produto</h2>
            <?php if ($success): ?>
                <p class="mensagem sucesso" role="status"><?= escape($success) ?></p>
            <?php endif; ?>
            <?php if ($errors): ?>
                <div class="mensagem erro" role="alert">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= escape($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="index.php" method="post">
                <input type="hidden" name="csrf" value="<?= escape($_SESSION['csrf']) ?>">
                <div class="campo">
                    <label for="nome">Nome do produto</label>
                    <input type="text" id="nome" name="nome" maxlength="120" placeholder="Ex.: Arroz integral" value="<?= escape($values['nome']) ?>" required>
                </div>
                <div class="linha-campos">
                    <div class="campo">
                        <label for="peso">Peso (kg)</label>
                        <input type="number" id="peso" name="peso" min="0.01" max="99999999.99" step="0.01" placeholder="Ex.: 1,00" value="<?= escape($values['peso']) ?>" required>
                    </div>
                    <div class="campo">
                        <label for="valor">Valor (R$)</label>
                        <input type="number" id="valor" name="valor" min="0.01" max="99999999.99" step="0.01" placeholder="Ex.: 12,50" value="<?= escape($values['valor']) ?>" required>
                    </div>
                </div>
                <div class="campo">
                    <label for="tipo">Tipo de produto</label>
                    <select id="tipo" name="tipo" required>
                        <option value="" disabled <?= $values['tipo'] === '' ? 'selected' : '' ?>>Selecione uma categoria</option>
                        <?php foreach ($categories as $key => $label): ?>
                            <option value="<?= escape($key) ?>" <?= $values['tipo'] === $key ? 'selected' : '' ?>><?= escape($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit">Cadastrar produto</button>
            </form>
            <p class="observacao">Os produtos cadastrados são armazenados no banco de dados.</p>
        </section>
        <section class="painel lista-produtos" aria-labelledby="titulo-produtos">
            <h2 id="titulo-produtos">Produtos cadastrados</h2>
            <?php if (!$products): ?>
                <p class="vazio">Nenhum produto cadastrado ainda.</p>
            <?php else: ?>
                <div class="tabela-container" tabindex="0" role="region" aria-label="Lista de produtos">
                    <table>
                        <thead>
                            <tr><th scope="col">Nome</th><th scope="col">Peso (kg)</th><th scope="col">Valor (R$)</th><th scope="col">Tipo</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= escape($product['nome']) ?></td>
                                    <td><?= number_format((float) $product['peso'], 2, ',', '.') ?></td>
                                    <td><?= number_format((float) $product['valor'], 2, ',', '.') ?></td>
                                    <td><?= escape($categories[$product['tipo']]) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>
    <footer class="rodape"><div class="container"></div></footer>
</body>
</html>
