# Cadastro de produtos

Aplicação PHP com cadastro e listagem usando PostgreSQL. A integração foi implementada; a validação com o banco local deve ser feita após configurar as credenciais.

## Requisitos

PHP 8.0 ou superior, extensão pdo_pgsql, PostgreSQL com a tabela de database/schema.sql e usuário app_produtos com permissões SELECT e INSERT na tabela e USAGE na sequência.

## Windows com XAMPP

Execute no PowerShell, dentro da pasta do projeto:

```powershell
powershell -ExecutionPolicy Bypass -File .\iniciar.ps1
```

Informe a senha de app_produtos no proprio terminal. A senha fica apenas no ambiente do processo durante a execução e não é gravada no projeto. Abra http://localhost:8000. Para encerrar, pressione Ctrl+C.

## Configuração no servidor

Configure DB_HOST, DB_PORT, DB_NAME, DB_USER e DB_PASSWORD no ambiente do processo PHP. DB_PASSWORD é obrigatória. Os padrões dos demais campos são 127.0.0.1, 5432, cadastro_produtos e app_produtos. Use public como raiz do servidor web. O script iniciar.ps1 é somente para desenvolvimento local.

## Estrutura

- app/ProductRepository.php: contrato de cadastro e consulta.
- app/PostgresProductRepository.php: acesso ao PostgreSQL por PDO com consultas parametrizadas.
- app/bootstrap.php: inicialização.
- public/index.php: formulário, validação e listagem.
- database/schema.sql: estrutura da tabela.

O armazenamento em sessão deixou de ser usado para produtos. Cadastros antigos da sessão não são migrados. A sessão continua sendo usada para proteger o formulário e exibir a mensagem de sucesso.

## Verificação

Cadastre um produto pelo site e consulte SELECT * FROM products; no pgAdmin. Abra o site em outro navegador e confira se o produto continua disponível. A instalação no servidor, HTTPS e backups ainda precisam ser configurados.
