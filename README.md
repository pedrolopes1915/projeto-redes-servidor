# 📦 Cadastro de Produtos

Aplicação web em PHP para cadastro e consulta de produtos, com armazenamento em PostgreSQL e execução em servidor Linux.

Projeto desenvolvido para a disciplina de Redes de Computadores e Administração de Sistemas Operacionais.

## ⚙️ Funcionalidades

- Cadastro de nome, peso, valor e categoria.
- Listagem dos produtos cadastrados.
- Validação dos campos no servidor.
- Persistência dos dados no PostgreSQL.
- Acesso à aplicação por HTTPS.
- Backup automatizado do banco de dados.

## 🛠️ Tecnologias

- PHP
- HTML5 e CSS3
- PostgreSQL
- Linux
- Shell Script

## 📁 Organização

```text
app/          Lógica da aplicação e acesso aos dados
database/     Estrutura SQL do banco
public/       Página principal, estilos e imagens
```

## 🔒 Segurança

A aplicação utiliza validação no servidor e tratamento dos dados exibidos nas páginas. O acesso ao banco é restrito à aplicação, com usuário sem privilégios administrativos.

Senhas e credenciais não devem ser incluídas no repositório.

## 💾 Backup

O backup utiliza `pg_dump`, compactação em `.tar.gz` e envio ao servidor de arquivos. A execução diária é agendada pelo Cron.

## 🎓 Objetivo

Demonstrar a integração entre aplicação web, banco de dados e serviços Linux, aplicando conceitos de redes, segurança e automação.
