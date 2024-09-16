<?php
require_once __DIR__ . '/../controllers/ProdutoController.php';
require_once __DIR__ . '/../config/Database.php';

$db = new Database();
$conn = $db->getConnection();

$produtoController = new ProdutoController($conn);
$produtos = $produtoController->getAllProducts();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/index.css">
</head>

<body>
    <div class="container-sm mt-5 py-5">
        <div class="row justify-content-center">
            <div class="col-8-md col-lg-6">
                <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-custom">

                    <a class="navbar-brand" href="#">Meu App</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Produtos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Sobre</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Contato</a>
                            </li>
                        </ul>
                    </div>

                </nav>
            </div>
        </div>
    </div>

    <div class="container-md my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="table-container p-4 mb-4">
                    <h2 class="mb-4">Produtos</h2>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Valor</th>
                                <th>Categoria</th>
                                <th>Ações</th> <!-- Nova coluna para ações -->
                            </tr>
                        </thead>
                        <tbody id="products-section">
                            <?php if (is_array($produtos) && !empty($produtos)): ?>
                                <?php foreach ($produtos as $produto): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($produto['nome_produto']); ?></td>
                                        <td>R$ <?php echo number_format($produto['valor_produto'], 2, ',', '.'); ?></td>
                                        <td><?php echo htmlspecialchars($produto['categoria_produto']); ?></td>
                                        <td>
                                            <!-- Botões para Editar e Apagar -->
                                            <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $produto['id_produto']; ?>" data-nome="<?php echo $produto['nome_produto']; ?>" data-valor="<?php echo $produto['valor_produto']; ?>" data-categoria="<?php echo $produto['categoria_produto']; ?>" data-bs-toggle="modal" data-bs-target="#editProductModal">Editar</button>
                                            <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $produto['id_produto']; ?>" data-bs-toggle="modal" data-bs-target="#deleteProductModal">Apagar</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">Nenhum produto encontrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">Adicionar Produto</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para adicionar produtos -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Adicionar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm">
                        <div class="mb-3">
                            <label for="nomeProduto" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nomeProduto" required>
                        </div>
                        <div class="mb-3">
                            <label for="valorProduto" class="form-label">Valor</label>
                            <input type="number" step="0.01" class="form-control" id="valorProduto" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoriaProduto" class="form-label">Categoria</label>
                            <input type="text" class="form-control" id="categoriaProduto" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Adicionar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar produtos -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Editar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm">
                        <input type="hidden" id="editProdutoId">
                        <div class="mb-3">
                            <label for="editNomeProduto" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="editNomeProduto" required>
                        </div>
                        <div class="mb-3">
                            <label for="editValorProduto" class="form-label">Valor</label>
                            <input type="number" step="0.01" class="form-control" id="editValorProduto" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCategoriaProduto" class="form-label">Categoria</label>
                            <input type="text" class="form-control" id="editCategoriaProduto" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para apagar produtos -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProductModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja apagar este produto?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Apagar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let produtoIdToDelete;

            // Configurar o modal de exclusão
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    produtoIdToDelete = this.getAttribute('data-id');
                });
            });

            document.getElementById('confirmDelete').addEventListener('click', function() {
                fetch('http://localhost:8000/produto/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: produtoIdToDelete,
                        action: 'delete'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Recarregar a página após sucesso
                    } else {
                        alert('Erro ao apagar o produto: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Erro ao apagar o produto: ' + error);
                });
            });

            // Configurar o modal de edição com os dados do produto
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const produtoId = this.getAttribute('data-id');
                    const nome = this.getAttribute('data-nome');
                    const valor = this.getAttribute('data-valor');
                    const categoria = this.getAttribute('data-categoria');

                    document.getElementById('editProdutoId').value = produtoId;
                    document.getElementById('editNomeProduto').value = nome;
                    document.getElementById('editValorProduto').value = valor;
                    document.getElementById('editCategoriaProduto').value = categoria;
                });
            });

            // Enviar o formulário de edição via fetch
            document.getElementById('editProductForm').addEventListener('submit', function(event) {
                event.preventDefault();

                const produtoId = document.getElementById('editProdutoId').value;
                const nome = document.getElementById('editNomeProduto').value;
                const valor = document.getElementById('editValorProduto').value;
                const categoria = document.getElementById('editCategoriaProduto').value;

                fetch('http://localhost:8000/produto', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: produtoId,
                        nome_produto: nome,
                        valor_produto: valor,
                        categoria_produto: categoria,
                        action: 'update'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Recarregar a página após sucesso
                    } else {
                        alert('Erro ao atualizar o produto: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Erro ao atualizar o produto: ' + error);
                });
            });
        });
    </script>
</body>

</html>
