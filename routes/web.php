<?php

require_once __DIR__ . '/../controllers/ProdutoController.php';
require_once __DIR__ . '/../controllers/PedidoController.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../config/Database.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$database = new Database();
$conn = $database->getConnection();

$produtoController = new ProdutoController($conn);
$pedidoController = new PedidoController($conn);
$usuarioController = new UsuarioController($conn);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

function isAuthenticated()
{
    return isset($_SESSION['token']);
}

function isAdmin()
{
    if (session_status() === PHP_SESSION_NONE) {
        return 'Sessão não iniciada.';
    }

    if (!isset($_SESSION['isAdmin'])) {
        return 'Status de administrador não definido.';
    }

    return $_SESSION['isAdmin'] ?? false;
}

function getRequestBody()
{
    $body = file_get_contents('php://input');
    return $body ? json_decode($body, true) : [];
}

function checkAuth()
{
    if (!isAuthenticated()) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado.']);
        exit;
    }
}

function handleError($message, $statusCode = 400, $data = [])
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => $message, 'data_received' => $data]);
}

function route($path, $controller, $actions)
{
    global $uri, $method;
    if ($uri === $path) {
        if (isset($actions[$method])) {
            $actions[$method]();
        } else {
            handleError('Método não permitido.', 405);
        }
        exit;
    }
}

route('/produtos', $produtoController, [
    'GET' => function () use ($produtoController) {
        if (isAdmin() !== true) {
            handleError('Permissão negada.', 403);
        }
        checkAuth();
        $result = $produtoController->getAllProducts();
        // echo json_encode(['status' => 'success', 'data' => $result]);
    }
]);

route('/retorna-produto', $produtoController, [
    'GET' => function () use ($produtoController) {
        if (isAdmin() !== true) {
            handleError('Permissão negada.', 403);
        }
        checkAuth();
        $id = $_GET['id'] ?? null;
        if (is_numeric($id)) {
            $result = $produtoController->getProduct($id);
            // echo json_encode(['status' => 'success', 'data' => $result]);
        } else {
            handleError('ID inválido.', 400);
        }
    }
]);

route('/produto', $produtoController, [
    'POST' => function () use ($produtoController) {
        checkAuth();
        $data = getRequestBody();
        if (isset($data['nome_produto'], $data['valor_produto'], $data['categoria_produto'])) {
            $result = $produtoController->insertProduct($data['nome_produto'], $data['valor_produto'], $data['categoria_produto']);
            // echo json_encode(['status' => 'success', 'message' => 'Produto inserido com sucesso.', 'data' => $result]);
        } else {
            handleError('Dados incompletos.', 400, $data);
        }
    },
    'PUT' => function () use ($produtoController) {
        checkAuth();
        $data = getRequestBody();
        if (isset($data['id'], $data['nome_produto'], $data['valor_produto'], $data['categoria_produto'])) {
            $result = $produtoController->updateProduct($data['id'], $data['nome_produto'], $data['valor_produto'], $data['categoria_produto']);
            // echo json_encode(['status' => 'success', 'message' => 'Produto atualizado com sucesso.', 'data' => $result]);
        } else {
            handleError('Dados incompletos.', 400, $data);
        }
    },
    'DELETE' => function () use ($produtoController) {
        checkAuth();
        $data = getRequestBody();
        if (isset($data['id'])) {
            $result = $produtoController->deleteProduct($data['id']);
            // echo json_encode(['status' => 'success', 'message' => 'Produto deletado com sucesso.', 'data' => $result]);
        } else {
            handleError('ID não fornecido.', 400, $data);
        }
    }
]);

route('/pedidos', $pedidoController, [
    'GET' => function () use ($pedidoController) {
        if (isAdmin() !== true) {
            handleError('Permissão negada.', 403);
        }
        checkAuth();
        $result = $pedidoController->getAll();
        // echo json_encode(['status' => 'success', 'data' => $result]);
    }
]);

route('/retorna-pedido', $pedidoController, [
    'GET' => function () use ($pedidoController) {
        if (isAdmin() !== true) {
            handleError('Permissão negada.', 403);
        }
        checkAuth();
        $id = $_GET['id'] ?? null;
        if (is_numeric($id)) {
            $result = $pedidoController->getbyId($id);
            // echo json_encode(['status' => 'success', 'data' => $result]);
        } else {
            handleError('ID inválido.', 400);
        }
    }
]);

