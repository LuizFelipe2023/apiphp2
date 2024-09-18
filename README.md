# Projeto de Gestão de Produtos e Pedidos

Este projeto é uma aplicação web para gerenciar produtos e pedidos utilizando PHP e uma API RESTful. O sistema oferece funcionalidades para adicionar, editar e excluir produtos e pedidos, além de listar todos os itens cadastrados. Inclui também funcionalidades de autenticação e gerenciamento de usuários.

## Tecnologias Utilizadas

- **PHP:** Linguagem principal do backend.
- **MySQL:** Sistema de gerenciamento de banco de dados relacional.
- **API RESTful:** Para operações CRUD (Criar, Ler, Atualizar, Deletar) com produtos e pedidos, além de um sistema de login e autenticação de usuários.

## Estrutura do Projeto

### Backend

- **PHP:** Lógica de negócios e interação com o banco de dados.
- **API:** Endpoints para manipulação dos dados dos produtos e pedidos.

## Funcionalidades

### Listagem de Produtos

- **Descrição:** Exibe uma tabela com todos os produtos cadastrados, mostrando nome, valor e categoria.
- **Ações:**
  - **Editar:** Abre um modal para editar as informações do produto.
  - **Excluir:** Abre um modal de confirmação para excluir o produto.

### Adicionar Produto

- **Descrição:** Modal para inserir um novo produto.
- **Campos:**
  - Nome
  - Valor
  - Categoria
- **Ação:** Envia uma requisição POST para a API para adicionar o produto.

### Editar Produto

- **Descrição:** Modal para editar as informações de um produto existente.
- **Campos:**
  - Nome
  - Valor
  - Categoria
- **Ação:** Atualiza os dados do produto via requisição POST para a API.

### Excluir Produto

- **Descrição:** Modal de confirmação para excluir um produto.
- **Ação:** Envia uma requisição DELETE para a API para remover o produto.

### Listagem de Pedidos

- **Descrição:** Exibe uma tabela com todos os pedidos cadastrados, mostrando ID, nome do cliente, data e status do pedido.
- **Ações:**
  - **Editar:** Abre um modal para editar as informações do pedido.
  - **Excluir:** Abre um modal de confirmação para excluir o pedido.

### Adicionar Pedido

- **Descrição:** Modal para inserir um novo pedido.
- **Campos:**
  - Nome do Cliente
  - Data
  - Status
- **Ação:** Envia uma requisição POST para a API para adicionar o pedido.

### Editar Pedido

- **Descrição:** Modal para editar as informações de um pedido existente.
- **Campos:**
  - Nome do Cliente
  - Data
  - Status
- **Ação:** Atualiza os dados do pedido via requisição POST para a API.

### Excluir Pedido

- **Descrição:** Modal de confirmação para excluir um pedido.
- **Ação:** Envia uma requisição DELETE para a API para remover o pedido.

## Autenticação e Gerenciamento de Usuários

### Criar Usuário

- **Descrição:** Endpoint para a criação de novos usuários.
- **Método:** POST
- **Rota:** `/usuarios`
- **Campos Requeridos:**
  - Nome
  - Email
  - Senha
  - CPF
  - Endereço
- **Ação:** Envia uma requisição POST para a API para criar um novo usuário.

### Login

- **Descrição:** Endpoint para autenticação de usuários.
- **Método:** POST
- **Rota:** `/login`
- **Campos Requeridos:**
  - Email
  - Senha
- **Ação:** Envia uma requisição POST para a API para autenticar o usuário e iniciar a sessão. Retorna um token de autenticação se bem-sucedido.

### Logout

- **Descrição:** Endpoint para encerrar a sessão do usuário.
- **Método:** POST
- **Rota:** `/logout`
- **Ação:** Envia uma requisição POST para a API para destruir a sessão do usuário e finalizar o login.

### Solicitação de Redefinição de Senha

- **Descrição:** Endpoint para solicitar a redefinição de senha.
- **Método:** POST
- **Rota:** `/solicitar-redefinicao-senha`
- **Campos Requeridos:**
  - Email
  - Nova Senha
- **Ação:** Envia uma requisição POST para a API para verificar a existência do usuário. Se ele existir, envia um token de redefinição por e-mail ao usuário.

### Redefinição de Senha

- **Descrição:** Endpoint para redefinir a senha do usuário.
- **Método:** POST
- **Rota:** `/redefinir-senha`
- **Campos Requeridos:**
  - Token
  - Nova Senha
  - Confirmação da Nova Senha
- **Ação:** A API verifica o token de redefinição enviado por e-mail. Se o token for válido, permite a troca de senha. O usuário deve digitar a nova senha e confirmá-la.

## Instalação e Execução

1. **Baixe o repositório do projeto.**
2. **No console, execute `composer install` para instalar as dependências.**
3. **Instale as bibliotecas necessárias com os comandos:**
   - `composer require firebase/php-jwt`
   - `composer require vlucas/phpdotenv`
4. **Para rodar a aplicação, coloque o arquivo `index.php` na pasta `public` e execute o comando:**
   - `php -S localhost:8000`
5. **Pronto! Agora você pode testar a aplicação usando o Postman ou Insomnia.**

