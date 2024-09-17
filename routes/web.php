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

function getRequestBody()
{
    $body = file_get_contents('php://input');
    return $body ? json_decode($body, true) : [];
}

function checkAuth()
{
    if (!isAuthenticated()) {
        http_response_code(403);
        echo json_encode([
            'status' => 'error',
            'message' => 'Usuário não autenticado. Faça o login para acessar esta rota.'
        ]);
        exit;
    }
}

function handleError($message, $statusCode = 400, $data = [])
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => $message,
        'data_received' => $data
    ]);
}

switch ($uri) {
    case '/produtos':
        if ($method === 'GET') {
            checkAuth();
            $result = $produtoController->getAllProducts();
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'data' => $result
            ]);
        } else {
            handleError('Método não permitido. Utilize GET para obter produtos.', 405);
        }
        break;

    case '/produto':
        if ($method === 'POST') {
            checkAuth();
            $data = getRequestBody();
            if (isset($data['nome_produto'], $data['valor_produto'], $data['categoria_produto'])) {
                $result = $produtoController->insertProduct($data['nome_produto'], $data['valor_produto'], $data['categoria_produto']);
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Produto inserido com sucesso.',
                    'data' => $result
                ]);
            } else {
                handleError('Dados do produto incompletos. Verifique os campos nome_produto, valor_produto e categoria_produto.', 400, $data);
            }
        } elseif ($method === 'PUT') {
            checkAuth();
            $data = getRequestBody();
            if (isset($data['id'], $data['nome_produto'], $data['valor_produto'], $data['categoria_produto'])) {
                $result = $produtoController->updateProduct($data['id'], $data['nome_produto'], $data['valor_produto'], $data['categoria_produto']);
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Produto atualizado com sucesso.',
                    'data' => $result
                ]);
            } else {
                handleError('Dados do produto incompletos. Verifique os campos id, nome_produto, valor_produto e categoria_produto.', 400, $data);
            }
        } elseif ($method === 'DELETE') {
            checkAuth();
            $data = getRequestBody(); 
            if (isset($data['id'])) {
                $result = $produtoController->deleteProduct($data['id']);
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Produto deletado com sucesso.',
                    'data' => $result
                ]);
            } else {
                handleError('ID do produto não fornecido. Certifique-se de que o campo id está incluído no corpo da solicitação.', 400, $data);
            }
        } else {
            handleError('Método não permitido para esta rota. Utilize POST, PUT ou DELETE conforme apropriado.', 405);
        }
        break;

    case '/pedidos':
        if ($method === 'GET') {
            checkAuth();
            $result = $pedidoController->getAll();
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'data' => $result
            ]);
        } else {
            handleError('Método não permitido. Utilize GET para obter pedidos.', 405);
        }
        break;

    case '/pedido':
        if ($method === 'POST') {
            checkAuth();
            $data = getRequestBody();
            if (
                isset($data['nome_cliente'], $data['cpf_cliente'], $data['endereco_cliente'], $data['data_pedido'], $data['produtos']) &&
                is_array($data['produtos']) &&
                !empty($data['produtos']) &&
                array_reduce($data['produtos'], fn($carry, $item) => $carry && isset($item['id'], $item['quantidade']), true)
            ) {
                $result = $pedidoController->criarPedido($data);
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Pedido criado com sucesso.',
                    'data' => $result
                ]);
            } else {
                handleError('Dados do pedido incompletos ou mal formatados. Verifique os campos nome_cliente, cpf_cliente, endereco_cliente, data_pedido e produtos.', 400, $data);
            }
        } elseif ($method === 'PUT') {
            checkAuth();
            $data = getRequestBody();
            if (
                isset($data['id'], $data['nome_cliente'], $data['cpf_cliente'], $data['endereco_cliente'], $data['data_pedido'], $data['produtos']) &&
                is_array($data['produtos']) &&
                !empty($data['produtos']) &&
                array_reduce($data['produtos'], fn($carry, $item) => $carry && isset($item['id'], $item['quantidade']), true)
            ) {
                $result = $pedidoController->atualizarPedido($data['id'], $data);
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Pedido atualizado com sucesso.',
                    'data' => $result
                ]);
            } else {
                handleError('Dados do pedido incompletos ou mal formatados. Verifique os campos id, nome_cliente, cpf_cliente, endereco_cliente, data_pedido e produtos.', 400, $data);
            }
        } elseif ($method === 'DELETE') {
            checkAuth();
            $data = getRequestBody(); // Use getRequestBody() para lidar com JSON
            if (isset($data['id'])) {
                $result = $pedidoController->deletePedido($data['id']);
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Pedido deletado com sucesso.',
                    'data' => $result
                ]);
            } else {
                handleError('ID do pedido não fornecido. Certifique-se de que o campo id está incluído no corpo da solicitação.', 400, $data);
            }
        } else {
            handleError('Método não permitido para esta rota. Utilize POST, PUT ou DELETE conforme apropriado.', 405);
        }
        break;

    case '/usuarios':
        if ($method === 'POST') {
            $data = getRequestBody();
            if (isset($data['nome'], $data['email'], $data['password'], $data['cpf'], $data['endereco'])) {
                $usuarioController->criarUsuario($data['nome'], $data['email'], $data['password'], $data['cpf'], $data['endereco']);
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Usuário criado com sucesso.'
                ]);
            } else {
                handleError('Dados do usuário incompletos. Verifique os campos nome, email, password, cpf e endereco.', 400, $data);
            }
        } else {
            handleError('Método não permitido para esta rota. Utilize POST para criar um usuário.', 405);
        }
        break;

    case '/login':
        if ($method === 'POST') {
            $data = getRequestBody();
            if (isset($data['email'], $data['password'])) {
                $usuarioController->login($data['email'], $data['password']);
            } else {
                handleError('Dados de login incompletos. Verifique os campos email e password.', 400, $data);
            }
        } else {
            handleError('Método não permitido para esta rota. Utilize POST para login.', 405);
        }
        break;

    case '/logout':
        if ($method === 'POST') {
            session_start();
            session_destroy();
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'message' => 'Logout realizado com sucesso.'
            ]);
        } else {
            handleError('Método não permitido para esta rota. Utilize POST para logout.', 405);
        }
        break;

        case '/solicitar-redefinicao-senha':
            if ($method === 'POST') {
                $data = getRequestBody();
                if (isset($data['email'])) {
                    $usuarioController->solicitarRedefinicaoSenha($data['email']);
                } else {
                    handleError('Dados incompletos. Verifique o campo email.', 400, $data);
                }
            } else {
                handleError('Método não permitido para esta rota. Utilize POST para solicitar redefinição de senha.', 405);
            }
            break;
        
            case '/redefinir-senha':
                if ($method === 'POST') {
                    $data = getRequestBody();
                    if (isset($data['token'], $data['novaSenha'], $data['confirmacaoSenha'])) {
                        $usuarioController->redefinirSenha($data['token'], $data['novaSenha'], $data['confirmacaoSenha']);
                    } else {
                        handleError('Dados incompletos. Verifique os campos token, novaSenha e confirmacaoSenha.', 400, $data);
                    }
                } else {
                    handleError('Método não permitido para esta rota. Utilize POST para redefinir a senha.', 405);
                }
                break;
            

    default:
        handleError('Rota não encontrada.', 404);
        break;
}
