# Projeto de Gestão de Produtos e Pedidos

Este projeto é uma aplicação web para gerenciar produtos e pedidos usando PHP, Bootstrap 5 e uma API RESTful. O sistema permite adicionar, editar e apagar produtos e pedidos, além de listar todos os produtos e pedidos cadastrados.

## Tecnologias Utilizadas

- **PHP:** Linguagem principal do backend.
- **Bootstrap 5:** Framework CSS para estilização.
- **JavaScript:** Manipulação dinâmica dos dados e interação com a API.
- **API RESTful:** Para operações CRUD (Criar, Ler, Atualizar, Deletar) com produtos e pedidos.

## Estrutura do Projeto

### Frontend

- **HTML:** Estrutura da página.
- **CSS (Bootstrap 5):** Estilo da página.
- **JavaScript:** Interação com modais e manipulação de formulários.

### Backend

- **PHP:** Lógica de negócios e interação com o banco de dados.
- **API:** Endpoints para manipulação de dados dos produtos e pedidos.

## Funcionalidades

1. **Listagem de Produtos:**
   - Exibe uma tabela com todos os produtos cadastrados, mostrando nome, valor e categoria.
   - Botões de ação para editar e apagar cada produto.

2. **Adicionar Produto:**
   - Modal para inserir um novo produto com campos para nome, valor e categoria.
   - Envia uma requisição POST para a API para adicionar o produto.

3. **Editar Produto:**
   - Modal para editar as informações de um produto existente.
   - Atualiza os dados do produto via requisição POST para a API.

4. **Excluir Produto:**
   - Modal de confirmação para excluir um produto.
   - Envia uma requisição POST para a API para remover o produto.

5. **Listagem de Pedidos:**
   - Exibe uma tabela com todos os pedidos cadastrados, mostrando ID, nome do cliente, data e status do pedido.
   - Botões de ação para editar e apagar cada pedido.

6. **Adicionar Pedido:**
   - Modal para inserir um novo pedido com campos para nome do cliente, data e status.
   - Envia uma requisição POST para a API para adicionar o pedido.

7. **Editar Pedido:**
   - Modal para editar as informações de um pedido existente.
   - Atualiza os dados do pedido via requisição POST para a API.

8. **Excluir Pedido:**
   - Modal de confirmação para excluir um pedido.
   - Envia uma requisição POST para a API para remover o pedido.
