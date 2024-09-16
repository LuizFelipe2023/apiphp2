<?php

require_once __DIR__ . '/../controllers/ProdutoController.php';
require_once __DIR__ . '/../controllers/PedidoController.php';
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

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

function getRequestBody() {
    $body = file_get_contents('php://input');
    return $body ? json_decode($body, true) : [];
}

switch ($uri) {
    case '/produtos':
        if ($method === 'GET') {
            $result = $produtoController->getAllProducts();
            echo json_encode([
                'status' => 'success',
                'data' => $result
            ]);
        } else {
            http_response_code(405);
            echo json_encode([
                'status' => 'error',
                'message' => 'Método não permitido. Utilize GET para obter produtos.'
            ]);
        }
        break;

    case '/produto':
        if ($method === 'POST') {
            $data = getRequestBody();
            if (isset($data['nome_produto'], $data['valor_produto'], $data['categoria_produto'])) {
                $result = $produtoController->insertProduct($data['nome_produto'], $data['valor_produto'], $data['categoria_produto']);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Produto inserido com sucesso.',
                    'data' => $result
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Dados do produto incompletos. Verifique os campos nome_produto, valor_produto e categoria_produto.',
                    'data_received' => $data
                ]);
            }
        } elseif ($method === 'PUT') {
            $data = getRequestBody();
            if (isset($data['id'], $data['nome_produto'], $data['valor_produto'], $data['categoria_produto'])) {
                $result = $produtoController->updateProduct($data['id'], $data['nome_produto'], $data['valor_produto'], $data['categoria_produto']);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Produto atualizado com sucesso.',
                    'data' => $result
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Dados do produto incompletos. Verifique os campos id, nome_produto, valor_produto e categoria_produto.',
                    'data_received' => $data
                ]);
            }
        } elseif ($method === 'DELETE') {
            parse_str(file_get_contents('php://input'), $data);
            if (isset($data['id'])) {
                $result = $produtoController->deleteProduct($data['id']);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Produto deletado com sucesso.',
                    'data' => $result
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'ID do produto não fornecido. Certifique-se de que o campo id está incluído no corpo da solicitação.',
                    'data_received' => $data
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode([
                'status' => 'error',
                'message' => 'Método não permitido para esta rota. Utilize POST, PUT ou DELETE conforme apropriado.'
            ]);
        }
        break;

    case '/pedidos':
        if ($method === 'GET') {
            $result = $pedidoController->getAll();
            echo json_encode([
                'status' => 'success',
                'data' => $result
            ]);
        } else {
            http_response_code(405);
            echo json_encode([
                'status' => 'error',
                'message' => 'Método não permitido. Utilize GET para obter pedidos.'
            ]);
        }
        break;

    case '/pedido':
        if ($method === 'POST') {
            $data = getRequestBody();
            if (
                isset($data['nome_cliente'], $data['cpf_cliente'], $data['endereco_cliente'], $data['data_pedido'], $data['produtos']) &&
                is_array($data['produtos']) &&
                !empty($data['produtos']) &&
                array_reduce($data['produtos'], fn($carry, $item) => $carry && isset($item['id'], $item['quantidade']), true)
            ) {
                $result = $pedidoController->criarPedido($data);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Pedido criado com sucesso.',
                    'data' => $result
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Dados do pedido incompletos ou mal formatados. Verifique os campos nome_cliente, cpf_cliente, endereco_cliente, data_pedido e produtos.',
                    'data_received' => $data
                ]);
            }
        } elseif ($method === 'PUT') {
            $data = getRequestBody();
            if (
                isset($data['id'], $data['nome_cliente'], $data['cpf_cliente'], $data['endereco_cliente'], $data['data_pedido'], $data['produtos']) &&
                is_array($data['produtos']) &&
                !empty($data['produtos']) &&
                array_reduce($data['produtos'], fn($carry, $item) => $carry && isset($item['id'], $item['quantidade']), true)
            ) {
                $result = $pedidoController->atualizarPedido($data['id'], $data);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Pedido atualizado com sucesso.',
                    'data' => $result
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Dados do pedido incompletos ou mal formatados. Verifique os campos id, nome_cliente, cpf_cliente, endereco_cliente, data_pedido e produtos.',
                    'data_received' => $data
                ]);
            }
        } elseif ($method === 'DELETE') {
            parse_str(file_get_contents('php://input'), $data);
            if (isset($data['id'])) {
                $result = $pedidoController->deletePedido($data['id']);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Pedido deletado com sucesso.',
                    'data' => $result
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'ID do pedido não fornecido. Certifique-se de que o campo id está incluído no corpo da solicitação.',
                    'data_received' => $data
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode([
                'status' => 'error',
                'message' => 'Método não permitido para esta rota. Utilize POST, PUT ou DELETE conforme apropriado.'
            ]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Rota não encontrada. Verifique a URL e tente novamente.'
        ]);
        break;
}
