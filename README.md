# Projeto de Gestão de Produtos e Pedidos

Este projeto é uma aplicação web para gerenciar produtos e pedidos usando PHP, Bootstrap 5 e uma API RESTful. O sistema permite adicionar, editar e apagar produtos e pedidos, além de listar todos os produtos e pedidos cadastrados. Também inclui funcionalidades de autenticação e gerenciamento de usuários.

## Tecnologias Utilizadas

- **PHP:** Linguagem principal do backend.
- **API RESTful:** Para operações CRUD (Criar, Ler, Atualizar, Deletar) com produtos e pedidos, e um sistema de login e autenticação de usuarios.

## Estrutura do Projeto

### Backend

- **PHP:** Lógica de negócios e interação com o banco de dados.
- **API:** Endpoints para manipulação de dados dos produtos e pedidos.

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

- **Descrição:** Endpoint para criação de novos usuários.
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

- **Descrição:** Endpoint para redefinição de senha do usuário.
- **Método:** POST
- **Rota:** `/solicitar-redefinicao-senha`
- **Campos Requeridos:**
  - Email
  - Nova Senha
- **Ação:** Envia uma requisição POST para a API para verificar o a existencia do usuario, se ele existir envia um token de redifinição de email por email ao usuário.
  
  ### Redefinição de Senha

- **Descrição:** Endpoint para realizar a troca de senha.
- **Método:** POST
- **Rota:** `/redefinir-senha`
- **Campos Requeridos:**
  - Token
  - Nova Senha
  - Confirmação da nova senha
- **Ação:** A Api faz uma solicitação POST pedindo do usuario o token de redifinição enviado via email, sendo válido autoriza a troca de senha, o usuario digita a senha nova e depois redigita pra confirmar a senha nova. 

### Para Instalar e Executar a aplicação

1 - **Baixe o repositorio**
2 - **No seu console, rode o Composer Install**
3 - **Instale essas bibliotecas aqui: composer require firebase/php-jwt e essa aqui composer require vlucas/phpdotenv. Com elas duas vcs poderam usar o JWT E .env no php.**
4 - **Para rodar aplicação, coloque na pasta public e rode esse comando: php -S localhost:8000**
