Projeto de Gestão de Produtos e Pedidos
Este projeto é uma aplicação web para gerenciar produtos e pedidos usando PHP, Bootstrap 5 e uma API RESTful. O sistema permite adicionar, editar e apagar produtos e pedidos, além de listar todos os produtos e pedidos cadastrados.

Tecnologias Utilizadas
PHP: Linguagem principal do backend.
Bootstrap 5: Framework CSS para estilização.
JavaScript: Manipulação dinâmica dos dados e interação com a API.
API RESTful: Para operações CRUD (Criar, Ler, Atualizar, Deletar) com produtos e pedidos.
Estrutura do Projeto
Frontend
HTML: Estrutura da página.
CSS (Bootstrap 5): Estilo da página.
JavaScript: Interação com modais e manipulação de formulários.
Backend
PHP: Lógica de negócios e interação com o banco de dados.
API: Endpoints para manipulação de dados dos produtos e pedidos.
Funcionalidades
Listagem de Produtos:

Exibe uma tabela com todos os produtos cadastrados, mostrando nome, valor e categoria.
Botões de ação para editar e apagar cada produto.
Adicionar Produto:

Modal para inserir um novo produto com campos para nome, valor e categoria.
Envia uma requisição POST para a API para adicionar o produto.
Editar Produto:

Modal para editar as informações de um produto existente.
Atualiza os dados do produto via requisição POST para a API.
Excluir Produto:

Modal de confirmação para excluir um produto.
Envia uma requisição POST para a API para remover o produto.
Listagem de Pedidos:

Exibe uma tabela com todos os pedidos cadastrados, mostrando ID, nome do cliente, data e status do pedido.
Botões de ação para editar e apagar cada pedido.
Adicionar Pedido:

Modal para inserir um novo pedido com campos para nome do cliente, data e status.
Envia uma requisição POST para a API para adicionar o pedido.
Editar Pedido:

Modal para editar as informações de um pedido existente.
Atualiza os dados do pedido via requisição POST para a API.
Excluir Pedido:

Modal de confirmação para excluir um pedido.
Envia uma requisição POST para a API para remover o pedido.
Endpoints da API
Produtos
Adicionar Produto
URL: http://localhost:8000/produto/add
Método: POST
Descrição: Adiciona um novo produto ao banco de dados.
Corpo da Requisição:
json
Copiar código
{
  "nome_produto": "Nome do Produto",
  "valor_produto": 100.00,
  "categoria_produto": "Categoria"
}
Atualizar Produto
URL: http://localhost:8000/produto/update
Método: POST
Descrição: Atualiza as informações de um produto existente.
Corpo da Requisição:
json
Copiar código
{
  "id": 1,
  "nome_produto": "Nome Atualizado",
  "valor_produto": 150.00,
  "categoria_produto": "Categoria Atualizada"
}
Excluir Produto
URL: http://localhost:8000/produto/delete
Método: POST
Descrição: Remove um produto do banco de dados.
Corpo da Requisição:
json
Copiar código
{
  "id": 1
}
Listar Todos os Produtos
URL: http://localhost:8000/produto/list
Método: GET
Descrição: Obtém todos os produtos cadastrados.
Resposta:
json
Copiar código
[
  {
    "id_produto": 1,
    "nome_produto": "Produto 1",
    "valor_produto": 100.00,
    "categoria_produto": "Categoria 1"
  },
  {
    "id_produto": 2,
    "nome_produto": "Produto 2",
    "valor_produto": 200.00,
    "categoria_produto": "Categoria 2"
  }
]
Pedidos
Adicionar Pedido
URL: http://localhost:8000/pedido/add
Método: POST
Descrição: Adiciona um novo pedido ao banco de dados.
Corpo da Requisição:
json
Copiar código
{
  "nome_cliente": "Nome do Cliente",
  "data_pedido": "2024-09-16",
  "status_pedido": "Em andamento"
}
Atualizar Pedido
URL: http://localhost:8000/pedido/update
Método: POST
Descrição: Atualiza as informações de um pedido existente.
Corpo da Requisição:
json
Copiar código
{
  "id": 1,
  "nome_cliente": "Nome do Cliente Atualizado",
  "data_pedido": "2024-09-17",
  "status_pedido": "Concluído"
}
Excluir Pedido
URL: http://localhost:8000/pedido/delete
Método: POST
Descrição: Remove um pedido do banco de dados.
Corpo da Requisição:
json
Copiar código
{
  "id": 1
}
Listar Todos os Pedidos
URL: http://localhost:8000/pedido/list
Método: GET
Descrição: Obtém todos os pedidos cadastrados.
Resposta:
json
Copiar código
[
  {
    "id_pedido": 1,
    "nome_cliente": "Cliente 1",
    "data_pedido": "2024-09-15",
    "status_pedido": "Em andamento"
  },
  {
    "id_pedido": 2,
    "nome_cliente": "Cliente 2",
    "data_pedido": "2024-09-16",
    "status_pedido": "Concluído"
  }
]
Configuração do Ambiente
Configurar o Banco de Dados:

Configure o banco de dados no arquivo Database.php com suas credenciais.
Endpoints da API:

Certifique-se de que os endpoints da API estão corretamente configurados e acessíveis a partir da aplicação web.
Instalação do Bootstrap 5:

Inclua o Bootstrap 5 no seu projeto através da CDN.
Scripts JavaScript:

Garanta que os scripts JavaScript estão corretamente carregados para interação com os modais e formulários.
Como Rodar o Projeto
Configurar o Servidor PHP:

Inicie o servidor PHP para servir a aplicação.
Acessar a Aplicação:

Abra o navegador e acesse a aplicação através do endereço configurado (e.g., http://localhost:8000).
Testar Funcionalidades:

Adicione, edite e exclua produtos e pedidos para verificar se todas as funcionalidades estão funcionando corretamente.
Contribuições
Se você deseja contribuir para o projeto, por favor, abra uma issue ou envie um pull request com suas alterações.
