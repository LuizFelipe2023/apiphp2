<?php
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../config/Database.php';

class PedidoController
{
    private $pedido;

    public function __construct()
    {
        $database = new Database();
        $conn = $database->getConnection();
        $this->pedido = new Pedido($conn);
    }

    public function getAll()
    {
        try {
            $result = $this->pedido->getAllPedidos();
            echo json_encode($result);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Não foi possível carregar a lista de pedidos. Por favor, tente novamente mais tarde."
            ]);
        }
    }

    public function getbyId($id)
    {
        try {
            $result = $this->pedido->getPedidobyId($id);
            echo json_encode($result);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Não foi possível carregar o pedido. Por favor, tente novamente mais tarde."
            ]);
        }
    }

    public function criarPedido($data)
    {
        try {
            if (!is_array($data)) {
                throw new InvalidArgumentException('Os dados do pedido devem ser um array.');
            }

            $dataPedido = $data['data_pedido'] ?? null;
            $nomeCliente = $data['nome_cliente'] ?? null;
            $cpfCliente = $data['cpf_cliente'] ?? null;
            $enderecoCliente = $data['endereco_cliente'] ?? null;
            $produtos = $data['produtos'] ?? [];

            if (!$dataPedido || !$nomeCliente || !$cpfCliente || !$enderecoCliente || empty($produtos)) {
                throw new InvalidArgumentException('Todos os campos são obrigatórios: data_pedido, nome_cliente, cpf_cliente, endereco_cliente e produtos.');
            }

            $this->pedido->setDetails($nomeCliente, $cpfCliente, $enderecoCliente, $dataPedido);
            $this->pedido->setProdutos($produtos);

            $result = $this->pedido->criarPedido();
            echo json_encode($result);
        } catch (InvalidArgumentException $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Por favor, preencha todos os campos obrigatórios corretamente. " . $e->getMessage()
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Não foi possível criar o pedido. Verifique os dados e tente novamente."
            ]);
        }
    }

    public function atualizarPedido($pedidoId, $data)
    {
        try {
            if (!is_array($data)) {
                throw new InvalidArgumentException('Os dados do pedido devem ser um array.');
            }

            $dataPedido = $data['data_pedido'] ?? null;
            $nomeCliente = $data['nome_cliente'] ?? null;
            $cpfCliente = $data['cpf_cliente'] ?? null;
            $enderecoCliente = $data['endereco_cliente'] ?? null;
            $produtos = $data['produtos'] ?? [];

            if (!$dataPedido || !$nomeCliente || !$cpfCliente || !$enderecoCliente || empty($produtos)) {
                throw new InvalidArgumentException('Todos os campos são obrigatórios: data_pedido, nome_cliente, cpf_cliente, endereco_cliente e produtos.');
            }

            $this->pedido->setDetails($nomeCliente, $cpfCliente, $enderecoCliente, $dataPedido);
            $this->pedido->setProdutos($produtos);

            $result = $this->pedido->atualizarPedido($pedidoId);
            echo json_encode($result);
        } catch (InvalidArgumentException $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Os dados fornecidos estão incompletos. " . $e->getMessage()
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Não foi possível atualizar o pedido. Verifique os dados e tente novamente."
            ]);
        }
    }

    public function deletePedido($id)
    {
        try {
            $result = $this->pedido->deletePedido($id);
            echo json_encode($result);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Não foi possível deletar o pedido. Por favor, tente novamente mais tarde."
            ]);
        }
    }
}