route('/pedido', $pedidoController, [
    'POST' => function () use ($pedidoController) {
        checkAuth();
        $data = getRequestBody();
        if (isset($data['nome_cliente'], $data['cpf_cliente'], $data['endereco_cliente'], $data['data_pedido'], $data['produtos'])) {
            if (is_array($data['produtos']) && !empty($data['produtos']) && array_reduce($data['produtos'], fn($carry, $item) => $carry && isset($item['id'], $item['quantidade']), true)) {
                $result = $pedidoController->criarPedido($data);
                // echo json_encode(['status' => 'success', 'message' => 'Pedido criado com sucesso.', 'data' => $result]);
            } else {
                handleError('Dados do produto inválidos.', 400, $data);
            }
        } else {
            handleError('Dados do pedido incompletos.', 400, $data);
        }
    },
    'PUT' => function () use ($pedidoController) {
        checkAuth();
        $data = getRequestBody();
        if (isset($data['id'], $data['nome_cliente'], $data['cpf_cliente'], $data['endereco_cliente'], $data['data_pedido'], $data['produtos'])) {
            if (is_array($data['produtos']) && !empty($data['produtos']) && array_reduce($data['produtos'], fn($carry, $item) => $carry && isset($item['id'], $item['quantidade']), true)) {
                $result = $pedidoController->atualizarPedido($data['id'], $data);
                // echo json_encode(['status' => 'success', 'message' => 'Pedido atualizado com sucesso.', 'data' => $result]);
            } else {
                handleError('Dados do produto inválidos.', 400, $data);
            }
        } else {
            handleError('Dados do pedido incompletos.', 400, $data);
        }
    },
    'DELETE' => function () use ($pedidoController) {
        checkAuth();
        $data = getRequestBody();
        if (isset($data['id'])) {
            $result = $pedidoController->deletePedido($data['id']);
            // echo json_encode(['status' => 'success', 'message' => 'Pedido deletado com sucesso.', 'data' => $result]);
        } else {
            handleError('ID não fornecido.', 400, $data);
        }
    }
]);

route('/usuarios', $usuarioController, [
    'GET' => function () use ($usuarioController) {
        if (isAdmin() !== true) {
            handleError('Permissão negada.', 403);
        }
        checkAuth();
        $result = $usuarioController->getAllUsers();
        // echo json_encode(['status' => 'success', 'data' => $result]);
    },
    'POST' => function () use ($usuarioController) {
        $data = getRequestBody();
        if (isset($data['nome'], $data['email'], $data['password'], $data['cpf'], $data['data_nascimento'], $data['telefone'])) {
            $result = $usuarioController->criarUsuario($data['nome'], $data['email'], $data['password'], $data['cpf'], $data['data_nascimento'], $data['telefone']);
            echo json_encode(['status' => 'success', 'message' => 'Usuário criado com sucesso.', 'data' => $result]);
        } else {
            handleError('Dados do usuário incompletos.', 400, $data);
        }
    }
]);

route('/login', $usuarioController, [
    'POST' => function () use ($usuarioController) {
        $data = getRequestBody();
        if (isset($data['email'], $data['password'])) {
            $result = $usuarioController->login($data['email'], $data['password']);
            // echo json_encode(['status' => 'success', 'message' => 'Usuário autenticado com sucesso.', 'data' => $result]);
        } else {
            handleError('Dados de login incompletos.', 400, $data);
        }
    }
]);

route('/logout', $usuarioController, [
    'POST' => function () use ($usuarioController) {
        $result = $usuarioController->logout();
        // echo json_encode(['status' => 'success', 'message' => 'Usuário deslogado com sucesso.', 'data' => $result]);
    }
]);

route('/solicitar-redefinicao-senha', $usuarioController, [
    'POST' => function () use ($usuarioController) {
        $data = getRequestBody();
        if (isset($data['email'])) {
            $result = $usuarioController->solicitarRedefinicaoSenha($data['email']);
            // echo json_encode(['status' => 'success', 'message' => 'Instruções para redefinir a senha enviadas para o e-mail.', 'data' => $result]);
        } else {
            handleError('E-mail não fornecido.', 400, $data);
        }
    }
]);

route('/redefinir-senha', $usuarioController, [
    'POST' => function () use ($usuarioController) {
        $data = getRequestBody();
        if (isset($data['token'], $data['senha'])) {
            $result = $usuarioController->redefinirSenha($data['token'], $data['senha'],$data['confirmacao_senha']);
            // echo json_encode(['status' => 'success', 'message' => 'Senha redefinida com sucesso.', 'data' => $result]);
        } else {
            handleError('Token ou senha não fornecidos.', 400, $data);
        }
    }
]);

handleError('Rota não encontrada.', 404);
